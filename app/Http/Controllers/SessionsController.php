<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;                                    // access to the request() function for getting post parameters
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;    

use GuzzleHttp\Client;                                          // sending and http requests and hadling responses
use App\Http\Middleware;                                        // don't think we're using any middleware
use DB;                                                         // db raw sql statements (may not need this here)
use Auth;                                                       // user information
use Carbon\Carbon; 

// Symphony Services                          
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException; 

// NO LONGER USED. Remove from composer dependencies and then delete
use Lcobucci\JWT\Builder; 
use Lcobucci\JWT\Parser;
use Cocur\BackgroundProcess\BackgroundProcess; 

// Custom
use App\User;
use App\Session;
use App\ApiHelper;
use App\Events\AttendanceUpdated;
use App\Jobs\ProcessSession;
use App\Notifications\SessionDeleted;
use App\Notifications\SessionNotification;

use Brian2694\Toastr\Facades\Toastr;




class SessionsController extends Controller
{

    /* All sessions 
    ---------------*/
    public function index(){
        // move to a scheduled task?
        // automatically update all of the upcoming events that have already happened
        // this compares the event to yesterday's date at 7:00 PM
        $yesterday = Carbon::yesterday()->setTimeZone('America/Chicago');
        Session::where('date', '<=', $yesterday)->where('status', 'upcoming')->update(['status' => 'completed']);

        return view ('sessions.index');
    }



    /* Show individual Session 
    --------------------------*/
    public function show($session_id, $session_key, $student_id = null){

        // store session identifiers in SESSION for swipr uses
        session(['current_id' => $session_id]);
        session(['current_key' => $session_key]);

        // get the session matching the id and key parameters
        $session = Session::where('session_id', $session_id)->where('session_key', $session_key)->first();

        // get list of all students present at the event from Atrack
        $attendance_list = ApiHelper::getAttendanceList($session_id, $session_key);

        // update the attendance on a sessions
        Session::where('session_id', $session_id)->where('session_key', $session_key)->update(['attendance' => count($attendance_list)]);

        $session->student_id = $student_id;

        return view('sessions.show', compact('session', 'attendance_list'));
    }



    /* Navigate to Edit page of individual Session 
    --------------------------*/
    public function edit($session_id, $session_key){

        // get the session matching the id and key parameters
        $session = Session::where('session_id', $session_id)->where('session_key', $session_key)->first();

        // get list of all students present at the event from Atrack
        $attendance_list = ApiHelper::getAttendanceList($session_id, $session_key);

        return view('sessions.edit', compact('session', 'attendance_list'));
        
    }



    /* Actually perform the Session Updating in the Database
    --------------------------*/
    public function update($session_id, $session_key) {
        //Toastr::success('Session Successfully updated', 'Session Title', ["positionClass" => "toast-top-right"]);

        if (request('startDate') == null) {
            Toastr::error('Sessions cannot be updated without a date', request('title'), ["positionClass" => "toast-top-right"]);
            // Auth::user()->notify((new SessionNotification(request('title'), 'fail', 'A date is required to make a session.'))->delay(2));
            return redirect('/Sessions');
        } else if (Carbon::yesterday()->setTimeZone('America/Chicago') >= (request('startDate'))) {
            Toastr::warning('This sessions date has already passed', request('title'), ["positionClass" => "toast-top-right"]);
            // Auth::user()->notify((new SessionNotification(request('title'), 'warning', 'Your session is updated, but in the past.'))->delay(2));
        } else {
            Toastr::success('Session successfully updated', request('title'), ["positionClass" => "toast-top-right"]);
        }
        Session::where('session_id', $session_id)->where('session_key', $session_key)->update(['title' => request('title'), 'date' => request('startDate'), 'duration' => request('duration')]);
        // Auth::user()->notify((new SessionNotification(request('title'), 'success', 'You have successfully updated your session.'))->delay(2));
        return redirect('/Sessions');
    }



    /* Delete the session from the databases
    --------------------------*/
    public function delete($session_id, $session_key) {
        // first get the sessions title that we are going to delete
        $session = Session::where('session_id', $session_id)->where('session_key', $session_key)->first();
        $sessionTitle = $session->title;
        // delete the session from the database
        $deleteValue = Session::where('session_id', $session_id)->where('session_key', $session_key)->delete();
        if ($deleteValue == 1) {
            // session was succesfully deleted
            // notify the user that the session has been deleted
            Toastr::success('Session successfully deleted', request('title'), ["positionClass" => "toast-top-right"]);
            //Auth::user()->notify((new SessionNotification($sessionTitle, 'success', 'You have successfully deleted your event.'))->delay(2));
        } else {
            // notify the user that the session failed to delete
            Toastr::error('This session could not be deleted.', request('title'), ["positionClass" => "toast-top-right"]);
            //Auth::user()->notify((new SessionNotification($sessionTitle, 'fail', 'Your message failed to delete.'))->delay(2));
        }
        
        return redirect('/Sessions');
    }



    /* Create Event Form 
    --------------------*/
    public function create(){
        // boop
        return view('sessions.create');
    }



    /* Generate new session 
    -----------------------*/
    public function store(){
        // create session in Atrack.
        // This will be used to retrieve the class for the user
        if (strlen(Auth::user()->class) > 0) {
            $class =  Auth::user()->class;
            
            // get the number of sessions the user currently has with this same class
            $numberSessions = Session::where('title', 'like', $class . " " . Auth::user()->name . " " . request('sessionType') .'%')->get()->count() + 1;

            // Default Titles: ClassName, SI Leader Name, Session Type, Number of that type
            $title = $class . " " . Auth::user()->name . " " .request('sessionType') . " " . $numberSessions;    
        } else {
            $title = request('title');
        }
        if (request('startDate') == null) {
            //Auth::user()->notify((new SessionNotification($title, 'fail', 'A date is required to make a session.'))->delay(2));
            Toastr::error('Sessions cannot be created without a date', request('title'), ["positionClass" => "toast-top-right"]);
            return redirect('/Sessions');
        } 
        $responseBody = ApiHelper::createSession($title, request('startDate'));
      

	// Really we should have an environment variable incase sistaff email changes.  
	$proctors = Auth::user()->email . ' sistaff@iastate.edu'; 

 
        // create Session in database.
        $session = new Session;

        $session->title = $title;
        $session->session_id = $responseBody['id'];
        $session->session_key = $responseBody['key'];
        $session->proctors = $proctors;
        $session->date = request('startDate');
        $session->attendance = 0;
        $session->duration =  request('duration');
        $session->status = 'upcoming';
        
        $session->save();
        /* ^^ I think we can clean this up to be like -> ... = new Session(title->request('title'), ...); broken into multiple lines*/
        if (Carbon::yesterday()->setTimeZone('America/Chicago') >= ($session->date)) {
            //Auth::user()->notify((new SessionNotification($title, 'warning', 'Your session is in the past.'))->delay(2));    
            Toastr::warning('This sessions date has already passed', request('title'), ["positionClass" => "toast-top-right"]);
        } else {
            // Auth::user()->notify((new SessionNotification($title, 'success', 'You have successfully created a new session.'))->delay(2));
            Toastr::success('Session successfully created.', request('title'), ["positionClass" => "toast-top-right"]);
        }
        return redirect('/Sessions');
    }



    /* Check students in to a session
    ----------------------------------*/
    public function checkin($session_id, $session_key){

        $client = new Client();
        $response = $client->post('https://atrack.its.iastate.edu/api/check-in', ['json' => 
            [
                'eventId' => $session_id,
		        'eventKey' => $session_key,
		        'apiKey' => config('services.api_key'),
		        'user' => request('student')
            ] 
        ]);

        // Broadcast event to clients
        $session = Session::where('session_id', $session_id)->where('session_key', $session_key)->first();
        broadcast(new AttendanceUpdated($session->id));
        
        return redirect('/Sessions/'.$session_id.'-'.$session_key);
    }



    /* Mark a Session as Completed 
    ------------------------------*/
    public function complete($session_id, $session_key){
        // update status of the session matching specified id and key
        $session = Session::where('session_id', $session_id)->where('session_key', $session_key)->update(['status' => 'completed']);  

        // return to home page
        return redirect('/Sessions/');
    }



    /* Process an event
    -------------------*/
    public function process($session_id, $session_key){
        // get matching session
        $session = Session::where('session_id', $session_id)->where('session_key', $session_key)->first();
        // Session::where('session_id', $session_id)->where('session_key', $session_key)->update(['status' => 'processed']);  

        // dipatch the process session job
        dispatch(new ProcessSession(Auth::User(), $session));

        // notify the user that their job has been queued  :: Delay 5... we really need a separate queue for notifications so that this line can be AFTER the job is dispatched
        // Toastr::success("Queued for processing. We'll let you know when it's done!", $session->title, ["positionClass" => "toast-top-right"]);

        // return to home page once job has been queued
        return redirect('/Sessions/');
    }



    /* Swipr post route. Doesn't use CSRF
    ------------------------------------*/
    public function swipr (){

        // Get the session identifiers from _SESSION variables
        $session_id = session('current_id');
        $session_key = session('current_key');

        // Default id value
        $student_id = '000000000';  
        
        // Grab the student's ID from swipr's post data
        if( null !== request('track2') ){
            $student_id = substr(request('track2') , 7, 9);
        }

        // get the session matching the id and key parameters
        $session = Session::where('session_id', $session_id)->where('session_key', $session_key)->first();

        // get list of all students present at the event from Atrack
        $attendance_list = ApiHelper::getAttendanceList($session_id, $session_key);

        // add student_id to the session object
        $session->student_id = $student_id;

        return view('sessions.show', compact('session', 'attendance_list'));
    }



    /* Used by SessionTable Vue components to grab sessions
    ------------------------------------------------------*/
    public function getSession($session_type){

        // update status of the session matching specified id and key
        $sessions = Session::where('proctors', $proctorList)->where('status', $session_type)->get();

        return $sessions;
    }

}

<?php

namespace App\Jobs;

// Illuminate Requirements
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

// Symphony Requirements
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

// Framework Requirements
use Storage; 

// Custom Requirements
use App\Notifications\SessionProcessed;
use App\Notifications\SessionNotification;
use App\Session;
use App\ApiHelper;

// Development Requirements
use Illuminate\Support\Facades\Log;    


class ProcessSession implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $user;
    public $session;
    private $process_string;
    

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $session)
    {
        $this->user = $user;
        $this->session = $session;
        //$this->process_string = $process_string;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // for code readability assign key and id to local variables
        $session_id = $this->session->session_id;
        $session_key = $this->session->session_key;

        // grab session date
        $date = explode (' ' , $this->session->date)[0];


        // get list of all students present at given session
        $attendance_list = ApiHelper::getAttendanceList($session_id, $session_key);

        // If attendace_list is empty end and notify user
        if ( count($attendance_list) == 0 ){
            $this->user->notify(new SessionProcessed($this->session->title, 'fail', 'There are no students checked in to this session, so we were unable to process it.'));
            return false;
        }
        // Otherwise notify user that their session is queued
        else {
            //notify user that their process is about to be run
            $this->user->notify(new SessionProcessed($this->session->title, 'info', "Is queued for processing. We'll let you know once it has finished!"));
        }
        
        // generate a string of attendee names formatted for python
        $attendance_string = '';

        // create the attendance list file contents
        foreach($attendance_list as $person){
            $attendance_string .= $person['lastName'].' '.$person['firstName'].' '.$date."\n"; 
        }

        // Name for the session attendance list file
        $name_list = $session_id.'_'.$session_key.'.txt';

        // create the attendance list text file
        Storage::put($name_list, $attendance_string);

        // get Box access token
        $access_token = ApiHelper::getAccessToken();

        // grab the users net-id from email
        $user_name = strtolower(explode ('@' , $this->user->email)[0]);

        // String representation of bash command to be run
        $shell_string = config('services.python_path').' '.base_path('python/process.py ').' '.storage_path('app/'.$name_list).' '.$user_name.' '.$this->session->duration.' '.$access_token.' >> python/log.txt';

       
        //create and reun the process
        $process = new Process($shell_string);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $this->user->notify(new SessionNotification($this->session->title, 'fail', 'There appears to be something wrong with your excel file!'));
            throw new ProcessFailedException($process);
        }
        else {
            // notify the user that their event has been processed
            $this->user->notify(new SessionProcessed($this->session->title, 'success', 'Your session has been processed! Would you like to refresh the dashboard?'));

            // mark the session as processed
            Session::where('title', $this->session->title)->update(['status' => 'processed']);
        }
        
    }


     /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed()
    {
        // Called when the job is failing...

        // Move session back to processed

        // notify user "we tried processing your event 3 times and it failed 3 times."
    }
}

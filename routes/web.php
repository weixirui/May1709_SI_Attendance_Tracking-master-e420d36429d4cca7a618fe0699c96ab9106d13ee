<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;                                    // access to the request() function for getting post parameters
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;                             // logging to storage/logs/laravel.log
                                               // create and modify files in storage/app/
use App\Session;                                                // the si session model

use App\User;                                                // the User model
/*
Route::post('Shibboleth.sso/SAML2/POST' , function() {
	Log::info('HELLO WORLD');
    	$request = $_POST["SAMLResponse"];//phpinfo();//var_dump($_SERVER);
	return view('about', compact('request'));  
});
*/
/* Account Routes
---------------------------------*/
Route::get('Account', function(){
    return view('user.edit');
});

Route::post('Account','UserController@edit');

Route::get('About', function(){
        return Redirect::to('http://may1709.sd.ece.iastate.edu/uploads/finalReports/Si_AttendanceManagmentUserManual.pdf');  
	//return view('about');
});



/* Session Routes
---------------------------------*/
// Show all sessions
Route::get('Sessions', 'SessionsController@index')->middleware('auth');

// Show all sessions
Route::get('Sessions/load/{status}', function ($status) {

    $proctor = Auth::user()->email;
 
    return json_encode(Session::where('proctors', 'like', '%' . $proctor . '%')->where('status', $status)->get());
});

// Save created session
Route::post('Sessions', 'SessionsController@store')->middleware('auth');

// Collect Swipr data and pass to show page
Route::post('Sessions/swipr', 'SessionsController@swipr')->middleware('auth');

// Edit the given
Route::get('Sessions/{session_id}-{session_key}/Edit', 'SessionsController@edit')->where(['session_id' => '[0-9]{4}', 'session_key' => '[A-Z]{4}'])->middleware('auth');

// Check-in route used to check students in.
Route::post('Sessions/{session_id}-{session_key}', 'SessionsController@checkin')->middleware('auth');

// Process the given event
Route::post('Sessions/{session_id}-{session_key}/process' , 'SessionsController@process')->where(['session_id' => '[0-9]{4}', 'session_key' => '[A-Z]{4}'])->middleware('auth');

// Save edited session to the database
Route::post('Sessions/{session_id}-{session_key}/Update', 'SessionsController@update')->where(['session_id' => '[0-9]{4}', 'session_key' => '[A-Z]{4}'])->middleware('auth');

// Remove a session from the database
Route::post('Sessions/{session_id}-{session_key}/Delete', 'SessionsController@delete')->where(['session_id' => '[0-9]{4}', 'session_key' => '[A-Z]{4}'])->middleware('auth');

// Collect Swipr data and pass to show page
Route::post('Sessions/swipr', 'SessionsController@swipr')->middleware('auth');

// Edit the given
Route::get('Sessions/{session_id}-{session_key}/Edit', 'SessionsController@edit')->where(['session_id' => '[0-9]{4}', 'session_key' => '[A-Z]{4}'])->middleware('auth');

// Check-in route used to check students in.
Route::post('Sessions/{session_id}-{session_key}', 'SessionsController@checkin')->middleware('auth');

// Process the given event
Route::post('Sessions/{session_id}-{session_key}/process' , 'SessionsController@process')->where(['session_id' => '[0-9]{4}', 'session_key' => '[A-Z]{4}'])->middleware('auth');

// Save edited session to the database
Route::post('Sessions/{session_id}-{session_key}/Update', 'SessionsController@update')->where(['session_id' => '[0-9]{4}', 'session_key' => '[A-Z]{4}'])->middleware('auth');

// Remove a session from the database
Route::post('Sessions/{session_id}-{session_key}/Delete', 'SessionsController@delete')->where(['session_id' => '[0-9]{4}', 'session_key' => '[A-Z]{4}'])->middleware('auth');


// Show detailed view of given session
Route::get('Sessions/{session_id}-{session_key}/{user_id?}', ['uses' => 'SessionsController@show', 'as' => 'Session.show'])->where(['session_id' => '[0-9]{4}', 'session_key' => '[A-Z]{4}'])->middleware('auth'); // restrict to the proper format 'ID-KEY'

// Show detailed view of given session
Route::get('Sessions/{session_id}-{session_key}/{user_id?}', ['uses' => 'SessionsController@show', 'as' => 'Session.show'])->where(['session_id' => '[0-9]{4}', 'session_key' => '[A-Z]{4}'])->middleware('auth'); // restrict to the proper format 'ID-KEY'

Route::post('shibLogout',['as'=> 'shibLogout', function() {
    Auth::logout();
    return Redirect::to('https://siattendance.ece.iastate.edu/Shibboleth.sso/Logout');  
}]);

Route::get('/', function() {
    
    if (!Auth::guest()){
        return redirect('/Sessions');
    }
	// shib logic
	$shib_user = $_SERVER['uid'];	
	Log::info($shib_user);
	// first check if user exists in database
	$laravelUser = User::where('email', $shib_user . '@iastate.edu')->first();
	if (count($laravelUser) == 1) {
		// log them in if they exist
		Auth::login($laravelUser);
		return redirect('/Sessions');
	} 
	// if the user does not exist in the database, register them		
	else {
		$user = new App\User();
		$user->password = Hash::make($shib_user . "@iastate.edu");
		$user->email = $shib_user . '@iastate.edu';
		$user->class = "Change Me in 'edit account'";
		$user->name = $shib_user;
		$user->save();

		Auth::login($user);
                return redirect('/Sessions');	     
	}
		
});

Route::get('/broadcast' , function () {
    broadcast(new App\Events\AttendanceUpdated(72));
    return view('welcome');
});

Auth::routes();



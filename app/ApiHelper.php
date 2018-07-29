<?php

namespace App;

// Illuminate Requirements
use Illuminate\Database\Eloquent\Model;

// External Dependencies
use GuzzleHttp\Client;

// Framework Dependencies
use DB;
use Auth;


class ApiHelper
{

     public static function createSession($title, $start_date){
        // Create Session in atrack
        $client = new Client();
        
        // Store response to be used for putting in our database
        $response = $client->post('https://atrack.its.iastate.edu/api/create', ['json' => 
            [
                'title' => $title,
                'apiKey' => config('services.api_key'),
                'startDate' => ['date' => $start_date],
                'open' => false,
                'proctors' => [
                    Auth::user()->email,
                ]
            ] 
        ]);
        return json_decode($response->getBody(), true);
    }

    public static function getAttendanceList($session_id, $session_key){
        $client = new Client();

        // get attendance data from atrack
        $url = 'https://atrack.its.iastate.edu/api/attendance/id/'.$session_id.'/key/'.$session_key.'/apikey/'.config('services.api_key');
        $response = $client->get($url)->getBody();
	
	// parse and keep uniques
	$attendance = json_decode($response, true);
	$attendance_list = array_unique( $attendance, SORT_REGULAR ); //  This does not work. PHP array_unique can't handle multi-dimensional
        
	return $attendance_list;
    }

    public static function getAccessToken(){
        // grab the current refresh token to get a new access token
        return DB::connection('token')->table('token')->select()->first()->access;
		
    }

    public static function updateAccessToken(){
        // grab the current refresh token to get a new access token
        $refresh = DB::connection('token')->table('token')->select()->first()->refresh;
		
        /* The Orig way */
        $client = new Client(['headers' => [ 'Content-Type' => 'application/x-www-form-urlencoded' ]]);

        // Store response to be used for putting in our database
        $response = $client->post('https://api.box.com/oauth2/token', 
        ['form_params' =>[
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh,
            'client_id' => config('services.client_id'),
            'client_secret' => config('services.client_secret')
        ]]);
		
        $tokens = json_decode($response->getBody(), TRUE);
        if(array_key_exists('access_token', $tokens)){
		$access_token = $tokens['access_token'];
        $refresh_token = $tokens['refresh_token'];
		}else{
			Log::info('Box token access failed');
			return false;
		}

        DB::connection('token')->table('token')->where('refresh', $refresh)->update(['refresh' => $refresh_token,'access' => $access_token]);
		return true;
    }



}

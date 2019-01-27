<?php

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/test', function (Request $request) {
	// Reads the variables sent via POST from our gateway
	$sessionId   = $request->sessionId;
	$serviceCode = $request->serviceCode;
	$phoneNumber = $request->phoneNumber;
	$text        = $request->text;
    $level       = explode("*", $text);

    if ($text == "") {
    	if (\Helahub::chkUser($phoneNumber)) {

		    $response  = "CON Welcome to Helahub \n";
		    $response .= "1. My Account \n";
		    $response .= "2. My phone number";

		} else {

		    $response  = "CON Welcome to Helahub, Please Register to continue. \n";
		    $response .= "3. Register \n";
		    $response .= "0. Exit";

		}
        // $response="CON Welcome to the registration portal.\nPlease enter you full name";
    } else if ($text == "3") {
	    $response = "CON Welcome to the registration portal.\nPlease enter you full names";
	} else if ($text == "0") {
	    $response = "END Goodbye";
	}

    if(isset($level[1]) && $level[1]!="" && !isset($level[1])){
      $response="CON Hi ".$level[1].", enter your ward name";
         
    }
    else if(isset($level[1]) && $level[1]!="" && !isset($level[2])){
            $response="CON Please enter you national ID number\n"; 
    }
    else if(isset($level[2]) && $level[2]!="" && !isset($level[3])){
        //Save data to database
        $data=array(
            'phoneNumber'=> $phoneNumber,
            'full_names' => $level[1],
            'electoral_ward' => $level[1],
            'national_id'=> $level[2]
        );

        Log::info("b2c callback");
        Log::info(print_r($data, true));
        
        $response="END Thank you ".$level[1]." for registering.\nWe will keep you updated"; 
    }
    header('Content-type: text/plain');
    echo $response;
});

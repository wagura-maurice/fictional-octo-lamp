<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class USSDController extends Controller
{

	public function ussd(Request $request) {
// Echo the response back to the API
		header('Content-type: text/plain');
		$sessionId   = $request->sessionId;
		$serviceCode = $request->serviceCode;
		$phoneNumber = $request->phoneNumber;
		$text        = $request->text;
		$level       = explode("*", $text);

		if (\Helahub::chkUser($phoneNumber)) {
			$response  = "CON Welcome to Helahub \n";
			$response .= "1. My Account \n";
			$response .= "2. My phone number";

		} else {
			$response = $this->_unauthenticated($text, $level);
		}
//  $response = $this->_unauthenticated($text);

		echo $response;
	}


	public function _unauthenticated($text, $level){
		$screenoutput ="END Thank you for registering"; //ending message/none is found
		
		if ($text == "") {
			$screenoutput  = "CON Welcome to Helahub, Please Register to continue. \n";
			$screenoutput .= "3. Register \n";
			$screenoutput .= "0. Exit";
		}

		switch ($text) {
			case "3":{
				# code...
		    	$screenoutput = "CON Welcome to the registration portal.\nPlease enter you full names";
				break;	
			}

			case "0":{
				# code...
		    	$screenoutput = "END Goodbye";
				break;	
			}
			
		}

		 if(isset($level[1]) && $level[1]!="" && !isset($level[2])){
	       $screenoutput="CON Please enter you national ID number\n"; 

	    }else if(isset($level[2]) && $level[2]!="" && !isset($level[3])){
	       $screenoutput="CON Please enter you national coupon\n"; 
	    }
		  
		$data = [
				"full_name"=>isset($level[1])?$level[1]:'',
				"id_no"=>isset($level[2])?$level[2]:'',
				"coupon"=>isset($level[3])?$level[3]:'',
		];
		
		if(isset($level[3])){
			 \Log::info(print_r($data, true));
		}
		
		return $screenoutput;
	}

	public function _authenticated($text, $level){
	
	}

}

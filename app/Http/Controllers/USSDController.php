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

		if (!\Helahub::chkUser($phoneNumber)) {
			$response = $this->_authenticated($text, $level, $phoneNumber);
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

	public function _authenticated($text, $level, $phoneNumber=null){
		$checker = true;	//check if merchant or client
		return ($checker)?$this->_loaduthenticatedMerchantMenu($text, $level):$this->_loaduthenticatedClientMenu($text, $level);/// ="END Thank you for accessing"; //ending message/none is fou
	}

	public function _loaduthenticatedMerchantMenu($text, $level){
		$screenoutput ="END Thankyou for accessing"; //ending mesor accesssage/none is found		
		if ($text == "") {
			$screenoutput  = "CON Welcome to Helahub Merchant \n";
			$screenoutput .= "1. Check Balance \n";
			$screenoutput .= "2. Move Money \n";
			$screenoutput .= "3. Load Money \n";
			$screenoutput .= "4. Pay Merchant \n";
			$screenoutput .= "5. Transaction Statements";
		}

		switch ($text) {
			case "1":{
				# code...
		    	$screenoutput = "CON Enter your Password";
				break;	
			}

			case "0":{
				# code...
		    	$screenoutput = "END Goodbye";
				break;	
			}
			
		}

		if(isset($level[1]) && $level[1]!="" && !isset($level[2])){
			$screenoutput = $this->authenticatePassword(); 
		 }
		return $screenoutput;
	}


	public function authenticatePassword(){
		$passwordcorect = true;
		return ($passwordcorect)?"END You have 1250KES \n":"CON Wow Incorrect password \n";
	}



	public function _loaduthenticatedClientMenu($text, $level){
		$screenoutput ="END Thank you for accessing"; //ending message/none is found		
		if ($text == "") {
			$screenoutput  = "CON Welcome to Helahub Client, . \n";
			$screenoutput .= "1. Check Balance \n";
			$screenoutput .= "2. Load Money \n";
			$screenoutput .= "3. Pay Merchant \n";
			$screenoutput .= "4. Transaction Statements";
		}
		return $screenoutput;
	}
}

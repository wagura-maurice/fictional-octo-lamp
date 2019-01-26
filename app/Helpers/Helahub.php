<?php

namespace App\Helpers;

class Helahub {

	public static function phone_suffix($phone) {
        return "254" . substr($phone, -9);
    }

    public static function chkUser($phone) {
    	return \App\User::where(['username' => $phone])->exists();
    }

    public static function getUser($phone) {
    	$user = \App\User::where(['username' => $this->phone_suffix($phone)])->first();
        return json_encode($user);
    }
}
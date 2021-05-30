<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;

class TokenController extends Controller
{
	protected $auth;
    function __construct(FirebaseAuth $auth)
    {

        $arrayServiceAccount = array(
            "type"=>"service_account",
            
            "project_id" => env('FIREBASE_PROYECT_ID'),
            "client_id" => env('FIREBASE_CLIENT_ID'),
            "client_email" => env('FIREBASE_CLIENT_EMAIL'),
            "private_key" => env('FIREBASE_PRIVATE_KEY')
        );

        $firebase = (new Factory)->withServiceAccount($arrayServiceAccount);
        $this->auth = $firebase->createAuth();

    	//$this->auth = $auth;
    }

    public function validarTokenUser(string $token)
    {
    	$AuthorizationToken=$token;
    	try{
    		$verifiedIdToken = $this->auth->verifyIdToken($AuthorizationToken);
    	}catch (InvalidToken $e) {
            return "Unauthenticated";
        }
         $uid = $verifiedIdToken->claims()->get('sub');
         $exp = $verifiedIdToken->claims()->get('exp');

        if($uid){
                return $uid;
        }
        return 'Unauthenticated';
    }

}

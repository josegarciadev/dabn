<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\ServiceAccount;

class TokenController extends Controller
{
	protected $auth;
    function __construct(FirebaseAuth $auth)
    {
    	$this->auth = $auth;
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

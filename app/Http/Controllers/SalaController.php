<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TokenController;
use App\Models\Sala;
use Illuminate\Http\Request;

class SalaController extends Controller
{
	private $Autenticartoken;
	function __construct(TokenController $Autenticartoken)
	{
		$this->Autenticartoken = $Autenticartoken;
	}

    public function ListaSalas(Request $request){
    	$uid= $this->Autenticartoken->validarTokenUser($request->header('Authorization'));
    	//return $uid;
    	if ($uid == "Unauthenticated") {
    		return \Response::json('Operacion no valida',422);
    	}

    	return Sala::get();
    }
}

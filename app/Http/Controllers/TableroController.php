<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TokenController;
use App\Models\Perfil_Publico;
use App\Models\Tablero;
use Illuminate\Http\Request;

class TableroController extends Controller
{
	private $Autenticartoken;

	function __construct(TokenController $Autenticartoken)
	{
		$this->Autenticartoken = $Autenticartoken;
	}

    public function CrearTablero(Request $request){
    	$uid=$this->Autenticartoken->validarTokenUser($request->header('Authorization'));
    	if ($uid == "Unauthenticated") {
    		return \Response::json('Operacion no valida',422);
    	}
    	$rules=[
    		'tablero'=>'required',
    		'sala_id'=>'required',
    		'x'=>'required',
    		'y'=>'required',
    	];
    	$users = Perfil_Publico::where('uid', $uid)->get();
    	$tablero= new Tablero;
    	$tablero->tablero=$request->input('tablero');
    	$tablero->sala_id=$request->input('sala_id');
    	$tablero->j1=$users[0]->id;
    	$tablero->x_tablero=$request->input('x');
    	$tablero->y_tablero=$request->input('y');
        $tablero->j2puntos=0;
        $tablero->j1puntos=0;
        $tablero->estado=3;
        // estado 3 es igual a Espera
    	$tablero->save();
    	return $tablero;
    }
}

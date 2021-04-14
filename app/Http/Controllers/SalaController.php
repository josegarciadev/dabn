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
     /**
    * @OA\Get(

    *     path="/api/ListaSalas",
    *     summary="Listar las Salas",
    *     tags={"Sala"},
     *      operationId="getSala",
     *   @OA\Parameter(
     *      name="Authorization",
     *      in="header",
     *      description="Token de Firebase",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todas las salas."
    *     ),
    *     @OA\Response(
    *         response="404",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function ListaSalas(Request $request){
    	$uid= $this->Autenticartoken->validarTokenUser($request->header('Authorization'));
    	//return $uid;
    	if ($uid == "Unauthenticated") {
    		return \Response::json('Operacion no valida',401);
    	}
        $salas=Sala::get();
        if ($salas->isEmpty()) {
            return \Response::json('ha ocurrido un error',404);
        }
    	return Sala::get();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TokenController;
use App\Models\Historial_Tablero;
use App\Models\Perfil_Publico;
use App\Models\Sala;
use App\Models\Tablero;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TableroController extends Controller
{

	private $Autenticartoken;

	function __construct(TokenController $Autenticartoken)
	{
		$this->Autenticartoken = $Autenticartoken;
	}

    /**
     * @OA\Post(path="/api/CrearTablero",
     *   tags={"Tablero"},
     *   summary="Crear tablero",
     *   description="",
     *   @OA\Parameter(
     *      name="Authorization",
     *      in="header",
     *      description="Token de Firebase",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="sala_id",
    *      description="Id de la sala",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="x",
     *      description="Movimiento  X",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="y",
     *      description="Movimiento  Y",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   operationId="CrearTablero",
     *   @OA\Response(response="201", description="Ok, Juegos Disponibles, Juegos Usados"),
     *  @OA\Response(response="401", description="Unauthenticated"),
     *  @OA\Response(response="422", description="Sala no existe"),
     * )
     */
    public function CrearTablero(Request $request){
    	$uid=$this->Autenticartoken->validarTokenUser($request->header('Authorization'));

    	if ($uid == "Unauthenticated") {
    		return \Response::json('Operacion no valida',401);
    	}

        //return $request->input('sala_id');
        $sala =Sala::find($request->input('sala_id'));
        if (!$sala) {
            return \Response::json(['resultado'=>'Sala encontrada'],422);
        }

    	$user = TableroController::getUsuario($uid);

    	$tablero= new Tablero;
        for ($i=0; $i<$request->input('x') ; $i++) {

            for ($j=0; $j < $request->input('y') ; $j++) {
                $tableroLogico[$i][$j]=[
                    'x'=>$i,
                    'y'=>$j
                ];
            }
        }

    	$tablero->tablero=json_encode($tableroLogico);
    	$tablero->sala_id=$request->input('sala_id');
    	$tablero->j1=$user[0]->id;
        $tablero->movimientos=0;
        $tablero->tablero=0;
    	$tablero->x_tablero=$request->input('x');
    	$tablero->y_tablero=$request->input('y');
        $tablero->j2puntos=0;
        $tablero->j1puntos=0;
        $tablero->estado=3;
        $tablero->duenio=$user[0]->id;

    	$tablero->save();
        $tablerosDisponibles=Tablero::where('estado',3)
                                    ->where('duenio',$user[0]->id)
                                    ->get()
                                    ->count();
        $tablerosUsados=Tablero::where('estado',4)
                                    ->where('duenio',$user[0]->id)
                                    ->get()
                                    ->count();

    	return \Response::json(['Resultado'=>'Ok','tablerosDisponibles'=>$tablerosDisponibles,"tablerosUsados"=>$tablerosUsados],201);
    }
     /**
    * @OA\Get(

    *     path="/api/ListaTablerosPorSala/{id}",
    *     summary="Listar tableros por sala",
    *     tags={"Tablero"},
     *      operationId="getListaTablerosPorSala",
     *   @OA\Parameter(
     *      name="Authorization",
     *      in="header",
     *      description="Token de Firebase",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID de la sala",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todas las tablas por sala_id."
    *     ),
     *     @OA\Response(
    *         response="401",
    *         description="Unauthenticated"
    *     ),
    *     @OA\Response(
    *         response="422",
    *         description="No hay tableros con este ID"
    *     ),
    * )
    */

    public function ListaTablerosPorSala($id,Request $request){
        $uid=$this->Autenticartoken->validarTokenUser($request->header('Authorization'));
        if ($uid == "Unauthenticated") {
            return \Response::json('Operacion no valida',401);
        }
       $user = TableroController::getUsuario($uid);

        $tableros= Tablero::latest('id')
                            ->where('sala_id',$id)
                            ->where('j1','!=',$user[0]->id)
                            ->get();

        if ($tableros->isEmpty()) {
            return \Response::json(['resultado'=>'No hay tableros con este ID'],422);
        }
       foreach ($tableros as $tablero => $value) {
        $resultado[$tablero]=[
            'id'=>$value->id,
            'Tamanio'=>$value->x_tablero.'x'.$value->y_tablero,
            'fechaCreacion'=>$value->created_at,
            'totalJugadas'=>$value->movimientos,
            'estado'=>$value->estado,
            'idJugador'=>$value->duenio
        ];

       }
        return $resultado;
    }

     /**
     * @OA\Post(path="/api/CrearJuego",
     *   tags={"Tablero"},
     *   summary="Crear Juego",
     *   description="El Jugador 1 no puede ser Igual al jugador 2, enviar solo idTableros de otros jugadores",
     *   @OA\Parameter(
     *      name="Authorization",
     *      in="header",
     *      description="Token de Firebase",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="IdTablero",
    *      description="Id de la sala",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   operationId="crearJuego",
     *   @OA\Response(response="200", description="Ok, Juegos Disponibles, Juegos Usados"),
     *  @OA\Response(response="401", description="Unauthenticated"),
     *  @OA\Response(response="404", description="Error j1 y j2 son el mismo jugador"),
    *  @OA\Response(response="422", description="Tablero no existe"),
     * )
     */
    public function CrearJuego(Request $request){
        $uid=$this->Autenticartoken->validarTokenUser($request->header('Authorization'));
        if ($uid == "Unauthenticated") {
            return \Response::json('Operacion no valida',401);
        }

        $tablero=Tablero::find($request->input('idTablero'));
        $user = TableroController::getUsuario($uid);
        if (!$tablero) {
            return \Response::json(['resultado'=>'Tablero no existe'],422);
        }

        if ($tablero->duenio == $user[0]->id) {
            return \Response::json(['resultado'=>'Error j1 y j2 son el mismo jugador'],404);
         }
        $tablero->j2=$user[0]->id;
        $tablero->j2puntos=0;
        $tablero->turno=$tablero->j1;
        $tablero->estado=1;
        $tablero->update();
        $tablerosDisponibles=Tablero::where('estado',3)
                                    ->where('duenio',$tablero->duenio)
                                    ->get()
                                    ->count();
        $tablerosActivos=Tablero::where('estado',1)
                                    ->where('duenio',$tablero->duenio)
                                    ->get()
                                    ->count();

        return \Response::json(['resultado'=>'Ok','juegosDisponibles'=>$tablerosDisponibles,'juegosActivos'=>$tablerosActivos],200);


    }

      /**
    * @OA\Get(
    *     path="/api/verJuego/{id}",
    *     summary="Ver Juego",
    *     tags={"Tablero"},
     *     operationId="getVerJuego",
     *   @OA\Parameter(
     *      name="Authorization",
     *      in="header",
     *      description="Token de Firebase",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID del tablero",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar el juego mediande el ID"
    *     ),
     *     @OA\Response(
    *         response="401",
    *         description="Unauthenticated"
    *     ),
    *     @OA\Response(
    *         response="404",
    *         description="Tablero no encontrado, Tablero en espera, necesita un tablero activo"
    *     ),
    *     @OA\Response(
    *         response="422",
    *         description="No hay tableros con este ID"
    *     ),
    * )
    */
    public function VerJuego($id,Request $request){
        $uid=$this->Autenticartoken->validarTokenUser($request->header('Authorization'));
        if ($uid == "Unauthenticated") {
            return \Response::json('Operacion no valida',401);
        }
        $tablero= Tablero::find($id);
        if (!$tablero) {
            return \Response::json(['resultado'=>'Tablero no encontrado'],404);
        }
        if ($tablero->estado==3) {
            return \Response::json(['resultado'=>'Tablero en espera, necesita un tablero activo'],404);
        }
        $tablero->tablero=json_decode($tablero->tablero,true);
        $verJuego=[
            'id'=>$tablero->id,
            'Tamanio'=>$tablero->x_tablero.'x'.$tablero->y_tablero,
            'ultimaActividad'=>$tablero->updated_at,
            'totalJugadas'=>$tablero->movimientos,
            'idJugador1'=>$tablero->j1,
            'idJugador2'=>$tablero->j2,
            'puntosJugador1'=>$tablero->j1puntos,
            'puntosJugador2'=>$tablero->j2puntos,
            'tablero'=>$tablero->tablero
        ];
        return $verJuego;
    }
     /**
     * @OA\Post(path="/api/Jugada",
     *   tags={"Tablero"},
     *   summary="Jugada",
     *   description="El Jugador 1 no puede ser Igual al jugador 2, enviar solo idTableros de otros jugadores",
     *   @OA\Parameter(
     *      name="Authorization",
     *      in="header",
     *      description="Token de Firebase",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="IdTablero",
    *      description="Id de la sala",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   operationId="Jugada",
     *   @OA\Response(response="200", description="Ok, Activo, jugadorIdTurno"),
     *  @OA\Response(response="401", description="Unauthenticated"),
     *  @OA\Response(response="404", description="Tablero No encontrado,bloqueado,Espera,Finalizado, La jugada X|Y no es valida, Este movimiento ya fue realizado"),
    *  @OA\Response(response="422", description="Tablero no existe"),
     * )
     */

    public function Jugada(Request $request){
        $uid=$this->Autenticartoken->validarTokenUser($request->header('Authorization'));
        if ($uid == "Unauthenticated") {
            return \Response::json('Operacion no valida',401);
        }
        $tablero= Tablero::find($request->input('id'));
        if (!$tablero) {
            return \Response::json(['resultado'=>'Tablero no encontrado'],422);
        }

        if ($tablero->estado==3) {
            return \Response::json(['resultado'=>'Tablero en espera, necesita un tablero activo'],404);
        }
        if ($tablero->estado==2) {
            return \Response::json(['resultado'=>'Tablero bloqueado'],404);
        }
        if ($tablero->estado==4) {
            return \Response::json(['resultado'=>'Tablero Finalizado'],404);
        }
        $user = TableroController::getUsuario($uid);
        if ($tablero->turno != $user[0]->id) {
            return \Response::json(['resultado'=>'No puedes mover, no es tu turno','jugadorIdTurno'=>$tablero->turno],404);
        }


        if ($tablero->x_tablero<$request->input('x')) {
            return \Response::json(['resultado'=>'La jugada x no es valida','x'=>$request->input('x')],404);
        }
        if ($tablero->y_tablero<$request->input('y')) {
            return \Response::json(['resultado'=>'La jugada y no es valida','y'=>$request->input('y')],404);
        }


        $x=$request->input('x')-1;
        $y=$request->input('y')-1;
        $historial=[
            'x'=>$x,
            'y'=>$y,
            'tablero'=>$tablero->id,
            'turno'=>$tablero->turno,
            'movimiento'=>$tablero->movimientos
        ];
        $comprobar_historial=Historial_Tablero::where('tablero',$tablero->id)
                                                ->where('x',$x)
                                                ->where('y',$y)
                                                ->get();
        if (!$comprobar_historial->isEmpty()) {
            return \Response::json(['resultado'=>'Este movimiento ya fue realizado'],404);
        }
        $tablero->estado=2;
        $tablero->movimientos=+1;
        $tablero->update();
        $historial_tablero=Historial_Tablero::create($historial);

        $tablero->estado=1;
         if ($tablero->turno==$tablero->j1) {
            $tablero->j1puntos=+1;
            $tablero->turno=$tablero->j2;
        }else{
            $tablero->j2puntos=+1;
            $tablero->turno=$tablero->j1;
        }
        $tablero->update();
        return \Response::json(['resultado'=>'ok','estado'=>'activo','jugadorIdTurno'=>$tablero->turno],200);
    }
    /**
    * @OA\Get(
    *     path="/api/PerfilJugador/{id}",
    *     summary="Perfil Jugador",
    *     tags={"Tablero"},
     *     operationId="getPerfilJugador",
     *   @OA\Parameter(
     *      name="Authorization",
     *      in="header",
     *      description="Token de Firebase",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID del jugador",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
    *     @OA\Response(
    *         response=200,
    *         description="Id, Partidas, Victorias, Puntos"
    *     ),
     *     @OA\Response(
    *         response="401",
    *         description="Unauthenticated"
    *     ),
    *     @OA\Response(
    *         response="404",
    *         description="Usuario no existe"
    *     ),
    * )
    */

    public function PerfilJugador($id,Request $request){
         $uid=$this->Autenticartoken->validarTokenUser($request->header('Authorization'));
        if ($uid == "Unauthenticated") {
            return \Response::json('Operacion no valida',401);
        }
        $victorias=Tablero::where('ganador',$id)->count();
        $user = TableroController::getUsuario($uid);
        if ($user->isEmpty()) {
            return \Response::json(['resultado'=>'Usuario no existe'],404);
        }
        $partidas=Tablero::where('j1',$id)
                            ->orWhere('j2',$id)
                            ->count();
        $puntos1=Tablero::where('j1',$id)
                            ->get()
                            ->sum('j1puntos');
        $puntos2=Tablero::where('j2',$id)
                            ->get()
                            ->sum('j2puntos');
        $puntosTotal=$puntos1+$puntos2;

        $perfil_user=[
            'id'=>$id,
            'partidas'=>$partidas,
            'victorias'=>$victorias,
            'puntos'=>$puntosTotal
        ];
        return $perfil_user;
    }
    /**
    * @OA\Get(
    *     path="/api/historialTableroId/{id}",
    *     summary="Historial Tablero",
    *     tags={"Tablero"},
     *     operationId="getHistorialTablero",
     *   @OA\Parameter(
     *      name="Authorization",
     *      in="header",
     *      description="Token de Firebase",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID del tablero",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
    *     @OA\Response(
    *         response=200,
    *         description="Historial"
    *     ),
     *     @OA\Response(
    *         response="401",
    *         description="Unauthenticated"
    *     ),
    *     @OA\Response(
    *         response="404",
    *         description="No hay historial para este tablero"
    *     ),
    * )
    */
     public function historialTableroID($id ,Request $request){
         $uid=$this->Autenticartoken->validarTokenUser($request->header('Authorization'));
        if ($uid == "Unauthenticated") {
            return \Response::json('Operacion no valida',401);
        }
        $historial=Historial_Tablero::where('tablero',$id)->get();

        if ($historial->isEmpty()) {
            return \Response::json(['resultado'=>'No hay historial para este tablero'],404);
        }
        return $historial;
     }

    public function getUsuario($uid){
        $user = Perfil_Publico::where('uid', $uid)->get();

        if ($user->isEmpty()) {
            $createdUser = new Perfil_Publico;
            $createdUser->uid = $uid;
            $createdUser->save();
            $user = TableroController::getUsuario($uid);
        }

        return $user;

    }

}

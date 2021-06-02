<?php

namespace App\Models;

use App\Models\Historial_Tablero;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tablero extends Model
{
	protected $table='tableros';
	protected $primaryKey = 'id';
    use HasFactory;
    protected $fillable=array('tablero','sala_id','turno','movimientos','j2','j2puntos',
    	'j1','j1puntos','estado','ganador','x_tablero','y_tablero','duenio');

    public function historial_tablero(){
    	return $this->belongsToMany(Historial_Tablero::class,'historial_tableros','tablero','id');
    }

    public static function Jugada($i,$j,$raya,$jugadorActualSkin,$_Puntos, $x, $y) {

        if($raya === 'LADO'){
            $_Puntos[$i][$j]->LadoEstaCompleto = $jugadorActualSkin;
        }else if($raya === 'ARRIBA'){
            $_Puntos[$i][$j]->ArribaEstaCompleto = $jugadorActualSkin;
        } 
        return $_Puntos;
    }

    public static function PuntoCompleto($i,$j,$jugadorActualSkin,$_Puntos, $x, $y) {
        //Funciona con fe falta validar los limites (x,y,x+1,y+1) 
        $_Puntos[$i][$j]->PuntoEstaCompleto = $jugadorActualSkin;
        return $_Puntos;
    }

    //TODO Actualizar para que funcione con 4 jugadores
    public static function CambiarTurno($turno,$j1,$j2){
        return $turno == $j1 ? $j2:$j1;
    }

    public static function VerificarFinDeJuego($_Puntos, $x, $y) {
        $p = new PuntoDatos;

        return $p->VerificarFinDeJuego($_Puntos, $x, $y);
    }

    public static function VerificarPuntoCompleto($i, $j,$_Puntos,$x, $y){
        $p = new PuntoDatos;

        return $p->VerificarPuntoCompleto($i, $j,$_Puntos,$x, $y);
    }

    public static function crearTableroMinimo($x,$y){
        $TableroMinimo = '';
        for ( $i = 0; $i < $x ; $i++)
        {
            for ( $j = 0; $j < $y ; $j++)
            {
                $TableroMinimo.='no,no,no|';
            }
        }
        return $TableroMinimo;
    }

    public static function transformarTableroLogicoAMinimo($_Puntos,$x,$y){
        $TableroMinimo = '';
        for ( $i = 0; $i < $x ; $i++)
        {
            for ( $j = 0; $j < $y ; $j++)
            {
                $TableroMinimo.=$_Puntos[$i][$j]->LadoEstaCompleto.','.$_Puntos[$i][$j]->ArribaEstaCompleto.','.$_Puntos[$i][$j]->PuntoEstaCompleto.'|';
            }
        }
        return $TableroMinimo;
    }

    public static function transformarTableroMinimoALogico($tableroMinimo,$x,$y){

       $puntosList = explode('|',$tableroMinimo);
       

       for ( $i = 0; $i < $x ; $i++)
        {
            for ( $j = 0; $j < $y ; $j++)
            {
                $punto =  explode(',',$puntosList[($i*$x)+$j]);
                $TableroLogico[$i][$j] = new PuntoDatos;
                $TableroLogico[$i][$j]->LadoEstaCompleto = $punto[0];
                $TableroLogico[$i][$j]->ArribaEstaCompleto = $punto[1];
                $TableroLogico[$i][$j]->PuntoEstaCompleto = $punto[2];
            }
        }
        return $TableroLogico;

    }


}

class PuntoDatos{
    public $LadoEstaCompleto = 'no';
    public $ArribaEstaCompleto = 'no';
    public $PuntoEstaCompleto = 'no';

    public function __construct ($Lado = false,$Arriba = false,$Punto = "no") {
        $this->LadoEstaCompleto = $Lado? "5000" : "no";
        $this->ArribaEstaCompleto = $Arriba ? "5000" : "no";
        $this->PuntoEstaCompleto = $Punto;
    }

    public function EstaArribaActivo($punto)
    {
        return $punto->ArribaEstaCompleto != 'no';
    }

    public function EstaLadoActivo($punto)
    {
        return $punto->LadoEstaCompleto != 'no';
    }

    public function EstaPuntoCompleto($punto)
    {
        return $punto->PuntoEstaCompleto != 'no';
    }

    public function VerificarPuntoCompleto($i, $j,$_Puntos,$x, $y)
    {
        if ($i == $x - 1 || $j == $y - 1)
            return false;

        return  $this->EstaLadoActivo($_Puntos[$i][$j])  &&
                $this->EstaArribaActivo($_Puntos[$i][$j]) &&
                $i < $x - 1 &&
                $this->EstaArribaActivo($_Puntos[$i + 1][$j]) &&
                $j < $y - 1 &&
                $this->EstaLadoActivo($_Puntos[$i][$j + 1]) ? true : false;

    }

    public function VerificarFinDeJuego($_Puntos, $x, $y) {

        for ( $i = 0; $i < $x - 1; $i++)
        {
            for ( $j = 0; $j < $y - 1; $j++)
            {
                if ($this->EstaPuntoCompleto( $_Puntos[$i][$j] ))
                {
                    return false;
                }
            }
        }
        return true;
    }


}

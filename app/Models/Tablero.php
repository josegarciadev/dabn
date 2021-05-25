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

    public function esFinDeJuego($tablero){

    }

    public function crearTableroMinimo($x,$y){
        $TableroMinimo = '';
        for ( $i = 0; $i < $x - 1; $i++)
        {
            for ( $j = 0; $j < $y - 1; $j++)
            {
                $TableroMinimo.='no,no,no|';
            }
        }
        return $TableroMinimo;
    }

    public function transformarTableroLogicoAMinimo($_Puntos,$x,$y){
        $TableroMinimo = '';
        for ( $i = 0; $i < $x - 1; $i++)
        {
            for ( $j = 0; $j < $y - 1; $j++)
            {
                $TableroMinimo.=$_Puntos[$i][$j]->LadoEstaCompleto.','.$_Puntos[$i][$j]->ArribaEstaCompleto.','.$_Puntos[$i][$j]->PuntoEstaCompleto.'|';
            }
        }

    }

    public function transformarTableroMiniALogico($tableroMinimo,$x,$y){

       $puntosList = explode('|',$tableroMinimo);
       

       for ( $i = 0; $i < $x - 1; $i++)
        {
            for ( $j = 0; $j < $y - 1; $j++)
            {
                $punto =  explode(',',$puntosList);
                $TableroLogico[$i][$j] = new PuntoDatos;
                $TableroLogico[$i][$j]->LadoEstaCompleto = $punto[0];
                $TableroLogico[$i][$j]->ArribaEstaCompleto = $punto[1];
                $TableroLogico[$i][$j]->PuntoEstaCompleto = $punto[2];
            }
        }

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

    public function EstaArribaActivo(PuntoDatos $punto)
    {
        return !($punto->ArribaEstaCompleto == 'no');
    }

    public function EstaLadoActivo(PuntoDatos $punto)
    {
        return !($punto->LadoEstaCompleto == 'no');
    }

    public function EstaPuntoCompleto(PuntoDatos $punto)
    {
        return !($punto->PuntoEstaCompleto == 'no');
    }

    public function VerificarPuntoCompleto($i, $j,$_Puntos,$x, $y)
    {
        if ($i == $x - 1 || $j == $y - 1)
            return false;

        return  EstaPuntoCompleto($_Puntos[$i][$j]) &&
                EstaLadoActivo($_Puntos[$i][$j])  &&
                EstaArribaActivo($_Puntos[$i][$j]) &&
                $i < $x - 1 &&
                EstaArribaActivo($_Puntos[$i + 1][$j]) &&
                $j < $y - 1 &&
                EstaLadoActivo($_Puntos[$i][$j + 1]) ? true : false;

                //return false;
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

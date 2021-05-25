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

    public function turno($tablero,$jugador1,$jugador2){

    }

    public function transformarTableroMinimoALogico($tableroMinimo){

    }

    public function transformarTableroMiniALogico($tableroLogico){

    }
}

class PuntoDatos{
    $LadoEstaCompleto = 'no';
    $ArribaEstaCompleto = 'no';
    $PuntoEstaCompleto = 'no';

    public __construct PuntoDatos($Lado = false,$Arriba = false,$Punto = "no") {
        $this->$LadoEstaCompleto = $Lado?"5000":"no";
        $this->$ArribaEstaCompleto = $Lado ? "5000" : "no";
        $this->$PuntoEstaCompleto = $Punto;
    }

    public function EstaArribaActivo()
    {

        return !($this->$ArribaEstaCompleto === 'no');
    }

    public function EstaLadoActivo()
    {
        return !($this->LadoEstaCompleto === 'no');
    }

    public function EstaPuntoCompleto()
    {
        return $this->PuntoEstaCompleto;
    }

    public function VerificarPuntoCompleto($i, $j, $_Puntos,$x, $y)
    {
        // Debug.Log("verifico "+i+j + " "+x+y);
        if (i == x - 1 || j == y - 1)
            return false;

        return _Puntos[i, j].EstaPuntoCompleto().Equals("no") &&
                           _Puntos[i, j].EstaLadoActivo() &&
                           _Puntos[i, j].EstaArribaActivo() &&
                           i < x - 1 &&
                           _Puntos[i + 1, j].EstaArribaActivo() &&
                           j < y - 1 &&
                           _Puntos[i, j + 1].EstaLadoActivo() ? true : false;
    }

    public function VerificarFinDeJuego(PuntoDatos[,] _Puntos, int x, int y) {

        for (int i = 0; i < x - 1; i++)
        {
            for (int j = 0; j < y - 1; j++)
            {
                if (_Puntos[i, j].EstaPuntoCompleto().Equals("no"))
                {
                    return false;
                }
            }
        }
        return true;
    }
}

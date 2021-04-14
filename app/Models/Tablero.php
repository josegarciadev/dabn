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
}

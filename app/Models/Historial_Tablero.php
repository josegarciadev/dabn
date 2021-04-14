<?php

namespace App\Models;

use App\Models\Tablero;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial_Tablero extends Model
{
	protected $table='historial_tableros';
	protected $primaryKey='id';
    use HasFactory;
    protected $fillable=array('x','y','tablero','turno','movimiento','created_at','updated_at');

    public function tablero(){
    	return $this->belongsToMany(Tablero::class,'historial_tableros','x','y','tablero','turno','movimiento','created_at','updated_at');
    }

}

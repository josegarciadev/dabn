<?php

namespace App\Models;

use App\Models\Perfil_Publico;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amigo extends Model
{
    protected $table='amigos';
	protected $primaryKey = 'id';
	protected $fillable=array('amigo','duenio');

	use HasFactory;

	public function perfil_publico(){
    	return $this->belongsToMany(Perfil_Publico::class);
    }
}

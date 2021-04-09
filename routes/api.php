<?php

use App\Http\Controllers\PruebaController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\TableroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::get('/ListaSalas', [SalaController::class,'ListaSalas']);
Route::post('/CrearTablero',[TableroController::class,'CrearTablero']);
Route::get('/ListaTablerosPorSala/{id}',[TableroController::class,'ListaTablerosPorSala']);

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerfilPublicoController extends Controller
{
    public function getUsuario($uid){
        $user = Perfil_Publico::where('uid', $uid)->get();

        if ($user->isEmpty()) {
            $createdUser = new Perfil_Publico;
            $createdUser->uid = $uid;
            $createdUser->save();
            $user = PerfilPublicoController::getUsuario($uid);
        }

        return $user;

    }
}

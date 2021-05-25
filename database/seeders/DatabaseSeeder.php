<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    $TablaEstado = 'estado';
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::table($TablaEstado)->insert([
            'id' => 1,
            'nombre' => 'ACTIVO'
        ]);

        DB::table($TablaEstado)->insert([
            'id' => 2,
            'nombre' => 'BLOQUEADO'
        ]);

        DB::table($TablaEstado)->insert([
            'id' => 3,
            'nombre' => 'ESPERA'
        ]);

        DB::table($TablaEstado)->insert([
            'id' => 4,
            'nombre' => 'FINALIZADO'
        ]);
    }
}

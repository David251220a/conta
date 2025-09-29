<?php

namespace Database\Seeders;

use App\Models\Persona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Persona::create([
            'documento' => 0,
            'nombre' => 'SIN NOMBRE',
            'apellido' => ' ',
            'fecha_nacimiento' => null,
            'tipo_persona_id' => 1,
            'sexo_id' => 0,
            'estado_civil' => 0,
            'email' => 'noreply@gmail.com',
            'celular' => '0',
            'ruc' => '',
            'estado_id' => 1,
            'user_id' => 1,
            'usuario_modificacion' => 1,
        ]);

        Persona::create([
            'documento' => 4918642,
            'nombre' => 'David',
            'apellido' => 'Ortiz',
            'fecha_nacimiento' => null,
            'tipo_persona_id' => 1,
            'sexo_id' => 0,
            'estado_civil' => 0,
            'email' => 'davidortiz25122010gmail.com',
            'celular' => '0',
            'ruc' => '',
            'estado_id' => 1,
            'user_id' => 1,
            'usuario_modificacion' => 1,
        ]);
        
    }
}

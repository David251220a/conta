<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Ciudad;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Estado;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);

        User::firstOrCreate([
            'name' => 'Admin',
            'lastname' => 'Admin',
            'documento' => '4918642',
            'email' => 'admin@dev',
            'password' => Hash::make('admin123456'),
        ])->assignRole('admin');

        $estado = ['ACTIVO', 'INACTIVO'];

        foreach ($estado as $item) {
            Estado::firstOrCreate([
                'descripcion' => $item
            ]);
        }

        Departamento::create([
            'descripcion' => 'CAPITAL'
        ]);

        Distrito::create([
            'departamento_id' => 1,
            'descripcion' => 'ASUNCION (DISTRITO)'
        ]);

        Ciudad::create([
            'distrito_id' => 1,
            'descripcion' => 'ASUNCION (DISTRITO)'
        ]);

        $this->call([
            TipoTransaccionSeeder::class,
            EntidadSeeder::class,
            ActividadEconomicaSeeder::class,
            FormaCobroSeeder::class,
            BancoSeeder::class,
            TipoDocumentoSeeder::class,
            EstablecimientoSeeder::class,
            NumeracionSeeder::class,
            TipoPersonaSeeder::class,
            PersonaSeeder::class,
        ]);
    }
}

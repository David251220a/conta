<?php

namespace Database\Seeders;

use App\Models\Entidad;
use App\Models\Timbrado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Entidad::create([
            'razon_social' => 'Caja de Jubilaciones y Pensiones del Personal Municipal',
            'nombra_fantasia' => 'Caja de Jubilaciones y Pensiones del Personal Municipal',
            'ruc' => '80000492-2',
            'ruc_sin_digito' => '80000492',
            'digito_verificador' => 2,
            'tipo_contribuyente' => 2,
            'tipo_regimen' => null,
            'email' => 'cajamunicipal.presidencia@gmail.com',
            'tipo_transaccion_id' => 2,
            'ambiente' => 0,
            'departamento_id' => 1,
            'distrito_id' => 1,
            'telefono' => '021497189',
            'direccion' => 'Benjamin Constant 955 c/ Colon y Montevideo',
            'ciudad_id' => 1,
            'codigo_set_id' => '003',
            'codigo_cliente_set' => 'B326123F3fd345C3a60F333B2025Ee9E',
            'firma' => 'app/keys/firma.p12',
            'pass_firma' => 'LqO#9j0E',
        ]);

        Timbrado::create([
            'entidad_id' => 1,
            'timbrado' => '18043139',
            'fecha_inicio' => '2025-05-21',
            'estado_id' => 1,
        ]);

    }
}

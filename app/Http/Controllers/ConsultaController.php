<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    public function facturas()
    {
        return view('consulta.factura');
    }
}

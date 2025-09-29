<?php

namespace App\Http\Controllers;

use App\Models\Entidad;
use App\Models\Factura;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function create()
    {
        return view('factura.create');
    }

    public function show(Factura $factura)
    {
        $entidad = Entidad::find(1);
        return view('factura.show', compact('factura', 'entidad'));
    }
}

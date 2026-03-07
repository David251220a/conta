<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use Illuminate\Http\Request;

class EstablecimientoController extends Controller
{
    public function index()
    {
        $data = Establecimiento::where('entidad_id', 1)->get();
        return view('establecimiento.index', compact('data'));
    }

    public function create()
    {
        return view('establecimiento.create');
    }
}

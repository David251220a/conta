<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $anulado = Factura::where('estado_id',1)->rechazadas()->count();

        $aprobadasMes = Factura::where('estado_id', 1)
        ->whereBetween('fecha_factura', [now()->startOfMonth(), now()->endOfMonth()])
        ->whereHas('sifen', function ($q) {
            $q->where('sifen_estado', 'APROBADO');
        })
        ->count();

        $data = Factura::where('estado_id', 1)
        ->whereBetween('fecha_factura', [now()->startOfMonth(), now()->endOfMonth()])
        ->whereHas('sifen', function ($q) {
            $q->where('sifen_estado', 'APROBADO');
        })
        ->limit(100)
        ->orderBy('id', 'DESC')
        ->get();

        return view('home', compact('anulado', 'aprobadasMes', 'data'));
    }
}

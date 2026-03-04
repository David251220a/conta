<?php

namespace App\Http\Livewire\Consulta;

use App\Models\Factura;
use App\Models\Sifen;
use App\Services\FacturaJsonBuilder;
use App\Services\FacturaXMLBuilder;
use App\Services\SifenServices;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FacturaIndex extends Component
{
    protected SifenServices $sifen;
    protected FacturaJsonBuilder $jsonBuilder;
    protected FacturaXMLBuilder  $xmlBuilder;

    public function boot(SifenServices $sifen, FacturaJsonBuilder $jsonBuilder, FacturaXMLBuilder $xmlBuilder) 
    { 
        $this->sifen = $sifen; 
        $this->jsonBuilder = $jsonBuilder;
        $this->xmlBuilder  = $xmlBuilder;
    }

    protected $listeners = ['anular_factura'];
    
    public function mount()
    {
    }

    public function render()
    {
        $data = Factura::with('sifen')
        ->where('estado_id', 1)
        ->where('condicion_pago', 1)
        ->whereHas('sifen', function ($q) {
            $q->where('sifen_estado', 'APROBADO');
        })
        ->latest('id')
        ->limit(500)
        ->get();

        return view('livewire.consulta.factura-index', compact('data'));
    }


    public function anular_factura($id)
    {
        try {
            DB::transaction(function () use ($id) { 
                $factura = Factura::find($id);
                $factura->update(['estado_id' => 2]);
            });
            $this->emit('mensaje_exitoso', 'Factura Anulado con exito.');
        }catch (\Throwable $e) {
            $this->emit('mensaje_error', $e->getMessage());
        };
       
    }

    public function consultarPendientes()
    {
        $datos = Sifen::where('sifen_estado', 'PENDIENTE')
        ->get();

        foreach ($datos as $item) {
            $respuesta = $this->sifen->consultar_cdc($item);
            $item->update([
                'sifen_estado' => strtoupper($respuesta['estado']),
                'sifen_envio_codrespuesta' => $respuesta['codigo'],
                'sifen_envio_msjrespuesta' => $respuesta['mensaje'],
                'sifen_respuesta_consulta_xml' => $respuesta['raw'],
            ]);
        }
    }
}

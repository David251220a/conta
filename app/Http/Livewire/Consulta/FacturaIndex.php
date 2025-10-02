<?php

namespace App\Http\Livewire\Consulta;

use App\Models\Sifen;
use App\Services\FacturaJsonBuilder;
use App\Services\FacturaXMLBuilder;
use App\Services\SifenServices;
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
    
    public function mount()
    {
    }

    public function render()
    {
        $data = Sifen::where('sifen_estado', '<>', 'APROBADO')
        ->latest('id')
        ->limit(1000)
        ->get();

        return view('livewire.consulta.factura-index', compact('data'));
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

<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Secuencia;
use App\Models\Sifen;
use App\Services\FacturaJsonBuilder;
use App\Services\FacturaXMLBuilder;
use App\Services\SifenServices;
use Illuminate\Http\Request;

class SifenController extends Controller
{
    public $sifen;

    public function __construct()
    {
        $this->sifen = new SifenServices();
    }

    public function enviar_sifen(Factura $factura)
    {
        $sifen = Sifen::where('factura_id', $factura->id)
        ->first();
        
        if (!($sifen)){
            $builder = new FacturaJsonBuilder($factura);
            $xml = new FacturaXMLBuilder();
            $json = [];
            if($factura->tipo_documento_id == 1){
                $json = $builder->jsonContado();
            }
            $documento =  $xml->generate($json, $factura->timbrado_id);
            $secuencia = Secuencia::find(1);
            $nro_secuencia = $secuencia->secuencia;

            $sifen = Sifen::create([
                'factura_id' => $factura->id,
                'cdc' => $documento['cdc'],
                'tipo_doc' => $factura->tipo_documento_id,
                'documento_xml' => $documento['archivo_xml'],
                'documento_pdf' => 'facturas/' . $documento['cdc'] .'.pdf',
                'documento_zip' => null,
                'zipeado' => 'N',
                'secuencia' => $nro_secuencia,
                'sifen_num_transaccion' => 0,
                'sifen_estado' => 'PENDIENTE',
                'sifen_mensaje' => ' ',
                'fecha_firma' => $documento['fecha_firma'],
                'link_qr' => $documento['link_qr'],
                'evento' => null,
                'sifen_cod' => 0,
                'tipo_transaccion' => $factura->tipo_transaccion_id,
                'condicion_pago' => $factura->condicion_pago,
                'moneda' => 'PYG',
                'correo_enviado' => 'N',
                'enviado_sifen' => 'N',
                'sifen_respuesta_consulta_xml' => '',
            ]);
            $secuencia->secuencia = $secuencia->secuencia + 1;
            $secuencia->update();
        }else {
            $builder = new FacturaJsonBuilder($factura);
            $xml = new FacturaXMLBuilder();
            $json = [];
            if($factura->tipo_documento_id == 1){
                $json = $builder->jsonContado();
            }
            $documento =  $xml->generate($json, $factura->timbrado_id);
            $sifen->update([
                'cdc' => $documento['cdc'],
                'documento_xml' => $documento['archivo_xml'],
                'documento_pdf' => 'facturas/' . $documento['cdc'] .'.pdf',
                'documento_zip' => null,
                'zipeado' => 'N',
                'sifen_num_transaccion' => 0,
                'sifen_estado' => 'PENDIENTE',
                'sifen_mensaje' => ' ',
                'fecha_firma' => $documento['fecha_firma'],
                'link_qr' => $documento['link_qr'],
                'evento' => null,
                'sifen_cod' => 0,
            ]);

        }

        return $this->sifen->enviar_directo($sifen);

        return $factura;
    }

    public function reenviar_sifen(Sifen $sifen)
    {
        $factura = Factura::find($sifen->factura_id);
        $builder = new FacturaJsonBuilder($factura);
        $xml = new FacturaXMLBuilder();
        $json = [];
        if($factura->tipo_documento_id == 1){
            $json = $builder->jsonContado();
        }
        $documento =  $xml->generate($json, $factura->timbrado_id);
        $sifen->update([
            'cdc' => $documento['cdc'],
            'documento_xml' => $documento['archivo_xml'],
            'documento_pdf' => 'facturas/' . $documento['cdc'] .'.pdf',
            'documento_zip' => null,
            'zipeado' => 'N',
            'sifen_num_transaccion' => 0,
            'sifen_estado' => 'PENDIENTE',
            'sifen_mensaje' => '',
            'fecha_firma' => $documento['fecha_firma'],
            'link_qr' => $documento['link_qr'],
            'evento' => null,
            'sifen_cod' => 0,
        ]);
        return $this->sifen->enviar_directo($sifen);

        return redirect()->route('consulta.factura_pendiente')->with('message', 'Reenviado con exito.');
    }

}

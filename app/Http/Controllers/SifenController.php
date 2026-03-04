<?php

namespace App\Http\Controllers;

use App\Models\Entidad;
use App\Models\Establecimiento;
use App\Models\Factura;
use App\Models\FacturaCobro;
use App\Models\FacturaDetalle;
use App\Models\Persona;
use App\Models\Secuencia;
use App\Models\Sifen;
use App\Models\Timbrado;
use App\Models\User;
use App\Services\FacturaJsonBuilder;
use App\Services\FacturaXMLBuilder;
use App\Services\SifenServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
                'sifen_idprod' => 0
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

        $documento =  $xml->generate_gpt($json, $factura->timbrado_id);
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

        // return $this->sifen->enviar_zip($sifen);
        return $this->sifen->enviar_directo($sifen);

        return redirect()->route('consulta.factura_pendiente')->with('message', 'Reenviado con exito.');
    }


    public function consultar_cdc($cdc)
    {
        return $this->sifen->consultar_cdc_sin_modelo($cdc);
        return $cdc;
    }


    public function consulta(Request $request)
    {
        $fecha_desde = Carbon::now()->format('Y-m-d');
        $fecha_hasta = Carbon::now()->format('Y-m-d');

        if($request->fecha_desde){
            $fecha_desde = $request->fecha_desde;
        }

        if($request->fecha_hasta){
            $fecha_hasta = $request->fecha_hasta;
        }

        $data = Factura::whereBetween('fecha_factura', [$fecha_desde, $fecha_hasta])
        ->get();

        return view('consulta.fac', compact('fecha_desde', 'fecha_hasta', 'data'));
    }


    public function consultar_estado_lote(Sifen $sifen)
    {
        return $this->sifen->consultar($sifen);
        // return $cdc;
    }

    public function crear_token()
    {
        $user = User::find(1);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $token = $user->createToken(
            'token-empresa',
            ['sifen:crear']
        )->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    public function recepcion_sifen(Request $request)
    {
        $sifenString = $request->input('sifen_json');

        $sifen_json = json_decode($sifenString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'error' => 'JSON inválido',
                'detalle' => json_last_error_msg()
            ], 422);
        }

        if (!isset($sifen_json['cliente'])) {
            return response()->json(['error' => 'Cliente requerido'], 422);
        }

        if (!isset($sifen_json['factura_sucursal'])) {
            return response()->json(['error' => 'Falta el campo factura_sucursal'], 422);
        }

        if (!isset($sifen_json['factura_general'])) {
            return response()->json(['error' => 'Falta el campo factura_general'], 422);
        }

        if (!isset($sifen_json['factura_numero'])) {
            return response()->json(['error' => 'Falta el campo factura_numero'], 422);
        }

        $existe_factura = Factura::where('factura_sucursal', $sifen_json['factura_sucursal'])
        ->where('factura_general', $sifen_json['factura_general'])
        ->where('factura_numero', $sifen_json['factura_numero'])
        ->where('estado_id', 1)
        ->first();

        if($existe_factura){
            return response()->json(['error' => 'Ya existe factura cargado con este numero: ' . $sifen_json['factura_sucursal'] . '-' . $sifen_json['factura_general']. '-' . $sifen_json['factura_numero']], 422);
        }

        $existe_registro = Factura::where('registro_id', $sifen_json['registro_id'])
        ->where('estado_id', 1)
        ->first();

        if($existe_registro){
            return response()->json(['error' => 'Ya existe factura con este IdRegistro: ' . $sifen_json['registro_id'] ], 422);
        }

        try {

            $sifen = DB::transaction(function () use ($sifen_json) {
                
                $data = $sifen_json;
                $cliente = $data['cliente'];
                $user = auth()->user();

                $persona = Persona::updateOrCreate([
                    'documento' => $cliente['documento']
                ],
                [
                    'nombre' => $cliente['nombre'],
                    'apellido' => $cliente['apellido'],
                    'tipo_persona_id' => 1,
                    'fecha_nacimiento' => null,
                    'sexo_id' => 1,
                    'estado_civil' => 1,
                    'email' => $cliente['correo'],
                    'celular' => $cliente['celular'],
                    'ruc' => $cliente['ruc'],
                    'numero_casa' => $cliente['numeroCasa'],
                    'dncp' => $cliente['numeroCasa'],
                    'diplomatico' => $cliente['diplomatico'],
                    'estado_id' => 1,
                    'user_id' => $user->id,
                    'usuario_modificacion' => $user->id
                ]);

                $timbrado = Timbrado::find(1);
                $entidad = Entidad::find(1);
                $establecimiento = Establecimiento::find($data['establecimiento_id']);
                if (!$establecimiento) {
                    throw new \Exception('No existe establecimiento con este Id: ' . $data['establecimiento_id']);
                }

                // Validar que haya items
                if (empty($data['items']) || !is_array($data['items'])) {
                    throw new \Exception('La factura debe traer items');
                }

                $factura = Factura::create([
                    'persona_id' => $persona->id,
                    'timbrado_id' => $timbrado->id,
                    'establecimiento_id' => $establecimiento->id,
                    'factura_sucursal' => $data['factura_sucursal'],
                    'factura_general' => $data['factura_general'],
                    'factura_numero' => $data['factura_numero'],
                    'fecha_factura' => $data['fecha_factura'],
                    'registro_id' => $data['registro_id'],
                    'tipo_documento_id' => $data['tipo_factura'],
                    'tipo_transaccion_id' => $entidad->tipo_transaccion_id,
                    'condicion_pago' => $data['condicionPago'],
                    'concepto' => $data['concepto_factura'],
                    'monto_total' => $data['totalPagado'],
                    'monto_abonado' => $data['totalPagado'],
                    'monto_devuelto' => 0,
                    'estado_id' => 1,
                    'anulado' => 0,
                    'fecha_anulado' => null,
                    'user_id' => $user->id,
                    'usuario_anulacion' => null
                ]);

                foreach ($data['items'] as $item) {
                    $ivaAfecta = $item['ivaTasa'] ?? 0;
                    $exento = 0;
                    $grabado = 0;
                    if ($ivaAfecta == 0) {
                        throw new \Exception('Iva Afectado no puede ser cero.');
                    }

                    if($ivaAfecta == 3){
                        $grabado = 0;
                        $exento = $item['precioTotal'] ?? 0;
                    }

                    if($ivaAfecta == 5){
                        $grabado = $item['baseGravItem'] ?? 0;
                        $exento = 0;
                    }
                    
                    if($ivaAfecta == 10){
                        $grabado = $item['baseGravItem'] ?? 0;
                        $exento = 0;
                    }

                    FacturaDetalle::create([
                        'factura_id'     => $factura->id,
                        'descripcion'    => $item['descripcion'],
                        'codigo'         => $item['codigo'] ?? null,
                        //'unidad_medida'  => $item['unidadMedida'] ?? null,
                        'iva_afecta'     => $ivaAfecta,
                        'cantidad'       => $item['cantidad'],
                        'precio_unitario'=> $item['precioUnitario'],
                        'monto_total'   => $item['precioTotal'],
                        'exento' => $exento,
                        'grabado' => $grabado,
                        'iva'       => $item['IvaItem'] ?? 0,
                    ]);
                }

                foreach ($data['forma_pago'] as $item) {

                    FacturaCobro::create([
                        'factura_id'     => $factura->id,
                        'forma_cobro_id'    => $item['tipoPago'],
                        'banco_id'    => $item['banco_id'],
                        'monto'    => $item['monto'],
                    ]);
                }

                $sifen = Sifen::create([
                    'factura_id' => $factura->id,
                    'cdc' => '',
                    'tipo_doc' => $factura->tipo_documento_id,
                    'documento_xml' => '',
                    'documento_pdf' => '',
                    'documento_zip' => null,
                    'zipeado' => 'N',
                    'secuencia' => 0,
                    'sifen_num_transaccion' => 0,
                    'sifen_estado' => 'PENDIENTE',
                    'sifen_mensaje' => '',
                    'fecha_firma' => Carbon::now(),
                    'link_qr' => '',
                    'evento' => null,
                    'sifen_cod' => 0,
                    'tipo_transaccion' => $factura->tipo_transaccion_id,
                    'condicion_pago' => $factura->condicion_pago,
                    'moneda' => 'PYG',
                    'correo_enviado' => 'N',
                    'enviado_sifen' => 'N',
                    'sifen_respuesta_consulta_xml' => '',
                    'sifen_idprod' => 0
                ]);

                // Generar XML y ACTUALIZAR sifen antes de enviar
                $builder = new FacturaJsonBuilder($factura);
                $xmlBuilder = new FacturaXMLBuilder();

                $json = [];
                if ((int)$factura->tipo_documento_id === 1) {
                    $json = $builder->jsonContado();
                }

                $documento = $xmlBuilder->generate($json, $factura->timbrado_id);

                $sifen->update([
                    'cdc'          => $documento['cdc'],
                    'documento_xml'=> $documento['archivo_xml'],
                    'fecha_firma'  => $documento['fecha_firma'],
                    'link_qr'      => $documento['link_qr'] ?? '',
                    'sifen_estado' => 'PENDIENTE',
                ]);

                return $sifen;

            });

            $resp = $this->sifen->enviar_directo($sifen);

            return response()->json($resp);

        }catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        
        };
        
    }

    public function evento_post(Factura $factura, Request $request)
    {
        $request->validate([
            'motivo' => 'required',
            'tipo_evento' => 'required'
        ]);

        $xml_evento = $this->sifen->cancelacion($factura->sifen, $request->motivo);
        $fecha_anulado = now()->toDateString();
        $evento =  $this->sifen->envioEvento($factura->sifen, $xml_evento, $factura->sifen->secuencia_evento, 2);
        if (($evento['status'] ?? '') === 'Aprobado') {
            $factura->update([
                'anulado' => 1,
                'fecha_anulado' => $fecha_anulado,
                'usuario_anulacion' => auth()->user()->id,
                'motivo_anulacion' => $request->motivo,
            ]);
        }
        return redirect()->route('consulta.factura')->with('message', $evento['mensaje']);
    }
    
}

<?php

namespace App\Http\Livewire\Factura;

use App\Models\Banco;
use App\Models\Entidad;
use App\Models\Establecimiento;
use App\Models\Factura;
use App\Models\FacturaCobro;
use App\Models\FacturaDetalle;
use App\Models\FormaCobro;
use App\Models\Numeracion;
use App\Models\Persona;
use App\Models\Timbrado;
use App\Models\TipoPersona;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FacturaCreate extends Component
{

    public $procesando, $tipo_persona_id, $documento, $nombre, $apellido, $ruc, $email, $persona;
    public $descripcion_item, $precio_unitario, $cantidad, $iva_afecta, $monto_total;
    public $tipo_persona, $data = [], $item_id, $forma_cobros, $forma_pago_id, $verBanco, $bancos
    , $ver_vuelto, $vuelto, $banco_id, $monto_abonado_2, $monto_abonado, $verBanco_2, $banco_id_2, $forma_pago_id_2
    , $verSegundo, $total_a_pagar, $concepto, $factura;

    public function mount()
    {
        $this->procesando = false;
        $this->tipo_persona = TipoPersona::all();
        $this->tipo_persona_id = 1;
        $this->iva_afecta = 10;
        $this->data = [];
        $this->item_id = 0;
        $this->forma_cobros = FormaCobro::whereIn('id', [1, 2, 5, 6])->get();
        $this->forma_pago_id = 1;
        $this->verBanco = 'none';
        $this->verBanco_2 = 'none';
        $this->verSegundo = 'none';
        $this->ver_vuelto = 'block';
        $this->vuelto = 0;
        $this->bancos = Banco::all();
        $this->banco_id = 1;
        $this->monto_abonado_2 = 0;
        $this->monto_abonado = 0;
    }

    public function render()
    {
        return view('livewire.factura.factura-create');
    }

    public function buscar_persona()
    {
        $ci = str_replace('.', '', $this->documento);
        $persona = Persona::where('documento', $ci)->first();
        if ($persona){
            $this->persona = $persona;
            $this->nombre = $persona->nombre;
            $this->apellido = $persona->apellido;
            $this->email = $persona->email;
            $this->ruc = $persona->ruc;
            $this->tipo_persona_id = $persona->tipo_persona_id;
        }else{
            $this->reset('nombre');
            $this->reset('apellido');
            $this->reset('ruc');
            $this->reset('email');
            $this->reset('persona');           
        }
    }

    public function monto_total_calcular(){
        $cantidad = 0;
        $precio_unitario = 0;
        $monto_total = 0;
        
        if(empty($this->cantidad)){
            $cantidad = 0;
        }

        if($this->cantidad == ''){
            $cantidad = 0;
        }

        if($this->cantidad == 'NaN'){
            $cantidad = 0;
        }

        $cantidad =(int) str_replace(['.', ','], '', $this->cantidad);

        if(empty($this->precio_unitario)){
            $precio_unitario = 0;
        }

        if($this->precio_unitario == ''){
            $precio_unitario = 0;
        }

        if($this->precio_unitario == 'NaN'){
            $precio_unitario = 0;
        }

        $precio_unitario = (float) str_replace(['.', ','], '', $this->precio_unitario);

        $monto_total = $precio_unitario * $cantidad;
        $this->monto_total = number_format($monto_total, 0, ".", ".");
    }

    public function toDecimal($valor, $decimals = 2)
    {
        if ($valor === null || trim($valor) === '') return null;
        $valor = str_replace('.', '', $valor);   // quita miles
        $valor = str_replace(',', '.', $valor);  // coma -> punto
        return number_format((float) $valor, $decimals, '.', '');
    }

    public function agregar_items()
    {
        $validaciones = $this->validar_item();
        if($validaciones == false){
            return false;
        }
        $cantidad =(int) str_replace(['.', ','], '', $this->cantidad);
        $precio_unitario = (float) str_replace(['.', ','], '', $this->precio_unitario);
        $monto_total = $precio_unitario * $cantidad;
        $exento = 0;
        $grabado = 0;
        $grabado10 = 0;
        $grabado5 = 0;
        $iva = 0;
        $iva10 = 0;
        $iva5 = 0;
        
        if($this->iva_afecta == 3){
            $exento = $monto_total;
        }

        if($this->iva_afecta == 10){
            $iva = round($monto_total / 11, 0);
            $iva10 = $iva;
            $grabado = $monto_total - $iva;
            $grabado10 = $monto_total;
        }

        if($this->iva_afecta == 5){
            $iva = round($monto_total / 21, 0);
            $iva5 = $iva;
            $grabado = $monto_total - $iva;
            $grabado5 = $monto_total;
        }

        $this->item_id = $this->item_id + 1;

        $this->data[] = [
            'id' => $this->item_id,
            'item_descripcion' => $this->descripcion_item,
            'precio_unitario' => $precio_unitario,
            'cantidad' => $cantidad,
            'monto_total' => $monto_total,
            'exento' => $exento,
            'grabado' => $grabado,
            'iva' => $iva,
            'grabado10' => $grabado10,
            'grabado5' => $grabado5,
            'iva10' => $iva10,
            'iva5' => $iva5,
            'iva_afecta' => $this->iva_afecta,
        ];

        $this->restablcerDatosFactura();
        $this->total_a_pagar = collect($this->data)->sum('monto_total');
    }

    public function validar_item()
    {
        if(empty($this->descripcion_item)){
            $this->emit('mensaje_error', 'La descripcion del item no puede ser vacio.');
            return false;
        }

        $cantidad = 0;
        $precio_unitario = 0;
        
        if(empty($this->cantidad)){
            $this->emit('mensaje_error', 'Debe de especificar la cantidad.');
            return false;
        }

        if($this->cantidad == ''){
            $this->emit('mensaje_error', 'Debe de especificar la cantidad.');
            return false;
        }

        if($this->cantidad == 'NaN'){
            $this->emit('mensaje_error', 'Debe de especificar la cantidad.');
            return false;
        }

        $cantidad =(int) str_replace(['.', ','], '', $this->cantidad);

        if($cantidad <= 0){
            $this->emit('mensaje_error', 'La cantidad no puede ser menor o igual a cero.');
            return false;
        }

        if(empty($this->precio_unitario)){
            $this->emit('mensaje_error', 'Debe de especificar el precio unitario.');
            return false;
        }

        if($this->precio_unitario == ''){
            $this->emit('mensaje_error', 'Debe de especificar el precio unitario.');
            return false;
        }

        if($this->precio_unitario == 'NaN'){
            $this->emit('mensaje_error', 'Debe de especificar el precio unitario.');
            return false;
        }

        $precio_unitario = (float) str_replace(['.', ','], '', $this->precio_unitario);
        
        if($precio_unitario <= 0){
            $this->emit('mensaje_error', 'El precio unitario no puede ser menor o igual a cero.');
            return false;
        }

        return true;
    }

    public function restablcerDatosFactura()
    {
        $this->reset('cantidad');
        $this->reset('descripcion_item');
        $this->reset('precio_unitario');
        $this->reset('monto_total');
    }

    public function eliminarfila(int $id)
    {   
        $index = array_search($id, array_column($this->data, 'id'));
        if ($index !== false) {
            unset($this->data[$index]);
            $this->data = array_values($this->data); // reindexa y refresca
        }
        $this->total_a_pagar = collect($this->data)->sum('monto_total');
    }

    public function updatedFormaPagoId($value)
    {
        $aux = FormaCobro::find($value);
        if($aux->banco_ver == 0){
            $this->verBanco = 'none';
            $this->ver_vuelto = 'block';
        }else{
            $this->verBanco = 'block';
            $this->ver_vuelto = 'none';
            $this->vuelto = 0;
        }
    }

    public function updatedFormaPagoId2($value)
    {
        $aux = FormaCobro::find($value);
        if($aux->banco_ver == 0){
            $this->verBanco_2 = 'none';
        }else{
            $this->verBanco_2 = 'block';
        }
    }

    public function forma_cobro_agregar()
    {
        
        if($this->verSegundo == 'none'){
            $this->verSegundo = 'block';
        }else{
            $this->verSegundo = 'none';
            $this->forma_pago_id_2 = 1;
            $this->banco_id_2 = 1;
            $this->monto_abonado_2 = 0;
            $this->verBanco_2 = 'none';
        }
    }

    public function grabar()
    {
        $validar = $this->validar_grabacion();
        if($validar == false){
            return false;
        }
        
        $this->procesando = true;

        if (empty($this->persona)){
            $apellido = (empty($this->apellido) ? ' ' : $this->apellido);
            $documento = str_replace('.', '', $this->documento);
            $persona = Persona::create([
                'documento' => $documento,
                'nombre' => $this->documento,
                'apellido' => $apellido,
                'tipo_persona_id' => $this->tipo_persona_id,
                'fecha_nacimiento' => null,
                'sexo_id' => 0,
                'estado_civil' => 0,
                'email' => $this->email,
                'celular' => ' ',
                'ruc' => $this->ruc,
                'estado_id' => 1,
                'user_id' => auth()->user()->id,
                'usuario_modificacion' => auth()->user()->id,
            ]);
            $this->persona = $persona;
        } else {
            if($this->persona->documento > 0){
                $persona = Persona::find($this->persona->id);
                $apellido = (empty($this->apellido) ? ' ' : $this->apellido);
                $persona->update([
                    'nombre' => $this->documento,
                    'apellido' => $apellido,
                    'tipo_persona_id' => $this->tipo_persona_id,
                    'fecha_nacimiento' => null,
                    'sexo_id' => 0,
                    'estado_civil' => 0,
                    'email' => $this->email,
                    'celular' => ' ',
                    'ruc' => $this->ruc,
                    'usuario_modificacion' => auth()->user()->id,
                ]);
            }
            
        }

        try {
            DB::transaction(function () {
                $fecha = Carbon::now()->toDateString();
                $tipoDocumento = 1;
                $primero_monto = str_replace('.', '', $this->monto_abonado);
                $segundo_monto = str_replace('.', '', $this->monto_abonado_2);
                $total_monto_abonado = $primero_monto + $segundo_monto;
                $total_a_pagar = str_replace('.', '', $this->total_a_pagar);
                $vuelto = $total_monto_abonado - $total_a_pagar;
                $entidad = Entidad::find(1);
                $establecimiento = Establecimiento::find(1)
                ->first();
                $timbrado = Timbrado::where('entidad_id', $entidad->id)
                ->where('estado_id', 1)
                ->first();

                $numeracion = Numeracion::where('timbrado_id', $timbrado->id)
                ->where('establecimiento_id', $establecimiento->id)
                ->where('tipo_documento_id', $tipoDocumento)
                ->where('estado_id', 1)
                ->lockForUpdate()
                ->first();

                if (!$timbrado) {
                    throw new \Exception('No se encontró un timbrado activo.');
                }

                $numero_factura = $numeracion->numero_siguiente;

                $factura = Factura::create([
                    'persona_id' => $this->persona->id,
                    'timbrado_id' => $timbrado->id,
                    'establecimiento_id' => $establecimiento->id,
                    'factura_sucursal' => $establecimiento->sucursal,
                    'factura_general' => $establecimiento->general,
                    'factura_numero' => $numero_factura,
                    'fecha_factura' => $fecha,
                    'tipo_documento_id' => $tipoDocumento,
                    'tipo_transaccion_id' => $entidad->tipo_transaccion_id,
                    'condicion_pago' => 1,
                    'concepto' => $this->concepto,
                    'monto_total' => $total_a_pagar,
                    'monto_abonado' => $total_monto_abonado,
                    'monto_devuelto' => $vuelto,
                    'estado_id' => 1,
                    'anulado' => 0,
                    'fecha_anulado' => null,
                    'user_id' => auth()->user()->id,
                    'usuario_anulacion' => null,
                    'motivo_anulacion' => ' ',
                ]);

                $this->factura = $factura;

                foreach ($this->data as $dat) {
                    FacturaDetalle::create([
                        'factura_id' => $factura->id,
                        'descripcion' => $dat['item_descripcion'],
                        'cantidad' => $dat['cantidad'],
                        'precio_unitario' => $dat['precio_unitario'],
                        'monto_total' => $dat['monto_total'],
                        'iva_afecta' => $dat['iva_afecta'],
                        'exento' => $dat['exento'],
                        'grabado' => $dat['grabado'],
                        'iva' => $dat['iva'],
                    ]);
                }

                FacturaCobro::create([
                    'factura_id' => $factura->id,
                    'forma_cobro_id' => $this->forma_pago_id,
                    'banco_id' => $this->banco_id,
                    'monto' => $primero_monto,
                ]);

                if ($this->verSegundo == 'block'){
                    FacturaCobro::create([
                        'factura_id' => $factura->id,
                        'forma_cobro_id' => $this->forma_pago_id_2,
                        'banco_id' => $this->banco_id_2,
                        'monto' => $segundo_monto,
                    ]);
                }

                $numeracion->numero_siguiente += 1;
                $numeracion->save();

            });
            
            return redirect()->route('factura.show', $this->factura)->with('message', 'Facturado correctamente.');
            
        } catch (\Throwable $e) {
            $this->emit('mensaje_error', 'Ocurrió un error al generar la factura: ' . $e->getMessage());
            $this->procesando = false;
            return false;
        }

    }

    public function validar_grabacion()
    {
        if($this->documento == ''){
            $this->emit('mensaje_error', 'El nro. de documento no puede ser vacio.');
            return false;
        }

        if($this->documento == 'NaN'){
           $this->emit('mensaje_error', 'El nro. de documento no puede ser vacio.');
            return false;
        }

        if(empty($this->nombre)){
            $this->emit('mensaje_error', 'El nombre no puede ser vacio.');
            return false;
        }

        if(empty($this->concepto)){
            $this->emit('mensaje_error', 'El concepto de la factura no puede ser vacio.');
            return false;
        }

        if(empty($this->email)){
            $this->emit('mensaje_error', 'El campo email no puede ser vacio.');
            return false;
        }

        if(empty($this->email)){
            $this->emit('mensaje_error', 'El campo email no puede ser vacio.');
            return false;
        }
        
        if($this->tipo_persona_id <> 1){
            if(empty($this->ruc)){
                $this->emit('mensaje_error', 'El campo ruc no puede ser vacio.');
                return false;
            }
        }

        if($this->total_a_pagar == 0){
            $this->emit('mensaje_error', 'Debe cargar el detalle de la factura.');
            return false;
        }

        if($this->forma_pago_id == 2){
            if($this->banco_id == 1){
                $this->emit('mensaje_error', 'Debe seleccionar un banco.');
                return false;
            }
        }

        if($this->forma_pago_id == 5){
            if($this->banco_id == 1){
                $this->emit('mensaje_error', 'Debe seleccionar un banco.');
                return false;
            }
        }

        if(empty($this->monto_abonado)){
            $this->emit('mensaje_error', 'El monto a abonar debe ser mayor a cero.');
            return false;
        }

        if($this->monto_abonado == ''){
            $this->emit('mensaje_error', 'El monto a abonar debe ser mayor a cero.');
            return false;
        }

        if($this->monto_abonado == 'NaN'){
            $this->emit('mensaje_error', 'El monto a abonar debe ser mayor a cero.');
            return false;
        }

        $monto_abonado =(int) str_replace(['.', ','], '', $this->monto_abonado);

        if($monto_abonado <= 0){
            $this->emit('mensaje_error', 'El monto a abonar debe ser mayor a cero.');
            return false;
        }

        if($this->verSegundo == 'block'){
            if($this->forma_pago_id_2 == 2){
                if($this->banco_id_2 == 1){
                    $this->emit('mensaje_error', 'Debe seleccionar un banco para la segunda forma de cobro.');
                    return false;
                }
            }

            if($this->forma_pago_id_2 == 5){
                if($this->banco_id_2 == 1){
                    $this->emit('mensaje_error', 'Debe seleccionar un banco para la segunda forma de cobro.');
                    return false;
                }
            }

            if(empty($this->monto_abonado_2)){
                $this->emit('mensaje_error', 'El monto a abonar debe ser mayor a cero para la segunda forma de cobro.');
                return false;
            }

            if($this->monto_abonado_2 == ''){
                $this->emit('mensaje_error', 'El monto a abonar debe ser mayor a cero para la segunda forma de cobro.');
                return false;
            }

            if($this->monto_abonado_2 == 'NaN'){
                $this->emit('mensaje_error', 'El monto a abonar debe ser mayor a cero para la segunda forma de cobro.');
                return false;
            }

            $monto_abonado_2 =(int) str_replace(['.', ','], '', $this->monto_abonado_2);

            if($monto_abonado_2 <= 0){
                $this->emit('mensaje_error', 'El monto a abonar debe ser mayor a cero para la segunda forma de cobro.');
                return false;
            }
        }

        return true;
    }

}

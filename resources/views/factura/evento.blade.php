@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/elements/alert.css')}}">
    <link href="{{asset('assets/css/elements/infobox.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/apps/invoice-preview.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="col-lg-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">

                <form action="{{route('factura.evento_post', $factura)}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-row mb-2">
                                <div class="form-group col-md-3">
                                    <label for="documento">Factura</label>
                                    @php
                                        $documento_factura = $factura->factura_sucursal . '-' . $factura->factura_general. '-' . str_pad($factura->factura_numero, 7, '0', STR_PAD_LEFT);
                                    @endphp
                                    <input type="text" class="form-control" value="{{$documento_factura}}" readonly>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="nombre">Cliente</label>
                                    <input type="text" class="form-control" value="{{$factura->persona->nombre .' '.$factura->persona->apellido}}" readonly>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="apellido">Total Factura</label>
                                    <input type="text" class="form-control text-right" value="{{number_format($factura->monto_total, 0, ".", ".")}}" readonly>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="ruc">CDC</label>
                                    <input type="text" class="form-control" value="{{$factura->sifen->cdc}}" readonly>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="Tipo_evento">Tipo Evento</label>
                                    <select name="tipo_evento" id="tipo_evento" class="form-control">
                                        <option value="1">Cancelacion</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="motivo">Motivo</label>
                                    <input type="text" name="motivo" id="motivo" class="form-control" value="" required>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <button id="btnEnviar" type="submit" class="btn btn-success">
                                Enviar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('js')
    <script>
        let enviado = false;
        document.querySelector('form').addEventListener('submit', function (e) {

            if (enviado) {
                e.preventDefault();
                return false;
            }

            enviado = true;

            let btn = document.getElementById('btnEnviar');
            btn.disabled = true;
            btn.innerText = 'Enviando...';

        });
    </script>
@endsection

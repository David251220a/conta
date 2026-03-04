<div  wire:init="consultarPendientes" class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="alert alert-arrow-left alert-icon-left alert-light-primary mb-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9">
                        </path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <strong>Información!</strong> Facturas Rechazadas o Pendiente de Respuesta.
                    </div>
                </div>
            </div>

            <div class="row mt-1">
                <div  class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                            <thead>
                                <tr>
                                    <th class="">
                                        Fecha
                                    </th>
                                    <th class="">Factura</th>
                                    <th class="">Monto</th>
                                    <th class="">Anulado</th>
                                    <th class="">Estado Sifen</th>
                                    <th class="">Mensaje Sifen</th>
                                    <th class="">Evento</th>
                                    <th class="">Mensaje Evento</th>
                                    <th class="text-center">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td class="">
                                            {{$item->fecha_factura}}
                                        </td>
                                        <td>
                                            {{$item->factura_sucursal}}-{{$item->factura_general}}-{{str_pad($item->factura_numero, 7, '0', STR_PAD_LEFT)}}
                                        </td>
                                        <td class="text-right">{{number_format($item->monto_total, 0, ".", ".")}}</td>
                                        <td>
                                            @if ($item->anulado == 0)
                                                <span class="badge badge-success">Activo{{$item->anulado}}</span>
                                            @else
                                                <span class="badge badge-danger">Anulado</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->sifen->sifen_estado == 'APROBADO')
                                                <span class="badge badge-success">APROBADO</span>
                                            @elseif($item->sifen->sifen_estado == 'RECHAZADO')
                                                <span class="badge badge-danger">RECHAZADO</span>
                                            @else
                                                <span class="badge badge-info">{{$item->sifen->sifen_estado}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{$item->sifen->sifen_envio_msjrespuesta}}
                                        </td>
                                        <td>
                                            {{$item->sifen->evento}}
                                        </td>
                                        <td>
                                            {{$item->sifen->sifen_evento_msjrespuesta}}
                                        </td>
                                        <td class="text-center">
                                            <ul class="table-controls">
                                                <li>
                                                    <a class="ml-2" href="{{$item->sifen->link_qr}}" target="__blank" data-toggle="tooltip" data-placement="top" title="Consulta Sifen">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                        class="feather feather-chrome"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="21.17" y1="8" x2="12" y2="8"></line>
                                                        <line x1="3.95" y1="6.06" x2="8.54" y2="14"></line><line x1="10.88" y1="21.94" x2="15.46" y2="14"></line></svg>
                                                    </a>

                                                    <a class="ml-2" href="{{route('factura.show', $item)}}" target="__blank" data-toggle="tooltip" data-placement="top" title="Ver Factura">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                        class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                    </a>

                                                    @if ($item->anulado == 0)
                                                        <a class="ml-2" href="{{route('factura.evento', $item)}}" data-toggle="tooltip" data-placement="top" title="Eventos">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                            class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                                        </a>
                                                    @endif
                                                    
                                                    @if ($item->sifen->sifen_estado == 'RECHAZADO')
                                                        <a class="ml-2" href="#" onclick="anular({{$item->id}})" data-toggle="tooltip" data-placement="top" title="Anular">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
                                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6">
                                                            </polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17">
                                                            </line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                        </a>
                                                    @endif
                                                    

                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="9"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

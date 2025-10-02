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
                                        <th class="">Estado</th>
                                        <th class="">Estado Sifen</th>
                                        <th class="text-center">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td class="">
                                                {{$item->fact->fecha_factura}}
                                            </td>
                                            <td>
                                                {{$item->fact->factura_sucursal}}-{{$item->fact->factura_general}}-{{$item->fact->factura_numero}}
                                            </td>
                                            <td>{{$item->fact->monto_total}}</td>
                                            <td>
                                                @if ($item->fact->estado_id == 1)
                                                    <span class="badge badge-success">Activo</span>
                                                @else
                                                    <span class="badge badge-danger">Anulado</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->sifen_estado == 'APROBADO')
                                                    <span class="badge badge-success">APROBADO</span>
                                                @elseif($item->sifen_estado == 'RECHAZADO')
                                                    <span class="badge badge-danger">RECHAZADO</span>
                                                @else
                                                    <span class="badge badge-info">{{$item->sifen_estado}}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @if ($item->sifen_estado == 'RECHAZADO')
                                                        <li>
                                                            <form action="{{route('sifen.reenviar_sifen', $item)}}" method="post">
                                                                @csrf
                                                                <button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Enviar">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M568.4 37.7C578.2 34.2 589 36.7 596.4 44C603.8 51.3 606.2 62.2 602.7 72L424.7 568.9C419.7 582.8 406.6 592 391.9 592C377.7 592 364.9 583.4 359.6 570.3L295.4 412.3C290.9 401.3 292.9 388.7 300.6 379.7L395.1 267.3C400.2 261.2 399.8 252.3 394.2 246.7C388.6 241.1 379.6 240.7 373.6 245.8L261.2 340.1C252.1 347.7 239.6 349.7 228.6 345.3L70.1 280.8C57 275.5 48.4 262.7 48.4 248.5C48.4 233.8 57.6 220.7 71.5 215.7L568.4 37.7z"/></svg>
                                                                </button> 
                                                            </form>
                                                            
                                                        </li>
                                                    @endif
                                                    <li><a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                                    <li><a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6">total</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

        </div>
    </div>
</div>
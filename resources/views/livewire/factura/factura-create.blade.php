<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 filtered-list-search mx-auto">
                    <div class="alert alert-icon-left alert-light-success mb-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12" y2="17"></line></svg>
                        Emitir Factura
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-3">
                            <label for="documento">Documento</label>
                            <input wire:model.defer="documento" wire:blur="buscar_persona" type="text" class="form-control" onkeyup="punto_decimal(this)">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="nombre">Nombre</label>
                            <input wire:model.defer="nombre" type="text" class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="apellido">Apellido</label>
                            <input wire:model.defer="apellido" type="text" class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="ruc">RUC</label>
                            <input wire:model.defer="ruc" type="text" class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="email">Email</label>
                            <input wire:model.defer="email" type="text" class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="tipo_persona_id">Tipo</label>
                            <select wire:model.defer="tipo_persona_id" class="form-control">
                                @foreach ($tipo_persona as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                    </div>

                    <div class="form-row mb-2">
                        <div class="form-group col-md-12">
                            <label for="concepto">Concepto de la Factura</label>
                            <input wire:model.defer="concepto" type="text" class="form-control">
                        </div>
                    </div>

                </div>
            </div>

            <h4>Detalle de la Factura</h4>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-3">
                            <label for="descripcion_item">Descripcion Item</label>
                            <input wire:model.defer="descripcion_item" type="text" class="form-control">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="precio_unitario">Precio Unitario</label>
                            <input wire:model.defer="precio_unitario" type="text" wire:blur="monto_total_calcular" class="form-control" onkeyup="punto_decimal(this)">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="cantidad">Cantidad</label>
                            <input wire:model.defer="cantidad" wire:blur="monto_total_calcular" type="text" class="form-control" onkeyup="punto_decimal(this)">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="iva_afecta">IVA Afecta</label>
                            <select wire:model.defer="iva_afecta" class="form-control">
                                <option value="10">10 %</option>
                                <option value="5">5 %</option>
                                <option value="3">EXENTA</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="monto_total">Monto Total</label>
                            <input wire:model.defer="monto_total" type="text" class="form-control" readonly>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="tipo_persona_id" class="w-full" style="width: 100%">Accion</label>
                            <button type="button"  wire:click="agregar_items" class="btn btn-primary btn-sm">Agregar</button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row ">
                <div  class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                            <thead>
                                <tr>
                                    <th width="50%" class="text-center">Item</th>
                                    <th width="10%" class="text-center">Precio Unitario</th>
                                    <th width="5%" class="text-center">Cantidad</th>
                                    <th width="10%" class="text-center">Exento</th>
                                    <th width="10%" class="text-center">10%</th>
                                    <th width="10%" class="text-center">5%</th>
                                    <th width="5%" class="text-center">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item['item_descripcion'] }}</td>
                                        <td class="text-right">{{ number_format($item['precio_unitario'] , 0, ".", ".")}}</td>
                                        <td class="text-right">{{ number_format($item['cantidad'], 0, ".", ".") }}</td>
                                        <td class="text-right">{{ number_format($item['exento'] , 0, ".", ".")}}</td>
                                        <td class="text-right">{{ number_format($item['grabado10'] , 0, ".", ".")}}</td>
                                        <td class="text-right">{{ number_format($item['grabado5'] , 0, ".", ".")}}</td>
                                        <td>
                                            <button type="button" wire:click="eliminarfila({{$item['id']}})" class="btn btn-danger btn-sm">
                                                <i class="fas fa-solid fa-xmark"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total Factura</th>
                                    <th colspan="4">
                                        @if (empty($data))
                                            0
                                        @else
                                            {{ number_format(collect($data)->sum('monto_total'), 0, ',', '.') }}
                                        @endif
                                        
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="3">Total I.V.A.</th>
                                    <th colspan="4">
                                        @if (empty($data))
                                            0
                                        @else
                                            {{ number_format(collect($data)->sum('iva'), 0, ',', '.') }}
                                        @endif
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <h6 class="font-bold mb-1">FORMA DE PAGO</h6>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-2">
                            <label for="forma_pago_id">Forma Pago</label>
                            <select wire:model="forma_pago_id" class="form-control">
                                @foreach ($forma_cobros as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2" style="display: {{$verBanco}}">
                            <label for="banco_id">Banco</label>
                            <select wire:model="banco_id" class="form-control">
                                @foreach ($bancos as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2 col-sm-12">
                            <label for="total_pagado">Monto Abonado</label>
                            <input type="text" wire:model.defer="monto_abonado" class="form-control bg-white text-right text-dark" onkeyup="punto_decimal(this)">
                        </div>

                        <div class="form-group col-md-2 col-sm-12">
                            <label for="" class="w-full" style="width: 100%">Accion</label>
                            <button type="button" wire:click="forma_cobro_agregar" class="btn btn-secondary">
                                @if ($verSegundo == 'none')
                                    Agregar Forma Cobro
                                @else
                                    Quitar Forma Cobro
                                @endif
                                
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row"  style="display: {{$verSegundo}}">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-2">
                            <label for="forma_pago_id_2">Forma Pago</label>
                            <select wire:model="forma_pago_id_2" class="form-control">
                                @foreach ($forma_cobros as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2" style="display: {{$verBanco_2}}">
                            <label for="banco_id_2">Banco</label>
                            <select wire:model="banco_id_2" class="form-control">
                                @foreach ($bancos as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2 col-sm-12">
                            <label for="total_pagado">Monto Abonado</label>
                            <input type="text" wire:model.defer="monto_abonado_2" class="form-control bg-white text-right text-dark" onkeyup="punto_decimal(this)">
                        </div>

                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-2 col-sm-12">
                            <label for="total_pagado">Total a Pagar </label>
                            <input type="text" class="form-control" value="{{ number_format($total_a_pagar, 0, ',', '.') }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button
                        type="button"
                        wire:click="grabar"
                        :disabled="$wire.procesando"
                        class="btn btn-success"
                    >
                        <span wire:loading.remove wire:target="grabar">Grabar</span>
                        <span wire:loading wire:target="grabar">Procesando...</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

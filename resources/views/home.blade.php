@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/elements/alert.css')}}">
    <link href="{{asset('assets/css/elements/infobox.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/tables/table-basic.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="col-lg-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="alert alert-arrow-left alert-icon-left alert-light-primary mb-4" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9">
                            </path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                            <strong>Información!</strong> Facturación del Mes.
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-xl-6 col-md-6 col-sm-12 col-12">
                        <div class="infobox-3">
                            <div class="info-icon" style="background: #8f1414">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 64 64" fill="none">
                                    <rect x="8" y="8" width="40" height="48" rx="4" ry="4" fill="#FFFFFF" stroke="#2E2E2E" stroke-width="2"/>
                                    <line x1="16" y1="20" x2="40" y2="20" stroke="#2E2E2E" stroke-width="2"/>
                                    <line x1="16" y1="28" x2="40" y2="28" stroke="#2E2E2E" stroke-width="2"/>
                                    <line x1="16" y1="36" x2="32" y2="36" stroke="#2E2E2E" stroke-width="2"/>
                                    <circle cx="48" cy="48" r="12" fill="#dc3545"/>
                                    <line x1="42" y1="42" x2="54" y2="54" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="54" y1="42" x2="42" y2="54" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <h5 class="info-heading">Facturas Rechazadas</h5>
                            <a class="info-link" href="#">Ver todos los rechazados
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                <line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </a>
                            </a>
                        </div>
                    </div>

                    <div class="col-xl-6 col-md-6 col-sm-12 col-12">
                        <div class="infobox-3">
                            <div class="info-icon" style="background: #0d3a0d">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 64 64" fill="none">
                                    <rect x="8" y="8" width="40" height="48" rx="4" ry="4" fill="#FFFFFF" stroke="#2E2E2E" stroke-width="2"/>
                                    <line x1="16" y1="20" x2="40" y2="20" stroke="#2E2E2E" stroke-width="2"/>
                                    <line x1="16" y1="28" x2="40" y2="28" stroke="#2E2E2E" stroke-width="2"/>
                                    <line x1="16" y1="36" x2="32" y2="36" stroke="#2E2E2E" stroke-width="2"/>
                                    <circle cx="48" cy="48" r="12" fill="#28a745"/>
                                    <path d="M42 48l4 4 8-8" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <h5 class="info-heading">Facturas Aprobadas</h5>
                            <a class="info-link" href="#">Ver todos los aprobados
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                                <line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </a>
                        </div>
                    </div>

                </div>

                <div class="row mt-5">
                    <div  class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                                <thead>
                                    <tr>
                                        <th class="">
                                            #
                                        </th>
                                        <th class="">Name</th>
                                        <th class="">Date</th>
                                        <th class="">Sales</th>
                                        <th class="text-center">Icons</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="">
                                            1
                                        </td>
                                        <td>
                                            <p class="mb-0">Shaun Park</p>
                                        </td>
                                        <td>10/08/2020</td>
                                        <td>320</td>

                                        <td class="text-center">
                                            <ul class="table-controls">
                                                <li><a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Settings"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings text-primary"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></a> </li>
                                                <li><a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                                <li><a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5">total</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

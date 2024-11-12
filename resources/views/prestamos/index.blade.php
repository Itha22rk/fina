@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Préstamos</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @can('crear-prestamo')
                        <a class="btn btn-warning" href="{{ route('prestamos.create') }}">Nuevo préstamo</a>
                        @endcan
                        <table class="table table-striped mt-2">
                            <thead style="background-color:#6777ef">
                                <tr>
                                    <th style="color:#fff;">Cliente</th>
                                    <th style="color:#fff;">Monto Solicitado</th>
                                    <th style="color:#fff;">Interés (%)</th>
                                    <th style="color:#fff;">Monto Total</th>
                                    <th style="color:#fff;">Multa</th> <!-- Nueva columna para Multa -->
                                    <th style="color:#fff;">Fecha de Inicio</th>
                                    <th style="color:#fff;">Fecha de Vencimiento</th>
                                    <th style="color:#fff;">Monto Pendiente</th> <!-- Nueva columna para Monto Pendiente -->
                                    <th style="color:#fff;">Total Pagado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prestamos as $prestamo)
                                    @php
                                        // Obtener la suma de todas las multas relacionadas con este préstamo
                                        $multa = $prestamo->multas()->sum('monto');

                                        // Calcular el monto pendiente
                                        $montoPendiente = $prestamo->monto_total + $multa - ($prestamo->total_pagado ?? 0);
                                    @endphp
                                    <tr>
                                        <td>{{ $prestamo->cliente->nombre }}</td>
                                        <td>${{ number_format($prestamo->monto, 2) }}</td> <!-- Monto solicitado -->
                                        <td>{{ $prestamo->interes }}%</td>
                                        <td>${{ number_format($prestamo->monto_total, 2) }}</td> <!-- Monto total -->
                                        <td>${{ number_format($multa, 2) }}</td> <!-- Mostrar multa calculada -->
                                        <td>{{ \Carbon\Carbon::parse($prestamo->fecha_inicio)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($prestamo->fecha_vencimiento)->format('d/m/Y') }}</td>
                                        <td>${{ number_format($montoPendiente, 2) }}</td> <!-- Mostrar monto pendiente -->
                                        <td>${{ number_format($prestamo->total_pagado ?? 0, 2) }}</td> <!-- Total pagado -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

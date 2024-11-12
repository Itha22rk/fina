@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Resumen Diario - {{ $fecha }}</h3>
    </div>

    <div class="section-body">
        <!-- Información del Corte del Día y Caja -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Corte del Día</h4>
                <p><strong>Total de pagos realizados hoy:</strong> ${{ number_format($pagosRealizados, 2) }}</p>
                <p><strong>Total de pagos pendientes:</strong> ${{ number_format($totalPagosPendientes, 2) }}</p>
                <p><strong>Dinero en caja al final del día:</strong> ${{ number_format($dineroEnCaja, 2) }}</p>
                <p><strong>Número de préstamos activos:</strong> {{ $numeroPrestamosActivos }}</p>

            </div>
        </div>

       
    </div>
</section>
@endsection

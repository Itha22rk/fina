@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Resumen Semanal - {{ $inicioSemana->format('d-m-Y') }} a {{ $finSemana->format('d-m-Y') }}</h3>
    </div>

    <div class="section-body">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Corte de la Semana</h4>
                <p><strong>Total de pagos realizados esta semana:</strong> ${{ number_format($pagosRealizados, 2) }}</p>
                <p><strong>Total de pagos pendientes:</strong> ${{ number_format($totalPagosPendientes, 2) }}</p>
                <p><strong>Dinero en caja al final de la semana:</strong> ${{ number_format($dineroEnCaja, 2) }}</p>
                <p><strong>Número de préstamos activos:</strong> {{ $numeroPrestamosActivos }}</p>
            </div>
        </div>
    </div>
</section>
@endsection

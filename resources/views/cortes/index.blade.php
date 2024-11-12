@extends('layouts.app') <!-- Asegúrate de que tu layout principal esté correctamente configurado -->

@section('content')
<div class="container">
    <h1>Corte Diario - {{ $fecha->format('d/m/Y') }}</h1>

    <div class="row">
        <div class="col-md-6">
            <h3>Total de Pagos: ${{ number_format($totalPagos, 2) }}</h3>
            <h4>Total Pendiente: ${{ number_format($totalPagosPendientes, 2) }}</h4>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente ID</th>
                <th>Monto Diario</th>
                <th>Status</th>
                <th>Fecha de Pago</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagos as $pago)
                <tr>
                    <td>{{ $pago->id }}</td>
                    <td>{{ $pago->cliente_id }}</td>
                    <td>${{ number_format($pago->monto_diario, 2) }}</td>
                    <td>{{ $pago->status ? 'Pagado' : 'Pendiente' }}</td>
                    <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay pagos registrados para esta fecha.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

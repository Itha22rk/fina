@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Pagos Pendientes para Hoy</h3>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        
                        {{-- Mensaje de éxito --}}
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @can('crear-pago')
                        <a class="btn btn-warning" href="{{ route('pagos.create') }}">Iniciar Pago</a>
                        @endcan

                        {{-- Mostrar la fecha actual --}}
                        <div class="mb-3">
                            <strong>Fecha Actual:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                        </div>
                        @php
                            $fechaActual = \Carbon\Carbon::now();
                        @endphp
                        <table class="table table-striped mt-2">
                            <thead style="background-color:#6777ef">
                                <tr>
                                    <th style="color:#fff;">Cliente</th>
                                    <th style="color:#fff;">Monto Diario</th>
                                    <th style="color:#fff;">Multa</th>
                                    <th style="color:#fff;">Monto Total a Pagar</th>
                                    <th style="color:#fff;">Fecha de Vencimiento</th>
                                    <th style="color:#fff;">Fecha de Pago</th>
                                    <th style="color:#fff;">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($pagos as $pago)
                                @php
                                    $fechaVencimiento = \Carbon\Carbon::parse($pago->fecha_vencimiento);
                                    $diasDiferencia = \Carbon\Carbon::now()->diffInDays($fechaVencimiento, false);
                                    $estatus = '';
                                    // Obtener la suma de las multas asociadas al pago
                                    $multa = $pago->multas->sum('monto');
                                    $montoTotal = $pago->monto_diario;

                                    if ($pago->fecha_pago) {
                                        // Caso de pago realizado
                                        $estatus = 'Pagado';
                                    } else {
                                        // Si no se ha pagado
                                        if ($diasDiferencia < 0) {
                                            // Pago vencido
                                            $estatus = 'Vencido';
                                            $montoTotal = $pago->monto_diario + $multa; // Si hay multa, se suma
                                        } else {
                                            // Pago no pagado pero dentro del plazo
                                            $estatus = 'No pagado';
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $pago->cliente->nombre }}</td>
                                    <td>${{ number_format($pago->monto_diario, 2) }}</td>
                                    <td>{{ is_numeric($multa) ? '$' . number_format($multa, 2) : 'Sin multa' }}</td>
                                    <td>${{ number_format($montoTotal, 2) }}</td>
                                    <td>{{ $fechaVencimiento->format('d/m/Y') }}</td>
                                    <td>{{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : 'No pagado' }}</td>
                                    <td><span class="{{ $estatus == 'Vencido' ? 'text-danger' : '' }}">{{ $estatus }}</span></td>
                                </tr>
                            @endforeach


                            </tbody>

                        </table>
                        {{-- Paginación, si tienes muchos pagos --}}
                        <div class="pagination justify-content-end">
                            {!! $pagos->links() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const clienteSelect = document.getElementById('cliente_id');
        const pagoSelect = document.getElementById('pago_id');
        const montoTotalInput = document.getElementById('monto_total');

        // Al seleccionar un cliente, cargar los pagos pendientes
        clienteSelect.addEventListener('change', function () {
            const clienteId = clienteSelect.value;

            // Limpiar el select de pagos pendientes
            pagoSelect.innerHTML = '<option value="">Seleccione un pago</option>';

            if (clienteId) {
                // Realizar la solicitud AJAX para obtener los pagos pendientes del cliente seleccionado
                fetch(`/pagos/cliente/${clienteId}/pendientes`)
                    .then(response => response.json())
                    .then(pagos => {
                        console.log(pagos);  // Ver los pagos en la consola

                        if (pagos.length > 0) {
                            pagos.forEach(pago => {
                                const option = document.createElement('option');
                                option.value = pago.id;
                                option.dataset.monto = pago.monto_diario;
                                option.dataset.montoTotal = pago.monto_total;
                                option.text = `Pago #${pago.id} - $${parseFloat(pago.monto_diario).toFixed(2)} (Vence: ${pago.fecha_vencimiento ? new Date(pago.fecha_vencimiento).toLocaleDateString() : 'N/A'})`;

                                pagoSelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.text = 'No hay pagos pendientes para este cliente';
                            pagoSelect.appendChild(option);
                        }
                    })
                    .catch(error => console.error('Error al obtener los pagos pendientes:', error));
            }
        });

        // Actualizar el monto total al seleccionar un pago
        pagoSelect.addEventListener('change', function () {
            const selectedOption = pagoSelect.options[pagoSelect.selectedIndex];
            const montoTotal = parseFloat(selectedOption.dataset.montoTotal || 0);

            // Establecer el monto total calculado en el campo
            montoTotalInput.value = montoTotal.toFixed(2);
        });
    });
</script>

@endsection

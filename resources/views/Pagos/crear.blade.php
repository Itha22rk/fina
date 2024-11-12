@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Registrar Pago</h3>
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

                            {{-- Formulario para registrar un pago --}}
                            <form method="POST" action="{{ route('pagos.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="cliente_id">Cliente</label>
                                    <select class="form-control" id="cliente_id" name="cliente_id" required>
                                        <option value="">Seleccione un cliente</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="pago_id">Seleccionar Pago Pendiente</label>
                                    <select class="form-control" id="pago_id" name="pago_id" required>
                                        <option value="">Seleccione un pago</option>
                                        {{-- Las opciones de pagos se agregarán aquí dinámicamente --}}
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="monto_total">Monto Total a Pagar</label>
                                    <input type="number" class="form-control" id="monto_total" name="monto_total" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="fecha_pago">Fecha de Pago</label>
                                    <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Registrar Pago</button>
                            </form>
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

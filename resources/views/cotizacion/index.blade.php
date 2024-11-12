@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Simulación de Préstamo</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>¡Ups! Algo salió mal.</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="simulacionForm">
                            @csrf
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monto">Monto del préstamo</label>
                                        <input type="number" name="monto" class="form-control" id="monto" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="interes">Tasa de interés (%)</label>
                                        <input type="number" step="0.01" name="interes" class="form-control" id="interes" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="plazo_dias">Plazo en Días</label>
                                        <input type="number" name="plazo_dias" class="form-control" id="plazo_dias" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="monto_total">Monto Total a Pagar</label>
                                        <input type="text" id="monto_total" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="monto_diario">Monto Diario a Pagar</label>
                                        <input type="text" id="monto_diario" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary" onclick="calculateTotal()">Calcular</button>
                                    <button type="button" class="btn btn-danger" onclick="clearFields()">Limpiar Campos</button>
                                    
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function calculateTotal() {
        const monto = parseFloat(document.getElementById('monto').value) || 0;
        const interes = parseFloat(document.getElementById('interes').value) || 0;
        const plazo_dias = parseFloat(document.getElementById('plazo_dias').value) || 1; // Avoid division by zero

        // Calcular el monto total a pagar
        const total = monto + (monto * (interes / 100));
        document.getElementById('monto_total').value = total.toFixed(2); // Mostrar el total con 2 decimales

        // Calcular el monto diario a pagar
        const monto_diario = total / plazo_dias;
        document.getElementById('monto_diario').value = monto_diario.toFixed(2); // Mostrar monto diario
    }

    function clearFields() {
        document.getElementById('monto').value = '';
        document.getElementById('interes').value = '';
        document.getElementById('plazo_dias').value = '';
        document.getElementById('monto_total').value = '';
        document.getElementById('monto_diario').value = '';
    }
</script>

@endsection

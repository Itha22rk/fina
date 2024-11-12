@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Editar Cliente</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-dark alert-dismissible fade show" role="alert">
                                <strong>¡Revise los campos!</strong>
                                @foreach ($errors->all() as $error)
                                    <span class="badge badge-danger">{{ $error }}</span>
                                @endforeach
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" enctype="multipart/form-data"> <!-- Agrega enctype para subir archivos -->
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                       <label for="nombre">Nombre Completo</label>
                                       <input type="text" name="nombre" class="form-control" value="{{ $cliente->nombre }}" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                       <label for="numero">Número</label>
                                       <input type="text" name="numero" class="form-control" value="{{ $cliente->numero }}" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                       <label for="direccion">Dirección</label>
                                       <input type="text" name="direccion" class="form-control" value="{{ $cliente->direccion }}" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                       <label for="ine">INE</label>
                                       <input type="file" name="ine" class="form-control">
                                       @if($cliente->ine)
                                           <small>Archivo actual: <a href="{{ asset('ruta/al/ine/'.$cliente->ine) }}" target="_blank">Ver INE</a></small>
                                       @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                       <label for="comprobante_domicilio">Comprobante de Domicilio</label>
                                       <input type="file" name="comprobante_domicilio" class="form-control">
                                       @if($cliente->comprobante_domicilio)
                                           <small>Archivo actual: <a href="{{ asset('ruta/al/comprobante/'.$cliente->comprobante_domicilio) }}" target="_blank">Ver Comprobante</a></small>
                                       @endif
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

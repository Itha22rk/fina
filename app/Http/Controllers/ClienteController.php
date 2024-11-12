<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtenemos todos los clientes sin paginación
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clientes.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validación de los campos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'ine' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'comprobante_domicilio' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Subida de archivos
        $inePath = $request->file('ine')->store('public/clientes');
        $comprobanteDomicilioPath = $request->file('comprobante_domicilio')->store('public/clientes');

        // Crear nuevo cliente con las rutas de las imágenes
        Cliente::create([
            'nombre' => $request->nombre,
            'numero' => $request->numero,
            'direccion' => $request->direccion,
            'ine' => $inePath,
            'comprobante_domicilio' => $comprobanteDomicilioPath,
        ]);

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.editar', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        // Validación de los campos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'ine' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'comprobante_domicilio' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Verificar si se actualizan las imágenes
        if ($request->hasFile('ine')) {
            // Eliminar la imagen anterior si existe
            if ($cliente->ine) {
                Storage::delete($cliente->ine);
            }
            // Guardar la nueva imagen
            $inePath = $request->file('ine')->store('public/clientes');
            $cliente->ine = $inePath;
        }

        if ($request->hasFile('comprobante_domicilio')) {
            // Eliminar la imagen anterior si existe
            if ($cliente->comprobante_domicilio) {
                Storage::delete($cliente->comprobante_domicilio);
            }
            // Guardar la nueva imagen
            $comprobanteDomicilioPath = $request->file('comprobante_domicilio')->store('public/clientes');
            $cliente->comprobante_domicilio = $comprobanteDomicilioPath;
        }

        // Actualizar los demás campos
        $cliente->update([
            'nombre' => $request->nombre,
            'numero' => $request->numero,
            'direccion' => $request->direccion,
        ]);

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        // Eliminar las imágenes asociadas si existen
        if ($cliente->ine) {
            Storage::delete($cliente->ine);
        }
        if ($cliente->comprobante_domicilio) {
            Storage::delete($cliente->comprobante_domicilio);
        }

        // Eliminar el cliente
        $cliente->delete();

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente eliminado exitosamente.');
    }
}

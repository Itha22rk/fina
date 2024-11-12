<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Prestamo;
use App\Models\Pago;
use App\Models\Multa;
use Illuminate\Http\Request;
use Carbon\Carbon;


class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        $prestamos = Prestamo::with('cliente') // Cargamos la relación con cliente
        ->select('prestamos.*')
        ->selectRaw('(select SUM(monto_diario) from pagos where prestamos.id = pagos.prestamo_id and status = 1) as total_pagado')
        ->get();

        return view('prestamos.index', compact('prestamos'));
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();  // Obtener todos los clientes
        $pagos = Pago::all();        // Obtener todos los pagos (Asegúrate de que el modelo Pago exista)

        return view('prestamos.crear', compact('clientes', 'pagos'));  // Pasar ambos a la vista

    }

    /**
     * Store a newly created resource in storage.
     */
    
    

     public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|integer',
            'monto' => 'required|numeric',
            'interes' => 'required|numeric',
            'plazo_dias' => 'required|integer',
            'fecha_inicio' => 'required|date',
            'monto_total' => 'required|numeric'
        ]);

        // Calcular el monto total y el monto diario
        $monto_total = $validatedData['monto_total'];
        $monto_pendiente = $validatedData['monto_total'];
         // Calcular el monto diario
        $monto_diario = $validatedData['monto_total'] / $validatedData['plazo_dias'];

        // Crear el préstamo
        $prestamo = Prestamo::create([
            'cliente_id' => $validatedData['cliente_id'],
            'monto' => $validatedData['monto'],
            'interes' => $validatedData['interes'],
            'plazo_dias' => $validatedData['plazo_dias'],
            'fecha_inicio' => $validatedData['fecha_inicio'],
            'fecha_vencimiento' => Carbon::parse($validatedData['fecha_inicio'])->addDays($validatedData['plazo_dias']),
            'monto_total' => $validatedData['monto_total'],
            'monto_pendiente' => $monto_pendiente,
            'estado' => false, // Inicialmente pendiente
        ]);

        // Crear los pagos
        for ($i = 1; $i <= $validatedData['plazo_dias']; $i++) {
            Pago::create([
                'prestamo_id' => $prestamo->id,
                'cliente_id' => $validatedData['cliente_id'],
                'monto_diario' => $monto_diario,
                'status' => 0, // Inicialmente no está pagado
                'fecha_vencimiento' => Carbon::parse($validatedData['fecha_inicio'])->addDays($i)->startOfDay(),
                'monto_total' => $monto_diario,
            ]);
        }

        // Actualiza el estado del préstamo a "pendiente" si no hay pagos hechos
        if ($prestamo->pagos()->where('status', 0)->count() > 0) {
            $prestamo->estado = 0;  // O el valor que desees
            $prestamo->save();
        }

        return redirect()->route('prestamos.index')->with('success', 'Préstamo creado exitosamente.');

    }


     

    /**
     * Display the specified resource.
     */
    public function show(Prestamo $prestamo)
    {
        return view('prestamos.show', compact('prestamo'));  // Mostramos los detalles de un préstamo
    }
    public function edit(Prestamo $prestamo)
    {
        $clientes = Cliente::all();  // Obtenemos todos los clientes para la vista de edición
        return view('prestamos.edit', compact('prestamo', 'clientes'));  // Retornamos una vista para editar un préstamo
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestamo $prestamo)
    {
        // Validamos los datos
        $request->validate([
            'cliente_id' => 'required|integer',
            'monto' => 'required|numeric',
            'plazo_dias' => 'required|integer|min:1',  // Plazo en días
            'interes' => 'required|numeric',
        ]);

        // Actualizamos el préstamo
        $prestamo->update($request->all());
        $prestamo->fecha_vencimiento = now()->addDays($request->plazo_dias);  // Actualizamos la fecha de vencimiento
        $prestamo->save();  // Guardamos los cambios

        return redirect()->route('prestamos.index')->with('success', 'Préstamo actualizado exitosamente.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestamo $prestamo)
    {
        $prestamo->delete();  // Eliminamos el préstamo

        return redirect()->route('prestamos.index')->with('success', 'Préstamo eliminado exitosamente.');
    }

    
    
}





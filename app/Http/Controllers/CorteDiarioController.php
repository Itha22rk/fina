<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Prestamo;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Http\Request;


class CorteDiarioController extends Controller
{
  
    public function resumenDiario()
    {
        $fecha = today()->format('Y-m-d');
        
        $clientesConPrestamo = Cliente::whereHas('prestamos', function ($query) {
            $query->where('estado', 'activo');
        })->with(['prestamos' => function ($query) {
            $query->where('estado', 'activo');
        }])->get();
    
        $corteDelDia = $clientesConPrestamo->sum(function ($cliente) {
            return $cliente->prestamos->sum('monto');
        });
    
        $montoInicial = 0; // Ejemplo de valor inicial de caja
        $prestamosActivados = Prestamo::whereDate('fecha_inicio', $fecha)->get();
        $pagosRealizados = Pago::whereDate('fecha_pago', $fecha)->sum('monto_total');
        $totalPagosPendientes = Pago::where('status', 0)->sum('monto_diario');
        $dineroEnCaja = $montoInicial + $prestamosActivados->sum('monto') - $pagosRealizados;
    
        // Nuevo: Contar el número de préstamos activos
        $numeroPrestamosActivos = Prestamo::where('estado', 'activo')->count();
    
        return view('cortes.resumen', compact(
            'fecha', 
            'clientesConPrestamo', 
            'corteDelDia', 
            'montoInicial', 
            'prestamosActivados', 
            'pagosRealizados', 
            'totalPagosPendientes', 
            'dineroEnCaja',
            'numeroPrestamosActivos' // Enviar el número de préstamos activos a la vista
        ));
    }
    
    public function resumenSemanal()
{
    $inicioSemana = Carbon::now()->startOfWeek(Carbon::MONDAY);
    $finSemana = Carbon::now()->endOfWeek(Carbon::SUNDAY);

    // Clientes con préstamos activos durante la semana
    $clientesConPrestamo = Cliente::whereHas('prestamos', function ($query) {
        $query->where('estado', 'activo');
    })->with(['prestamos' => function ($query) {
        $query->where('estado', 'activo');
    }])->get();

    // Corte de la semana: suma de los montos de préstamos activos
    $corteDeLaSemana = $clientesConPrestamo->sum(function ($cliente) {
        return $cliente->prestamos->sum('monto');
    });

    // Ajusta el monto inicial aquí según tu lógica
    $montoInicial = 7500; // Ejemplo de monto inicial de caja para el inicio de la semana
    $prestamosActivados = Prestamo::whereBetween('fecha_inicio', [$inicioSemana, $finSemana])->get();
    $pagosRealizados = Pago::whereBetween('fecha_pago', [$inicioSemana, $finSemana])->sum('monto_total');
    $totalPagosPendientes = Pago::where('status', 0)->sum('monto_diario');

    // Calculo ajustado del dinero en caja
    $dineroEnCaja = $montoInicial + $pagosRealizados - $prestamosActivados->sum('monto');

    $numeroPrestamosActivos = Prestamo::where('estado', 'activo')->count();

    return view('cortes.resumen_semanal', compact(
        'inicioSemana', 
        'finSemana', 
        'clientesConPrestamo', 
        'corteDeLaSemana', 
        'montoInicial', 
        'prestamosActivados', 
        'pagosRealizados', 
        'totalPagosPendientes', 
        'dineroEnCaja',
        'numeroPrestamosActivos'
    ));
}

    

}

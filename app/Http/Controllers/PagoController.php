<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Prestamo;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Carbon\Carbon;
use App\Models\Multa;
use Illuminate\Support\Facades\Log;

class PagoController extends Controller
{
    public function index()
    {
        // Verificar y crear multas para pagos vencidos
        $this->verificarPagos();
    
        // Pagina los resultados en lugar de obtener todos
        $pagos = Pago::with('cliente', 'multas')->paginate(10);
    
        return view('Pagos.index', compact('pagos'));
    }
    
    public function create()
    {
        $clientes = Cliente::all(); // Obtener todos los clientes
    
        // Si deseas que el formulario cargue inicialmente sin pagos, deja $pagos vacío
        $pagos = collect();
    
        return view('pagos.crear', compact('clientes', 'pagos'));
    }
    
    // Enviar los pagos del cliente seleccionado al formulario
    public function getPagosPendientes($clienteId)
    {
        // Obtener los pagos pendientes para el cliente
        $pagos = Pago::where('cliente_id', $clienteId)
            ->where('status', 0)  // Asumimos que 'status' 0 significa pendiente
            ->get();

        // Devolver los pagos en formato JSON
        return response()->json($pagos);
    }

 

    // Método para almacenar un nuevo pago
    public function store(Request $request)
    {
        // Obtener el pago seleccionado
        $pago = Pago::find($request->pago_id);
    
        // Calcular el monto total a pagar, incluyendo la multa si aplica
        $montoTotal = $pago->monto_diario;
        \Log::info('Monto Total Inicial: ', ['monto_total' => $montoTotal]);
    
        // Verificar si el pago está vencido y aplicar la multa de $100 si corresponde
        if ($pago->fecha_pago && $pago->fecha_pago->isPast()) {
            // Si ya pasó la fecha de pago, agregar la multa de $100
            $montoTotal += 100;
            \Log::info('Multa aplicada, nuevo monto total: ', ['monto_total' => $montoTotal]);
        }
    
        // Crear el registro del pago y marcar como realizado
        $pago->status = 1;  // Marcamos el pago como realizado
        $pago->monto_total = $montoTotal; // Actualizar el monto total con la multa si aplica
        $pago->fecha_pago = $request->fecha_pago;
        \Log::info('Guardando el pago con monto_total: ', ['monto_total' => $montoTotal]);
        $pago->save();
    
        // Actualizar el préstamo
        $prestamo = $pago->prestamo;
        $prestamo->monto_pendiente -= $montoTotal;
        $prestamo->save();
    
        // Actualizar el estado de la multa si existe
        if ($pago->multa > 0) {
            // Actualizamos el estado de la multa a pagada (status_multa = 1)
            $multa = Multa::where('pago_id', $pago->id)->first();
            if ($multa) {
                $multa->status_multa = 1; // Marcamos la multa como pagada
                $multa->save();
            }
        }
    
        return redirect()->route('pagos.index')->with('success', 'Pago registrado exitosamente');
    }




    public function actualizarPago(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required',
            'prestamo_id' => 'required',
            'monto_total' => 'required|numeric',
            'fecha_pago' => 'required|date',
            'fecha_vencimiento' => 'required|date',
        ]);
    
        $pago = Pago::findOrFail($id);
    
        // Actualizar los valores del pago
        $pago->update([
            'cliente_id' => $validatedData['cliente_id'],
            'prestamo_id' => $validatedData['prestamo_id'],
            'monto_total' => $validatedData['monto_total'],
            'fecha_pago' => $validatedData['fecha_pago'],
            'fecha_vencimiento' => $validatedData['fecha_vencimiento'],
        ]);
    
        // Actualiza el total pagado y monto pendiente del préstamo asociado
        $prestamo = Prestamo::findOrFail($pago->prestamo_id);
        $prestamo->total_pagado += $validatedData['monto_total'];
        $prestamo->monto_pendiente = $prestamo->monto_total - $prestamo->total_pagado;
        $prestamo->save();
    
        return redirect()->route('pagos.index')->with('success', 'Pago actualizado con éxito');
    }

    
    // Método para calcular el monto diario basado en el préstamo
    protected function calcularMontoDiario($prestamoId)
    {
        $prestamo = Prestamo::findOrFail($prestamoId);
        $interesTotal = ($prestamo->monto * $prestamo->interes / 100);
        $montoTotal = $prestamo->monto + $interesTotal;
        return $montoTotal / $prestamo->plazo_dias;
    }
    
    public function verificarPagos()
    {
        $pagos = Pago::with('prestamo')->get();
        
        foreach ($pagos as $pago) {
            \Log::info('Verificando pago: ', [
                'cliente' => $pago->cliente->nombre,
                'status' => $pago->status,
                'multa' => $pago->multa,
                'fecha_vencimiento' => $pago->fecha_vencimiento,
            ]);

            // Añadimos un día a la fecha de vencimiento para evitar problemas de aplicación de multas
            $fechaVencimientoModificada = Carbon::parse($pago->fecha_vencimiento)->addDay();
            
            // Verificamos si el pago está vencido, si no tiene multa y si está pendiente (status 0)
            if (!$pago->status && Carbon::now()->greaterThan($fechaVencimientoModificada) && $pago->multa == 0) {
                // Añadir multa
                $pago->multa = 100; // Establecemos la multa
                $pago->monto_total = $pago->monto_diario + $pago->multa; // Calculamos el monto total con multa
                $pago->status = 0; // Aseguramos que el pago esté pendiente antes de modificarlo
                $pago->save();

                // Crear la multa en la tabla 'multas'
                Multa::create([
                    'pago_id' => $pago->id,
                    'prestamo_id' => $pago->prestamo->id, // Asegúrate de que el 'prestamo_id' está correctamente asociado
                    'monto' => 100,
                    'fecha_generada' => Carbon::now(),
                ]);
        
                // Registrar el evento de creación de la multa
                Log::info('Multa creada para el pago vencido: ', [
                    'pago_id' => $pago->id, 
                    'prestamo_id' => $pago->prestamo->id, 
                    'monto' => 100
                ]);
            }
        }
    }
    
}

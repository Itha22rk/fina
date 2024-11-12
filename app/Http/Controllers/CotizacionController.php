<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function index()
    {
        return view('cotizacion.index'); // Asegúrate de que esta vista existe
    }

    public function store(Request $request)
    {
        // Valida y procesa la solicitud
        $validatedData = $request->validate([
            'monto' => 'required|numeric',
            'interes' => 'required|numeric',
            'plazo' => 'required|integer',
        ]);

        // Aquí puedes realizar la lógica de la cotización
        $monto = $validatedData['monto'];
        $interes = $validatedData['interes'];
        $plazo = $validatedData['plazo'];

        $interes_total = ($monto * $interes / 100) * $plazo;
        $total = $monto + $interes_total;

        // Redirigir o devolver la vista con los resultados
        return redirect()->route('cotizacion.index')->with('resultado', [
            'interes_total' => $interes_total,
            'total' => $total,
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; 

class Pago extends Model
{
    
    use HasFactory;
    protected $fillable = [
        'cliente_id', 'prestamo_id', 'monto_diario', 'status', 'fecha_vencimiento', 'fecha_pago', 'multa', 'monto_total'
    ]; // Incluye 'pagado'

    public function getFechaPagoAttribute($value)
    {
        return $value ? Carbon::parse($value) : null; // Convierte a Carbon o devuelve null
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }

    
    public function multas()
    {
        return $this->hasMany(Multa::class);
    }

    public function getMultaAttribute()
    {
        return $this->multas->sum('monto');
    }


    public function getMontoTotalAttribute()
    {
        return $this->monto_diario + $this->multa;
    }

}
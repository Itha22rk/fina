<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multa extends Model
{
    use HasFactory;

    protected $fillable = [
        'pago_id', 'prestamo_id', 'monto', 'fecha_generada',
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class);
    }
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }
}

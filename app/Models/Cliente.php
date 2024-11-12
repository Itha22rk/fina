<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
    protected $fillable = [
        'nombre',
        'numero',
        'direccion',
        'ine',
        'comprobante_domicilio',
    ];
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    protected $dates = ['fecha_inicio'];
    protected $fillable = [
        
        'cliente_id',
        'monto',
        'interes',
        'plazo_dias',
        'fecha_inicio',
        'fecha_vencimiento',
        'monto_total',
    ];
    public function setMontoTotalAttribute($value)
    {
        $this->attributes['monto_total'] = $value;
        $this->attributes['monto_pendiente'] = $value; // Asigna el mismo valor a monto_pendiente
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // En el modelo Prestamo
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function multas()
    {
        return $this->hasMany(Multa::class);
    }

}
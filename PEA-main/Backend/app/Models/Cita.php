<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';
    
    protected $fillable = [
        'user_id',
        'mascota_id',
        'servicio_id',
        'veterinario_id',
        'fecha',
        'hora',
        'estado',
        'observaciones',
        'razon_rechazo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function veterinario()
    {
        return $this->belongsTo(Veterinario::class, 'veterinario_id');
    }
}

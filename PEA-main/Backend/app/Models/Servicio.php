<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';
    
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'precio',
        'tipo',
        'duracion',
        'imagen',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'servicio_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veterinario extends Model
{
    protected $table = 'veterinarios';
    
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'especialidad',
        'licencia',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'veterinario_id');
    }

    public function horarios()
    {
        return $this->hasMany(HorarioVeterinario::class, 'veterinario_id');
    }
}

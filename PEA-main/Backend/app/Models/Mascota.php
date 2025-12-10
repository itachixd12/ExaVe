<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $table = 'mascotas';
    
    protected $fillable = [
        'user_id',
        'nombre',
        'especie',
        'raza',
        'edad',
        'peso',
        'descripcion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'mascota_id');
    }
}

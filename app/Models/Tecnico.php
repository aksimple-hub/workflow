<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    protected $fillable = [
        'id',
        'nombre',
        'apellidos',
        'dni_nie',
        'telefono',
        'direccion',
        'foto_perfil',
        'experiencia',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}

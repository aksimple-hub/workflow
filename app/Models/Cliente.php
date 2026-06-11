<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'dni_cif',
        'email',
        'telefono',
        'direccion'
    ];

    public function ordenes()
    {
        return $this->hasMany(OrdenTrabajo::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function usuario()
    {
        return $this->hasOne(User::class);
    }
}

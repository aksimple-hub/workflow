<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    /** @use HasFactory<\Database\Factories\OrdenTrabajoFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid', 'cliente_id', 'tecnico_id', 'titulo', 'descripcion', 'prioridad', 'estado'
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }
    public function Material() {
        return $this->hasMany(Material::class);
    }
}

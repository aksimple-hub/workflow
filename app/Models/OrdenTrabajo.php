<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    /** @use HasFactory<\Database\Factories\OrdenTrabajoFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid', 'cliente_id', 'usuario_id', 'titulo', 'descripcion', 'prioridad', 'estado', 'fecha_asignacion', 'fecha_entrega_prevista', 'observaciones', 'firma_path'
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    public function tecnico() {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    public function Material() {
        return $this->hasMany(Material::class);
    }
}

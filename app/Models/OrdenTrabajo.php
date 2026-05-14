<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    /** @use HasFactory<\Database\Factories\OrdenTrabajoFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid', 'cliente_id', 'usuario_id', 'titulo', 'descripcion', 'prioridad', 'estado',
        'fecha_asignacion', 'fecha_entrega_prevista', 'observaciones', 'recomendaciones',
        'comentario_cliente', 'satisfaccion', 'satisfaccion_tecnico', 'hora_inicio', 'hora_fin', 'firma_path',
    ];

    protected function casts(): array
    {
        return [
            'fecha_asignacion' => 'datetime',
            'fecha_entrega_prevista' => 'datetime',
        ];
    }

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

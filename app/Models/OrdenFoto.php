<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenFoto extends Model
{
    protected $fillable = ['orden_trabajo_id', 'path'];

    public function orden()
    {
        return $this->belongsTo(OrdenTrabajo::class, 'orden_trabajo_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adopcion extends Model
{
    use HasFactory;

    protected $fillable = ['publicacion_id', 'adoptante_id', 'estado_adopcion', 'motivo_cancelacion', 'estado_mascota', 'fecha_adopcion'];

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class);
    }

    public function adoptante()
    {
        return $this->belongsTo(Adoptante::class);
    }
}
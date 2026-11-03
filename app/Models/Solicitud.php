<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitud extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['publicacion_id', 'adoptante_id', 'estado', 'fecha_entrevista', 'motivo_cierre'];

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class);
    }

    public function adoptante()
    {
        return $this->belongsTo(Adoptante::class);
    }
}
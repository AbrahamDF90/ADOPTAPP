<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publicacion extends Model
{
    use HasFactory, SoftDeletes;

    // Le decimos a Laravel el nombre exacto de la tabla
    protected $table = 'publicaciones';

    protected $fillable = ['refugio_id', 'foto', 'especie', 'sexo', 'edad_aproximada', 'estado', 'motivo_baja'];

    public function refugio()
    {
        return $this->belongsTo(Refugio::class);
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }

    public function adopcion()
    {
        return $this->hasOne(Adopcion::class);
    }
}
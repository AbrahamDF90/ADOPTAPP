<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adoptante extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'dni', 'alias', 'direccion', 'telefono', 'foto'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }

    public function adopciones()
    {
        return $this->hasMany(Adopcion::class);
    }
}
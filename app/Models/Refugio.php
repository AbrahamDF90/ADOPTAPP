<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refugio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'nombre', 'ubicacion', 'direccion', 'telefono', 'activo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class);
    }
}
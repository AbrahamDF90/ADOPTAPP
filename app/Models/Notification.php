<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

/**
 * HU-25: Modelo para la tabla `notifications`.
 *
 * Patrón: MassPrunable (purga masiva) + Active Record.
 * Permite eliminar notificaciones antiguas (>90 días) de forma eficiente.
 *
 * Nota de seguridad: el `id` es un UUID y NO está en `$fillable`
 * para evitar asignación masiva. En tests se usa `forceCreate()`.
 */
class Notification extends Model
{
    use MassPrunable;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * La tabla solo tiene `created_at`, no `updated_at`.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indica si el ID es autoincremental (false porque es UUID).
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * El tipo del ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Campos asignables en masa.
     *
     * El `id` (UUID) se omite deliberadamente por seguridad.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'created_at',
    ];

    /**
     * HU-25: Define qué notificaciones son "podables" (eliminables).
     *
     * Regla de negocio: notificaciones con más de 90 días de antigüedad.
     */
    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subDays(90));
    }
}

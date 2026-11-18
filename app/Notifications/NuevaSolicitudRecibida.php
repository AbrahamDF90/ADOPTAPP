<?php

namespace App\Notifications;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * HU-23: Notificación in-app al refugio cuando recibe una nueva solicitud.
 *
 * Patrón: Notification (canal database) + Active Record.
 * Se encola con afterCommit() para que la cola no procese antes del commit de la transacción.
 */
class NuevaSolicitudRecibida extends Notification
{
    use Queueable;

    /**
     * @param  Solicitud  $solicitud  Solicitud recién creada.
     */
    public function __construct(public Solicitud $solicitud)
    {
        // HU-23: Garantiza que la notificación no se procese antes del commit de la transacción.
        $this->afterCommit();
    }

    /**
     * Canales de entrega: solo base de datos (in-app).
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Representación de la notificación para la tabla `notifications`.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'solicitud_id' => $this->solicitud->id,
            'publicacion_id' => $this->solicitud->publicacion_id,
            'adoptante_nombre' => $this->solicitud->adoptante->user->name,
            'especie' => $this->solicitud->publicacion->especie,
            'mensaje' => 'Has recibido una nueva solicitud de adopción.',
        ];
    }
}

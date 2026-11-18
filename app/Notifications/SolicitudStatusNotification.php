<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Patrón: Notification (Laravel)
 * HU-24: Aviso in-app cuando el refugio acepta o rechaza una solicitud.
 */
class SolicitudStatusNotification extends Notification
{
    use Queueable;

    protected $estado;

    protected $mensaje;

    /**
     * @param  string  $estado
     * @param  string  $mensaje
     */
    public function __construct($estado, $mensaje)
    {
        $this->estado = $estado;
        $this->mensaje = $mensaje;
    }

    /**
     * Se guarda en la base de datos (in-app).
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Datos que se guardan en la columna 'data' de la tabla notifications.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'estado' => $this->estado,
            'mensaje' => $this->mensaje,
        ];
    }
}

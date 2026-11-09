# Esquema de Base de Datos (MariaDB)

El sistema utiliza **SoftDeletes** (borrado lógico) en las tablas que requieren historial (`users`, `adoptantes`, `refugios`, `publicaciones`, `solicitudes`).

## 1. Tabla `users` (Credenciales)
* `id`, `name`, `email`, `password`, `role` (enum: admin, refugio, adoptante).
* `email_verified_at`, `created_at`, `updated_at`, `deleted_at`.

## 2. Tabla `adoptantes` (Perfil)
* `id`, `user_id` (FK a users), `dni` (único, 8 dígitos), `alias`, `direccion`, `telefono`, `foto` (ruta en storage).

## 3. Tabla `refugios` (Perfil)
* `id`, `user_id` (FK a users), `nombre`, `ubicacion` (para filtros), `direccion`, `telefono`, `activo` (boolean).

## 4. Tabla `publicaciones` (Mascotas)
* `id`, `refugio_id` (FK a refugios), `foto` (ruta), `especie`, `sexo`, `edad_aproximada`.
* `estado` (enum: activa, completa, adoptada, baja).
* `motivo_baja` (texto, si el estado es 'baja').

## 5. Tabla `solicitudes` (Flujo de Adopción)
* `id`, `publicacion_id` (FK), `adoptante_id` (FK), `estado` (pendiente, en_entrevista, aceptada, cerrada, cancelada).
* `fecha_entrevista`, `motivo_cierre`.

## 6. Tabla `adopciones` (Registro final)
* `id`, `publicacion_id` (FK), `adoptante_id` (FK).
* `estado_adopcion` (activa, cancelada), `motivo_cancelacion`.
* `estado_mascota` (adoptada, fallecida, robada, perdida).

## 7. Tabla `notifications` (Estándar Laravel)
* `id` (UUID), `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`.
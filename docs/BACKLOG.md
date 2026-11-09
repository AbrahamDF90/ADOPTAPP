# Product Backlog - ADOPTAPP (30 Historias de Usuario)

Este documento es la hoja de ruta del proyecto. 
**Leyenda de Estados:** ✅ Done (Terminado) | 🚧 In Progress (En curso) | ⏳ To Do (Pendiente)

## 🏃‍♂️ Sprint 1: Fundaciones y Autenticación
*   **HU-01 ✅ (Infraestructura):** VM Debian 13, Nginx, PHP 8.4, MariaDB. Backup diario configurado.
*   **HU-02 ✅ (Git):** Repositorio en GitHub. Ramas `main` (prod) y `develop` (dev). Estrategia GitFlow.
*   **HU-03 ✅ (Base de Datos):** MER diseñado. Migraciones creadas con SoftDeletes y llaves foráneas.
*   **HU-04 ✅ (Breeze):** Login, registro y reseteo de contraseña. SMTP Brevo configurado.
*   **HU-05 ✅ (Roles):** Middleware `RoleMiddleware` protege rutas. 3 roles: admin, refugio, adoptante.
*   **HU-06 ✅ (Registro Adoptante):** Registro extendido. Transacción en BD (User + Adoptante). Test Pest al 82%.

## 🏃‍♂️ Sprint 2: Gestión de Refugios y Publicaciones
*   **HU-09 ✅ (Alta Refugio):** Admin puede crear cuentas de refugios. (Controlador `Admin\RefugioController`).
*   **HU-07 ⏳ (Perfil Adoptante):** El adoptante puede editar su foto, alias, dirección y teléfono. (No puede editar DNI). *Ruta sugerida: `adoptante/perfil/edit`*.
*   **HU-08 ⏳ (Baja Adoptante):** El adoptante puede darse de baja (SoftDelete). Sus solicitudes pendientes se cancelan. Al volver a registrarse con el mismo DNI, recupera historial.
*   **HU-10 ⏳ (Editar/Bloquear Refugio):** Admin puede editar datos del refugio o bloquearlo (si se bloquea, sus publicaciones pasan a estado "baja").
*   **HU-11 ⏳ (Crear Publicación):** Refugio sube foto de mascota. Datos: especie, sexo, edad aprox. Estado inicial: `activa`. *Ruta: `refugio/publicaciones/create`*.
*   **HU-12 ⏳ (Editar Publicación):** Refugio edita foto o datos de su publicación.
*   **HU-13 ⏳ (Baja Publicación):** Refugio da de baja lógica una publicación con un motivo (ej: falleció).
*   **HU-14 ⏳ (Listado Público):** Visitantes ven publicaciones `activas`. Filtros: especie, sexo, ubicación. Paginación de 12.
*   **HU-15 ⏳ (Detalle Público):** Visitante ve detalle. Botón "Solicitar adopción" (solo adoptantes logueados).

## 🏃‍♂️ Sprint 3: Solicitudes de Adopción
*   **HU-16 ⏳ (Crear Solicitud):** Adoptante solicita (máx 3 por publicación). Al llegar a 3, publicación pasa a `completa`.
*   **HU-17 ⏳ (Cancelar Solicitud):** Adoptante cancela solicitud `pendiente`. Si estaba `completa`, vuelve a `activa`.
*   **HU-18 ⏳ (Ver Solicitudes Adoptante):** Adoptante ve su lista de solicitudes y su estado.

## 🏃‍♂️ Sprint 4: Flujo de Adopción y Notificaciones
*   **HU-19 ⏳ (Gestionar Solicitudes Refugio):** Refugio ve las solicitudes de sus publicaciones. Ve ficha completa del adoptante.
*   **HU-20 ⏳ (Aceptar Solicitud):** Refugio acepta. Publicación pasa a `adoptada`. Otras 2 se cierran. Crea registro en tabla `adopciones`.
*   **HU-21 ⏳ (Revertir Aceptación):** Refugio cancela adopción si no se concretó. Publicación vuelve a `completa`.
*   **HU-22 ⏳ (Estado Post-Adopción):** Refugio marca estado de mascota (fallecida, robada, etc.).
*   **HU-23 ⏳ (Notificar Refugio):** Aviso in-app cuando hay nueva solicitud.
*   **HU-24 ⏳ (Notificar Adoptante):** Aviso in-app cuando aceptan o rechazan su solicitud.

## 🏃‍♂️ Sprint 5 y 6: Panel Admin y Cierre
*   **HU-25 ⏳ (Purga Notificaciones):** Admin borra notificaciones de más de 90 días.
*   **HU-26 ⏳ (Ver Usuarios Admin):** Admin ve listado de adoptantes y refugios.
*   **HU-27 ⏳ (Ver Publicaciones Admin):** Admin supervisa publicaciones y puede restaurar dadas de baja.
*   **HU-28 ⏳ (Estadísticas):** Dashboards con métricas para Admin y Refugio.
*   **HU-29 ⏳ (Documentación Final):** Manual de usuario, UML, Gantt, etc.
*   **HU-30 ⏳ (Deploy Final):** Puesta en producción y pruebas finales.
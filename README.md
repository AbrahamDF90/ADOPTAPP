# ADOPTAPP - Sistema Web de Adopción Responsable de Mascotas

Este proyecto es una plataforma web diseñada para centralizar la adopción de mascotas, conectando refugios con potenciales adoptantes mediante un proceso transparente y controlado.

## 🛠️ Stack Tecnológico
*   **Backend:** Laravel 13 (PHP 8.4.27)
*   **Frontend:** Blade + Tailwind CSS + Vite
*   **Base de Datos:** MariaDB
*   **Servidor:** Debian 13 (VM en Proxmox) + Nginx

## 🏗️ Arquitectura y Patrones de Diseño
El sistema sigue una arquitectura **Monolito MVC** (Modelo-Vista-Controlador). Se aplicaron los siguientes patrones:
*   **MVC:** Separación de responsabilidades entre la lógica de negocio (`app/Models`), la presentación (`resources/views`) y el control de peticiones (`app/Http/Controllers`).
*   **Active Record:** Implementado a través de Eloquent ORM, donde cada tabla de la base de datos tiene su clase representativa (ej. `Publicacion::all()`).
*   **Middleware (Cadena de Responsabilidad):** Uso de `RoleMiddleware` para interceptar peticiones HTTP y validar que el usuario tenga el rol adecuado (admin, refugio, adoptante) antes de acceder a rutas protegidas.
*   **Dependency Injection:** Uso nativo de Laravel para inyectar dependencias como `Request` o servicios en los controladores, facilitando el testing.

## 📋 Soluciones Implementadas (Historias de Usuario)

### Sprint 1: Fundaciones y Autenticación

**HU-01: Infraestructura**
Se configuró una máquina virtual con Debian 13, Nginx como servidor web, PHP 8.4 y MariaDB. Se implementó un firewall (UFW) restringiendo el acceso a puertos específicos.

**HU-02 y HU-03: Base de Datos y Control de Versiones**
Se diseñó un modelo relacional normalizado. Las tablas principales (`users`, `adoptantes`, `refugios`, `publicaciones`, `solicitudes`, `adopciones`) se crearon mediante **Migraciones** de Laravel para garantizar la portabilidad del esquema. Se utilizó Git con la estrategia de ramas `GitFlow` (`main`, `develop`, `dev/feature`).

**HU-04, HU-05 y HU-06: Autenticación y Roles**
*   Se instaló **Laravel Breeze** para el scaffolding de autenticación.
*   Se extendió el registro por defecto: mediante una **Transacción de Base de Datos (`DB::transaction`)**, al registrarse un usuario, se crean sus credenciales en `users` y su perfil (DNI, foto, dirección) en `adoptantes` de forma atómica.
*   Se implementó un **Middleware de roles** para proteger las rutas según el perfil.
*   Se configuró el SMTP de Brevo (en `.env`) para el envío de correos.

### Sprint 2: Gestión de Refugios y Publicaciones

**HU-07: Edición de Perfil del Adoptante**
*   Se creó el controlador `Adoptante\PerfilController` (con métodos `edit` y `update`), respetando el principio de **Responsabilidad Única (SRP)**.
*   **Seguridad:** La regla de negocio impide modificar el DNI; el controlador solo actualiza alias, dirección, teléfono y foto.
*   **Manejo de Archivos:** Uso de `Storage::disk('public')` para guardar y reemplazar la foto de perfil en el servidor, en lugar de la base de datos.
*   **Deuda Técnica:** Si en el futuro se requiere cambiar la contraseña o email desde la misma vista, se deberá extender el `ProfileController` de Breeze o crear un Form Request específico.

**Patrones de Diseño Usados:**
1. **MVC:** Controlador (`PerfilController`), Modelo (`Adoptante`) y Vista (`edit.blade.php`).
2. **Active Record:** Uso de `$adoptante->save()` para persistir los cambios.
3. **Storage Pattern:** Abstracción del almacenamiento de archivos provista por Laravel.

**HU-09: Creación de Refugios por el Administrador**
*   **MVC (Model-View-Controller):** Se separó la lógica en un Controlador (`RefugioController`), dos Modelos (`User` y `Refugio`) y una Vista (`create.blade.php`).
*   **Transaction Script:** Se utilizó `DB::transaction()` en el método `store`. Este patrón garantiza que la creación del Usuario y del Refugio se ejecuten como una unidad atómica.
*   **Deuda Técnica:** Toda la lógica de negocio está en el Controlador (*Fat Controller*). Solución futura: implementar Patrón Service o Action.

**HU-11: Crear Publicación (Refugio)**
*   El refugio puede subir una foto y datos de la mascota.
*   **Storage Pattern:** Uso de `store('publicaciones', 'public')` para guardar la imagen en el disco.

**HU-14: Galería Pública de Mascotas**
*   Se creó el `HomeController` para manejar la página de inicio.
*   **Eager Loading:** `with('refugio')` para optimizar el rendimiento (evita el problema N+1).
*   **Paginación Nativa:** 12 ítems por página.
*   La vista se maquetó con **Tailwind CSS** usando CSS Grid (`grid-cols-1 md:grid-cols-4`).

**HU-15: Detalle de Publicación**
*   **Route Model Binding:** Captura la publicación directamente desde la URL.
*   Se aplicó una validación de seguridad en el controlador: si la publicación no está `activa`, devuelve un error 404.
*   Vista responsiva de dos columnas.

### Sprint 3: Solicitudes de Adopción

**HU-16: Creación de Solicitud de Adopción**
*   **Validaciones estrictas:** El adoptante no puede duplicar solicitudes, y una publicación no puede superar las 3 solicitudes activas.
*   **Transaction Script:** `DB::transaction` para guardar la solicitud y, si es la tercera, actualizar el estado a `completa` de forma segura.

**HU-18: Ver Solicitudes del Adoptante**
*   Se creó el `DashboardController` para el perfil del adoptante.
*   **Eager Loading:** `with('publicacion')` para cargar los datos de las mascotas solicitadas sin consultas N+1.
*   La vista utiliza **Tailwind CSS** para las tarjetas de estado.

### Sprint 4: Flujo de Adopción y Notificaciones

**HU-19: Gestión de Solicitudes por Refugio**
*   Controlador `Refugio\SolicitudRefugioController` (`index` y `show`).
*   **Seguridad:** El refugio solo puede ver las solicitudes de sus propias publicaciones (403 si intenta ver una ajena).
*   **Eager Loading:** `with(['publicacion','adoptante.user'])` para evitar consultas N+1.

**HU-20: Aceptar Solicitud de Adopción**
*   Validación de pertenencia y estados (`pendiente` o `en_entrevista`).
*   **Transaction Script:** `DB::transaction` para aceptar la solicitud, cambiar la publicación a `adoptada`, cerrar las demás y crear el registro en `adopciones`.

**HU-21: Revertir Adopción**
*   Validación de existencia de adopción `activa`.
*   **Transaction Script:** Cancela la adopción (`estado_adopcion='cancelada'`), devuelve la publicación a `completa` y la solicitud a `pendiente`.

**HU-22: Estado Post-Adopción de la Mascota**
*   Controlador `Refugio\AdopcionController@updateEstado`.
*   **Seguridad:** Solo el refugio dueño de la publicación puede cambiar el estado (403 si es ajeno).
*   **Validación:** `Rule::in()` para aceptar solo los estados permitidos.

**HU-23 y HU-24: Notificaciones In-app**
*   Clases `NuevaSolicitudRecibida` y `SolicitudStatusNotification`.
*   Canales `database` de Laravel implementados para persistencia en tabla `notifications`.
*   Vistas responsivas con Tailwind CSS.

### Sprint 5: Panel Admin y Calidad

**HU-25: Purga de Notificaciones**
*   Trait `MassPrunable` en el modelo `Notification`.
*   Programación de tarea diaria `model:prune` en `routes/console.php` (Laravel 13).

**HU-26: Listado de Usuarios (Admin)**
*   `Admin\UsuarioController` (solo lectura).
*   Eager Loading (`with('user')`) y paginación de 15 ítems.

**HU-27: Gestión de Publicaciones (Admin)**
*   `Admin\PublicacionController`.
*   **SoftDeletes:** Uso de `withTrashed()` para ver publicaciones dadas de baja y `restore()` para recuperarlas.
*   Vista con tabla, badges de estado y formularios de acción.

**HU-28: Dashboards de Estadísticas (Admin y Refugio)**
*   **Service Layer:** Creación de `EstadisticaService` (SRP) para centralizar las consultas de conteo.
*   **Dependency Injection:** Inyección del servicio en los controladores `Admin\DashboardController` y `Refugio\DashboardController`.
*   Vistas responsivas con tarjetas de métricas (Tailwind Grid).

**HU-31: Integración Continua (CI/CD)**
*   Pipeline con **GitHub Actions** (`.github/workflows/ci.yml`).
*   **Quality Gate:** Cada PR corre los tests de Pest en Ubuntu 24.04. Si los tests fallan o no se usa `RefreshDatabase`, el PR se bloquea.

---

## 🚀 Instalación en Local
1. Clonar el repositorio: `git clone ...`
2. Instalar dependencias: `composer install` y `npm install`
3. Copiar el entorno: `cp .env.example .env`
4. Generar clave: `php artisan key:generate`
5. Ejecutar migraciones: `php artisan migrate --seed`
6. Compilar frontend: `npm run build`
7. Levantar servidor: `php artisan serve`

## 🧪 Pruebas Automatizadas (Testing)
El proyecto utiliza **Pest PHP** para garantizar la calidad del código y validar las reglas de negocio.

### ¿Cómo ejecutar los tests?
Para correr **toda** la suite de pruebas (recomendado antes de hacer un commit o PR):
```bash
php artisan test
```

### Tests específicos
```bash
php artisan test --filter=NombreDelTest
php artisan test --filter=RegistroAdoptanteTest
```

### Cobertura de código
```bash
sudo apt install php8.4-pcov
php artisan test --coverage
```

## 🎨 Paleta de Colores Oficial (Branding ADOPTAPP)
El diseño visual del sistema se basa en el logotipo oficial, utilizando una interfaz con modo oscuro (Dark Mode) para resaltar los colores de la marca.

| Color | Código HEX | Clase Tailwind | Uso principal |
| :--- | :--- | :--- | :--- |
| Negro | #000000 | `bg-black` | Fondo de la barra de navegación y layouts. |
| Verde Menta | #6ee7b7 | `text-emerald-400` | Color principal de la marca, botones de acción (CTA) y enlaces. |
| Rojo Coral | #ef4444 | `text-red-500` | Alertas, errores y acentos visuales del perro. |
| Celeste | #38bdf8 | `text-sky-400` | Acentos visuales secundarios. |
| Amarillo | #fbbf24 | `text-amber-400` | Detalles y estados de advertencia. |

---

## 🤝 Guía para Nuevos Desarrolladores (Onboarding)

¡Bienvenido al equipo de ADOPTAPP! Para empezar a codificar, es **obligatorio** leer los siguientes documentos en la carpeta `/docs`:
1. `docs/ARQUITECTURA.md`: Entender el stack, los patrones de diseño y las reglas de escalabilidad (SOLID).
2. `docs/REGLAS_CODIGO.md`: Normas de comentarios y documentación.

### 🛠️ Flujo de trabajo por Historia de Usuario (HU)
Cada vez que tomes una HU de Jira/GitHub Projects, seguí este flujo estándar para no romper el sistema:
1. **Crear Rama:** `git checkout -b dev/nombre-tarea` desde `develop`.
2. **Backend (Lógica):** Crear el Controlador en `app/Http/Controllers/` y la lógica de base de datos.
3. **Frontend (Vistas):** Crear el archivo Blade en `resources/views/rol/entidad/` (ej: `admin/refugios/create.blade.php`).
4. **Rutas:** Registrar la ruta en `routes/web.php` (protegida por el Middleware correspondiente).
5. **Documentar:** Comentar el código explicando el patrón de diseño usado.
6. **Testing:** Si es lógica de negocio crítica, crear un test en Pest (`php artisan make:test --pest NombreTest`).
7. **PR:** Hacer Push y Pull Request a `develop`.

### HU-12: Edición de Publicación (Refugio)
**Solución Implementada:**
* Se añadieron los métodos `edit` y `update` al `Refugio\PublicacionController`.
* **Seguridad:** Se validó la pertenencia de la publicación (403 si el refugio intenta editar una mascota ajena).
* **Storage Pattern:** Si se sube una nueva foto, se elimina la anterior del disco para no acumular basura. Si no se sube foto, se conserva la existente.
* **Active Record:** Actualización de los campos de la publicación.

**Patrones de Diseño:**
1. **MVC:** Controlador (`PublicacionController`), Modelo (`Publicacion`), Vista (`edit.blade.php`).
2. **Active Record:** Uso de `$publicacion->save()`.
3. **Storage Pattern:** Abstracción del manejo de archivos.
4. **Route Model Binding:** Inyección automática de la `Publicacion` desde la URL.

### HU-08: Baja de Cuenta del Adoptante
**Solución Implementada:**
* Se agregó el método `destroy` al `Adoptante\PerfilController` para procesar la baja del usuario.
* **Transaction Script:** Se utilizó `DB::transaction` para garantizar la atomicidad del proceso: se cancelan las solicitudes activas del adoptante y luego se borra lógicamente su perfil y su cuenta de usuario.
* **SoftDeletes:** Se implementó el borrado lógico en los modelos `User` y `Adoptante` para no perder el historial de adopciones. Si el usuario se vuelve a registrar con el mismo DNI, el sistema podrá recuperar su historial previo.
* **Seguridad:** Se cerró la sesión del usuario (`Auth::logout()`) y se invalidó la sesión tras la baja.

**Patrones de Diseño:**
1. **SoftDeletes:** Trait de Laravel para borrado lógico.
2. **Transaction Script:** Uso de `DB::transaction` para asegurar la integridad de los datos.
3. **MVC:** Lógica en el Controlador y botón de acción en la Vista.

### HU-17: Cancelar Solicitud de Adopción
**Solución Implementada:**
* Se añadió el método `cancelar` al `Adoptante\SolicitudController`.
* **Seguridad:** Se validó que el adoptante solo pueda cancelar sus propias solicitudes (403 si intenta cancelar una ajena).
* **Regla de Negocio:** Solo se permite cancelar solicitudes en estado `pendiente`. Al cancelar, el estado cambia a `cancelada` y se registra el motivo.
* **Transaction Script:** Uso de `DB::transaction` para garantizar que, si la publicación había alcanzado el límite de 3 solicitudes (estado `completa`), al cancelar una, la publicación vuelva automáticamente al estado `activa` (liberando el cupo para otro adoptante).

**Patrones de Diseño:**
1. **Transaction Script:** Para coordinar la actualización de la solicitud y la publicación de forma atómica.
2. **Active Record:** Uso de `$solicitud->update()` y `$publicacion->update()`.
3. **MVC:** Lógica en el controlador y botón de acción en la vista del dashboard.

### HU-13: Baja de Publicación (Refugio)
**Solución Implementada:**
* Se añadió el método `destroy` al `Refugio\PublicacionController`.
* **Seguridad:** Se validó la pertenencia (403 si el refugio intenta borrar la mascota de otro).
* **Regla de Negocio:** Se solicita un "motivo de baja" antes de ocultar la publicación.
* **SoftDeletes:** La publicación no se elimina físicamente de la base de datos, sino que se marca con `deleted_at` para conservar el historial. El estado cambia a `baja`.

**Patrones de Diseño:**
1. **SoftDeletes:** Para conservar el historial y permitir restauraciones (como hace el Admin en la HU-27).
2. **Active Record:** Uso de `update()` y `delete()` sobre el modelo.

### HU-10: Editar y Bloquear Refugios (Admin)
**Solución Implementada:**
* Se añadieron los métodos `edit`, `update` y `toggleActivo` al `Admin\RefugioController`.
* **Seguridad:** Solo el administrador puede acceder a estas rutas (middleware `role:admin`).
* **Regla de Negocio:** Al bloquear un refugio, todas sus publicaciones activas cambian automáticamente a estado `baja` con el motivo "Refugio bloqueado por el administrador".
* **Transaction Script:** Uso de `DB::transaction` para garantizar que el bloqueo del refugio y la baja de sus publicaciones sean operaciones atómicas.

**Patrones de Diseño:**
1. **Transaction Script:** Para coordinar la actualización del refugio y sus publicaciones.
2. **Active Record:** Uso de `update()` sobre el modelo `Refugio` y su relación `publicaciones()`.
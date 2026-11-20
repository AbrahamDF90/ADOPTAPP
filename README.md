### HU-31: Integración Continua (CI/CD)
**Solución Implementada:**
* Se configuró un pipeline de **GitHub Actions** (`.github/workflows/ci.yml`).
* **Quality Gate:** Cada vez que se crea un Pull Request hacia `develop` o `main`, GitHub levanta un entorno Ubuntu 24.04 con PHP 8.4, instala dependencias, compila Vite y ejecuta los tests de Pest.
* **Bloqueo automático:** Si un test falla (por ejemplo, por no usar `RefreshDatabase` y no encontrar las tablas), el PR se marca en rojo y **no puede ser mergeado**, garantizando que código roto nunca llegue a producción.

### HU-25: Purga de Notificaciones
**Solución Implementada:**
* Se creó el modelo `Notification` con el trait `MassPrunable`.
* Se definió `prunable()` para eliminar notificaciones con más de 90 días de antigüedad.
* Se programó la tarea diaria `model:prune` en `routes/console.php` con `onOneServer()` y `withoutOverlapping()` (Laravel 13).
* Se agregó test Pest con `RefreshDatabase` verificando que solo se eliminan las notificaciones antiguas.

**Patrones de Diseño:**
1. **MassPrunable:** Trait nativo de Laravel para purga masiva eficiente.
2. **Active Record:** Uso de `Notification::where()` para definir la condición de poda.
3. **Task Scheduling:** Programación de tareas con `Schedule::command()` en `routes/console.php`.

**Decisiones de seguridad:**
* El `id` (UUID) de `notifications` **no está en `$fillable`** para evitar asignación masiva. En tests se usa `forceCreate()`.

**Deuda Técnica:**
* La purga es automática (diaria). Si el PM requiere un botón manual en el panel admin, se añadiría un controlador y una ruta adicionales.

### HU-26: Listado de Usuarios (Admin)
**Solución Implementada:**
* Se creó `Admin\UsuarioController` (solo lectura) con métodos `adoptantes()` y `refugios()`, sin modificar `Admin\RefugioController` (HU-09).
* **Eager Loading** (`with('user')`) para evitar el problema N+1.
* **Paginación nativa** de 15 ítems por página.
* Vistas Blade responsivas (`overflow-x-auto`) con la paleta oficial (thead negro, acentos emerald).

**Patrones de Diseño:**
1. **MVC:** Controlador (`Admin\UsuarioController`), modelos (`Adoptante`, `Refugio`) y vistas (`admin.usuarios.*`).
2. **Active Record + Eager Loading:** `Adoptante::with('user')->paginate()`.
3. **Middleware:** Rutas protegidas con `role:admin` dentro del grupo `auth` existente.

**Deuda Técnica:**
* Si se agregan filtros/búsqueda/exportación, extraer a `Admin\UsuarioService` (Service Layer).
* Botones de editar/bloquear refugio quedan para HU-10 (no se implementaron para no adelantar alcance).

### HU-28: Dashboards de Estadísticas (Admin y Refugio)
**Solución Implementada:**
* Se creó `EstadisticaService` (Service Layer) que centraliza las consultas agregadas para ambos dashboards, respetando SRP y manteniendo los controladores por debajo de las 50 líneas (docs/ARQUITECTURA.md sección 2).
* Se convirtieron los closures de `admin/dashboard` y `refugio/dashboard` en `Admin\DashboardController@index` y `Refugio\DashboardController@index`. Los nombres de ruta (`admin.dashboard`, `refugio.dashboard`) no cambiaron: los links existentes siguen funcionando.
* El refugio solo ve métricas de sus propias publicaciones (validación de pertenencia por `refugio_id`).
* Vistas Blade responsivas (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`) con tarjetas de métricas y desglose de publicaciones por estado. Se conservó el contenido original de ambas vistas (mensajes de bienvenida, `session('success')`, botones existentes).

**Patrones de Diseño:**
1. **Service Layer:** `App\Services\EstadisticaService` (nuevo, según ARQUITECTURA.md sección 2).
2. **Dependency Injection:** El service se inyecta en el constructor de los controladores.
3. **MVC:** Controladores delgados, datos en modelos, presentación en Blade.
4. **Active Record + agregados:** `count()`, `whereIn()`, `selectRaw('estado, count(*) as total')->groupBy('estado')` (agregado necesario, no SQL crudo de negocio).
5. **Middleware:** Rutas protegidas con `role:admin` / `role:refugio`.

**Decisión de diseño:**
* Se descartó crear rutas nuevas (`admin/estadisticas`) porque duplicaría puntos de entrada y dejaría un closure residual como deuda técnica. Convertir los closures a controladores elimina deuda existente y mantiene los nombres de ruta, evitando links rotos.

**Deuda Técnica:**
* No se agregaron gráficos (Chart.js). Si el PM los pide, se puede añadir un componente Blade que reciba `$porEstado` como JSON.
* Si se agregan filtros por rango de fechas, extender `EstadisticaService` con parámetros opcionales (`?Carbon $desde`, `?Carbon $hasta`).



### HU-28: Dashboards de Estadísticas (Admin y Refugio)
**Solución Implementada:**
* Se creó `EstadisticaService` (Service Layer) que centraliza las consultas agregadas para ambos dashboards, respetando SRP y manteniendo los controladores por debajo de las 50 líneas (docs/ARQUITECTURA.md sección 2).
* Se convirtieron los closures de `admin/dashboard` y `refugio/dashboard` en `Admin\DashboardController@index` y `Refugio\DashboardController@index`. Los nombres de ruta (`admin.dashboard`, `refugio.dashboard`) no cambiaron: los links existentes siguen funcionando.
* El refugio solo ve métricas de sus propias publicaciones (validación de pertenencia por `refugio_id`).
* Vistas Blade responsivas (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`) con tarjetas de métricas y desglose de publicaciones por estado. Se conservó el contenido original de ambas vistas (mensajes de bienvenida, `session('success')`, botones existentes).

**Patrones de Diseño:**
1. **Service Layer:** `App\Services\EstadisticaService` (nuevo, según ARQUITECTURA.md sección 2).
2. **Dependency Injection:** El service se inyecta en el constructor de los controladores.
3. **MVC:** Controladores delgados, datos en modelos, presentación en Blade.
4. **Active Record + agregados:** `count()`, `whereIn()`, `selectRaw('estado, count(*) as total')->groupBy('estado')` (agregado necesario, no SQL crudo de negocio).
5. **Middleware:** Rutas protegidas con `role:admin` / `role:refugio`.

**Decisión de diseño:**
* Se descartó crear rutas nuevas (`admin/estadisticas`) porque duplicaría puntos de entrada y dejaría un closure residual como deuda técnica. Convertir los closures a controladores elimina deuda existente y mantiene los nombres de ruta, evitando links rotos.

**Deuda Técnica:**
* No se agregaron gráficos (Chart.js). Si el PM los pide, se puede añadir un componente Blade que reciba `$porEstado` como JSON.
* Si se agregan filtros por rango de fechas, extender `EstadisticaService` con parámetros opcionales (`?Carbon $desde`, `?Carbon $hasta`).
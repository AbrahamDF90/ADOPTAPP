# Arquitectura y Patrones de ADOPTAPP

## 1. Stack y Estructura
* **Framework:** Laravel 13 (PHP 8.4).
* **Frontend:** Blade + Tailwind CSS (Modo oscuro/claro).
* **Base de Datos:** MariaDB.
* **Autenticación:** Laravel Breeze.
* **Roles:** Admin (creado por seeder), Refugio (creado por admin), Adoptante (registro libre).

## 2. Patrones de Diseño Obligatorios
Todo código nuevo debe alinearse a estos patrones:
* **MVC:** La lógica siempre va en el Controlador, los datos en el Modelo y la vista en Blade. NUNCA escribir consultas SQL crudas en las vistas.
* **Active Record:** Usar Eloquent (ej: `User::create()`, `Publicacion::where(...)`). No usar `DB::select()` a menos que sea estrictamente necesario.
* **Transaction Script:** Si una acción afecta a más de una tabla, usar `DB::transaction()`. Ejemplo: crear un usuario y su perfil a la vez.
* **Middleware:** Las rutas deben protegerse por roles usando `role:admin`, `role:refugio`, `role:adoptante`.
* **Service Layer (Futuro):** Si un controlador supera las 50 líneas, extraer la lógica a `app/Services/`.

## 3. Principios SOLID aplicados
* **SRP (Responsabilidad Única):** Cada clase debe hacer una sola cosa. Si el controlador hace validación + envío de correo + guardado, separar en `FormRequests` y `Notifications`.
* **OCP (Abierto/Cerrado):** Para agregar un nuevo estado (ej: una mascota "en tratamiento"), usar Enums y no modificar la estructura de la base de datos de forma destructiva.

## 4. Reglas de Escalabilidad y Seguridad
* **Validación:** SIEMPRE usar `$request->validate()` o FormRequests antes de tocar la base de datos.
* **CSRF:** Incluir `@csrf` en todos los formularios.
* **Archivos:** Las fotos van a `storage/app/public/`, NUNCA a la base de datos.
* **Responsive:** Usar clases de Tailwind `md:`, `lg:`, `flex`, `grid` para que se vea bien en celulares.
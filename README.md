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

## 📋 Soluciones Implementadas (Sprint 1)

### HU-01: Infraestructura
Se configuró una máquina virtual con Debian 13, Nginx como servidor web, PHP 8.4 y MariaDB. Se implementó un firewall (UFW) restringiendo el acceso a puertos específicos.

### HU-02 y HU-03: Base de Datos y Control de Versiones
Se diseñó un modelo relacional normalizado. Las tablas principales (`users`, `adoptantes`, `refugios`, `publicaciones`, `solicitudes`, `adopciones`) se crearon mediante **Migraciones** de Laravel para garantizar la portabilidad del esquema. Se utilizó Git con la estrategia de ramas `GitFlow` (`main`, `develop`, `dev/feature`).

### HU-04, HU-05 y HU-06: Autenticación y Roles
*   Se instaló **Laravel Breeze** para el scaffolding de autenticación.
*   Se extendió el registro por defecto: mediante una **Transacción de Base de Datos (`DB::transaction`)**, al registrarse un usuario, se crean sus credenciales en `users` y su perfil (DNI, foto, dirección) en `adoptantes` de forma atómica (si falla uno, no se guarda el otro).
*   Se implementó un **Middleware de roles** para proteger las rutas según el perfil.
*   Se configuró el SMTP de Brevo (en `.env`) para el envío de correos de verificación y reseteo de contraseña.

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

php artisan test

### test especificos

php artisan test --filter=NombreDelTest

php artisan test --filter=RegistroAdoptanteTest

sudo apt install php8.4-pcov
php artisan test --coverage

## 🎨 Paleta de Colores Oficial (Branding ADOPTAPP)
El diseño visual del sistema se basa en el logotipo oficial, utilizando una interfaz con modo oscuro (Dark Mode) para resaltar los colores de la marca.

|Color            |    Código HEX   |   Clase Tailwind   | Uso principal                                                   |
|   Negro         | #000000       | bg-black           | Fondo de la barra de navegación y layouts.                      |
|   Verde Menta   | #6ee7b7       | text-emerald-400   | Color principal de la marca, botones de acción (CTA) y enlaces. |
|   Rojo Coral    | #ef4444       | text-red-500       | Alertas, errores y acentos visuales del perro.                  |
|   Celeste       | #38bdf8       | text-sky-400       | Acentos visuales secundarios.                                   |
|   Amarillo      | #fbbf24       | text-amber-400     | Detalles y estados de advertencia.                              |


## 🏗️ HU-09: Creación de Refugios por el Administrador

### Patrones de Diseño Aplicados
1. **MVC (Model-View-Controller):** Se separó la lógica en un Controlador (`RefugioController`), dos Modelos (`User` y `Refugio`) y una Vista (`create.blade.php`).
2. **Transaction Script:** Se utilizó `DB::transaction()` en el método `store`. Este patrón garantiza que la creación del Usuario y del Refugio se ejecuten como una unidad atómica. Si algo falla, se hace un *rollback* automático, evitando datos huérfanos.
3. **Active Record:** Los modelos `User` y `Refugio` abstraen las consultas SQL, permitiendo crear registros usando métodos orientados a objetos (`User::create()`).

### Análisis de Deuda Técnica (Trade-offs)
* **Deuda Asumida:** Toda la lógica de negocio (validación y creación) está en el Controlador (conocido como *Fat Controller*).
* **Justificación:** Para un flujo CRUD simple, mantenerlo en el controlador es rápido y legible.
* **Solución Futura (Escalabilidad):** Si el alta de un refugio requiere más pasos (ej. enviar correo de bienvenida, generar un slug único, asignar plan), se deberá refactorizar aplicando el **Patrón Service** (capa de servicios) o **Action Pattern**, moviendo la lógica a una clase dedicada (ej. `CreateRefugioAction`). Esto respetará el Principio de Responsabilidad Única (SRP) de SOLID.

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

### HU-14: Galería Pública de Mascotas
**Solución Implementada:**
* Se creó el `HomeController` para manejar la página de inicio.
* Se implementó una consulta con **Eager Loading** (`with('refugio')`) para optimizar el rendimiento (evita el problema N+1 de consultas SQL).
* Se utiliza **Paginación Nativa** de Laravel (12 ítems por página) para no sobrecargar el servidor.
* La vista se maquetó con **Tailwind CSS** usando CSS Grid (`grid-cols-1 md:grid-cols-4`) garantizando que sea 100% responsiva.

**Patrones de Diseño Usados:**
1. **MVC:** Separación entre controlador (`HomeController`), modelo (`Publicacion`) y vista (`welcome.blade.php`).
2. **Active Record:** Uso de `Publicacion::with()->where()->paginate()`.

**Deuda Técnica:**
* Actualmente el botón "Ver Detalle" no redirige a ninguna parte (`href="#"`). Se resolverá en la siguiente HU (HU-15).


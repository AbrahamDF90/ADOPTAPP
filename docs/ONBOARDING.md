# 🚀 Guía de Onboarding - ADOPTAPP

Bienvenido al equipo. Esta guía es obligatoria para asegurar que todo el código cumpla con los estándares de calidad del proyecto (S.O.L.I.D., Testing, Documentación).

> 💻 **NOTA SOBRE SISTEMAS OPERATIVOS:**
> *   **Usuarios Linux (Debian/Ubuntu):** Usen la terminal nativa.
> *   **Usuarios Windows:** Se recomienda encarecidamente instalar **WSL2 (Ubuntu)** o usar **Git Bash** para ejecutar los comandos. Si usan XAMPP/Laragon, asegúrense de tener el PHP y Composer en las variables de entorno.

## 🛠️ Fase 1: Preparación del Entorno (Solo la primera vez)
1. **Clonar el repositorio:** `git clone [url-del-repo]` y entrar a la carpeta.
2. **Instalar dependencias Backend:** Ejecutar `composer install`.
3. **Instalar dependencias Frontend:** Ejecutar `npm install` (requiere Node.js 22+).
4. **Configurar el entorno:** Copiar `.env.example` a `.env` (`cp .env.example .env` en Linux/Mac/GitBash, o `copy .env.example .env` en CMD).
5. **Generar clave:** `php artisan key:generate`.
6. **Configurar Base de Datos:** En el `.env`, poner las credenciales de MariaDB local (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
7. **Migrar BD:** `php artisan migrate --seed`.
8. **Compilar Frontend:** `npm run dev` (para desarrollo) o `npm run build` (para producción).

## 📚 Fase 2: Contexto (Antes de tocar código)
9. **Leer documentación:** Es obligatorio leer `README.md`, `docs/ARQUITECTURA.md`, `docs/BASE_DE_DATOS.md` y `docs/REGLAS_CODIGO.md`.
10. **Si usás IA:** Cargar esos 4 archivos en el prompt para que la IA entienda nuestra arquitectura.
11. **Elegir tarea:** Abrir `docs/BACKLOG.md` o Jira.
12. **Crear rama:** `git checkout develop` -> `git pull origin develop` -> `git checkout -b dev/nombre-tarea`.

## 💻 Fase 3: Desarrollo
13. **Backend:** Crear Controladores/Modelos respetando el patrón MVC.
14. **Comentar código:** Explicar qué hace y qué patrón de diseño se usó (ej: *Transaction Script*).
15. **Seguridad:** Validar entradas (`$request->validate()`), usar `@csrf` y proteger rutas con Middleware.
16. **Frontend:** Usar Blade + Tailwind. Mantener el diseño responsivo y la paleta oficial (ver `docs/FRONTEND.md`).

## 🧪 Fase 4: Testing (Obligatorio)
17. **Crear Test:** `php artisan make:test --pest NombreTest`.
18. **Ejecutar tests:** `php artisan test`. **Si un test falla, no se sube el código.**

## 📝 Fase 5: Cierre y Git
19. **Documentar:** Actualizar el README explicando la HU y patrones usados.
20. **Actualizar Backlog:** Marcar la HU como ✅ en `docs/BACKLOG.md` o en Jira.
21. **Formatear:** Ejecutar `./vendor/bin/pint` (Laravel Pint).
22. **Commit y Push:** `git commit -m "feat: mensaje"` -> `git push origin dev/nombre-tarea`.
23. **Pull Request:** Crear PR hacia `develop`. GitHub Actions (CI) debe pasar en verde. Un compañero debe aprobar.
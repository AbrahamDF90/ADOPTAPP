## 📝 Descripción
[Explicar brevemente qué hace este PR]

## ✅ Checklist de Calidad (Obligatorio)
- [ ] El código está comentado explicando la lógica y el **Patrón de Diseño** usado.
- [ ] Se agregaron/actualizaron los **tests de Pest** y pasan en local (`php artisan test`).
- [ ] Se actualizó el **README.md** o la documentación en `/docs/`.
- [ ] Se actualizó el estado de la tarea en **docs/BACKLOG.md** o Jira.
- [ ] El código pasó por el formateador (`./vendor/bin/pint`).

## 🚦 Verificación del CI
- [ ] El Github Action (CI) pasó en verde.

## 📝 Descripción## 📝 Descripción
[Explicar brevemente qué hace este PR]

## ✅ Checklist de Calidad (Obligatorio)
- [ ] El código está comentado explicando la lógica y el **Patrón de Diseño** usado.
- [ ] Se agregaron/actualizaron los **tests de Pest** y pasan en local (`php artisan test`).
- [ ] Se actualizó el **README.md** o la documentación en `/docs/`.
- [ ] Se actualizó el estado de la tarea en **docs/BACKLOG.md** o Jira.
- [ ] El código pasó por el formateador (`./vendor/bin/pint`).

## 🚦 Verificación del CI
- [ ] El Github Action (CI) pasó en verde.

Implementa HU-20: el refugio puede aceptar una solicitud de adopción. Al hacerlo:
- La solicitud elegida pasa a `aceptada`.
- La publicación pasa a `adoptada`.
- Las demás solicitudes activas se cierran con motivo.
- Se crea el registro en `adopciones`.
Todo dentro de una transacción de BD (Transaction Script) para garantizar atomicidad.

## ✅ Checklist de Calidad (Obligatorio)
- [x] El código está comentado explicando la lógica y el **Patrón de Diseño** usado.
- [x] Se agregaron/actualizaron los **tests de Pest** y pasan en local (`php artisan test`).
- [x] Se actualizó el **README.md** o la documentación en `/docs/`.
- [x] Se actualizó el estado de la tarea en **docs/BACKLOG.md** o Jira.
- [x] El código pasó por el formateador (`./vendor/bin/pint`).

## 🚦 Verificación del CI
- [ ] El Github Action (CI) pasó en verde. *(se marca al pasar)*
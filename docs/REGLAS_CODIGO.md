# Reglas de Documentación de Código

Para garantizar la mantenibilidad y cumplir con los estándares académicos:

1. **Comentarios de Bloque (DocBlocks):** Todo método público debe tener un comentario `/** ... */` explicando qué hace, qué parámetros recibe y qué retorna.
2. **Explicación del Patrón:** En cada controlador nuevo, agregar un comentario indicando qué patrón de diseño se usó (ej: `/** Patrón: MVC */`).
3. **Deuda Técnica:** Si se toma un atajo por falta de tiempo, escribir un comentario `// TODO: Refactorizar usando Patrón X cuando haya tiempo`.
4. **Idioma:** Variables y tablas en español (ej: `$publicacion->estado`), pero nombres de clases y funciones de Laravel en inglés estándar.
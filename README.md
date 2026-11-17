# CyberShop — Laboratorio vulnerable (Puesta en producción segura)

Tienda online deliberadamente vulnerable, pensada como laboratorio docente para el módulo
**Puesta en producción segura** del Curso de Especialización en Ciberseguridad en
Entornos de las Tecnologías de la Información.

Stack: PHP 8.1 + MySQL 8 + HTML/CSS/JS vanilla, sin frameworks, para que las vulnerabilidades
y sus remediaciones sean directamente visibles en el código fuente.

## ⚠️ Uso responsable

Esta aplicación contiene vulnerabilidades **intencionadas y explotables** (inyección SQL,
XSS, RCE vía subida de ficheros, inyección de comandos, XXE, deserialización insegura, etc.).

- Desplegar **únicamente** en una red aislada / laboratorio (VM, red docker local, entorno
  del aula sin salida a redes de producción ni a Internet expuesto).
- No introducir datos, contraseñas ni tarjetas reales.
- No publicar el contenedor `web` en una IP accesible desde Internet.
- Uso exclusivamente educativo, en el marco de una práctica autorizada por el docente.

## Despliegue con Docker (recomendado)

Requisitos: Docker y Docker Compose.

```bash
cd tienda-vulnerable
docker compose up --build
```

Servicios:

| Servicio    | URL                     | Descripción                                   |
|-------------|--------------------------|------------------------------------------------|
| web         | http://localhost:8080    | La tienda online (CyberShop)                   |
| adminer     | http://localhost:8081    | Cliente web de MySQL (servidor: `db`)           |
| db          | localhost:3306           | MySQL (usuario `tienda_user` / `tienda_pass123`)|
| internal    | *no publicado al host*   | Servicio interno simulado, objetivo de la práctica de SSRF (solo alcanzable desde la red docker `labnet`) |

Para reiniciar la base de datos a su estado inicial:

```bash
docker compose down -v
docker compose up --build
```

## Credenciales de partida

| Usuario | Contraseña | Rol   |
|---------|-----------|-------|
| admin   | admin123  | Administrador |
| lucia   | lucia2024 | Cliente |
| marcos  | marcos2024| Cliente |

## Despliegue alternativo (XAMPP / LAMP sin Docker)

1. Copiar el contenido de `src/` a la raíz del servidor web (`htdocs` o `/var/www/html`).
2. Crear una base de datos MySQL llamada `tienda` e importar `db/init.sql`.
3. Ajustar credenciales en `src/config.php` si no coinciden con tu MySQL local.
4. Asegurar permisos de escritura en `src/uploads/` y `src/invoices/`.
5. Habilitar `Options Indexes` en la configuración de Apache para el directorio del proyecto
   (necesario para una de las prácticas de Security Misconfiguration).

## Estructura del laboratorio

- `src/` — código fuente de la aplicación vulnerable (lo que verán y auditarán los alumnos).
  El frontend está pensado para parecer una tienda online real (categorías, valoraciones,
  banners, footer corporativo, aviso legal en `src/legal.php`), pero **cada endpoint y cada
  vulnerabilidad descrita en `walkthrough.md` se mantiene exactamente igual**: solo cambia la
  presentación, no la lógica ni los nombres de campo/formulario.
- `db/init.sql` — esquema y datos de partida (catálogo, usuarios, opiniones y pedidos de
  ejemplo ampliados para dar volumen realista; los 5 primeros productos y los 3 primeros
  usuarios mantienen id/orden exactos por compatibilidad con `walkthrough.md`).
- `internal/` — micro-servicio interno usado como objetivo de la práctica de SSRF.

## Cobertura OWASP Top Ten

La práctica se plantea sobre el **OWASP Top 10:2025**. Correspondencia entre las categorías
de 2025 y los fallos presentes en el laboratorio:

| Categoría OWASP Top 10:2025 | Fallos del laboratorio |
|---|---|
| A01:2025 Broken Access Control | IDOR, path traversal, funciones de administración sin control de acceso, CSRF, SSRF (integrado en A01 desde 2025) |
| A02:2025 Security Misconfiguration | Ficheros sensibles expuestos, `phpinfo()`, listado de directorios, XXE |
| A03:2025 Software Supply Chain Failures | jQuery desactualizado cargado desde CDN sin SRI |
| A04:2025 Cryptographic Failures | Hashes MD5 sin sal, tarjetas en texto plano |
| A05:2025 Injection | SQLi, XSS (almacenado, reflejado y DOM), inyección de comandos |
| A06:2025 Insecure Design | Manipulación de precios, token de recuperación predecible, subida de ficheros sin restricciones |
| A07:2025 Authentication Failures | Credenciales por defecto, sin protección frente a fuerza bruta, fijación de sesión, política de contraseñas débil |
| A08:2025 Software or Data Integrity Failures | Deserialización insegura de la cookie "remember me" |
| A09:2025 Security Logging and Alerting Failures | Ausencia de registro y alertas (transversal) |
| A10:2025 Mishandling of Exceptional Conditions | Errores SQL y mensajes de error detallados mostrados al usuario |

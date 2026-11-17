# Instalación y uso — CyberShop (laboratorio vulnerable)

Guía paso a paso para desplegar el laboratorio, comprobar que funciona correctamente. Para el uso didáctico por unidades, ver `README.md`.

## 1. Requisitos previos

- **Docker** y **Docker Compose** (plugin `docker compose`, no el antiguo `docker-compose`).
- Tu usuario debe poder ejecutar `docker` (pertenecer al grupo `docker`, o usar `sudo`).
- Puertos libres en `localhost`: **8080** (tienda), **8081** (Adminer), **3306** (MySQL).
- Al menos 1-2 GB de RAM libres para los contenedores (MySQL + PHP + Adminer + servicio interno).

Comprobar que Docker funciona:

```bash
docker --version
docker compose version
docker ps
```

Si `docker ps` da un error de permisos (`permission denied ... docker.sock`), añade tu
usuario al grupo `docker` y vuelve a iniciar sesión:

```bash
sudo usermod -aG docker $USER
# cerrar sesión y volver a entrar (o reiniciar), luego comprobar:
docker ps
```

## 2. Instalación

1. Sitúate en la carpeta del proyecto (donde está `docker-compose.yml`):

   ```bash
   cd tienda-vulnerable
   ```

2. Construye las imágenes y levanta todos los servicios:

   ```bash
   docker compose up --build
   ```

   La primera vez tardará uno o dos minutos: construye la imagen PHP+Apache (instala las
   extensiones `mysqli` y `curl`), descarga MySQL 8 y Adminer, y carga automáticamente el
   esquema y los datos de `db/init.sql` en la base de datos `tienda`.

3. Dejar la terminal abierta (verás los logs de los tres/cuatro contenedores mezclados), o
   arrancar en segundo plano con:

   ```bash
   docker compose up --build -d
   ```

   y consultar los logs cuando quieras con:

   ```bash
   docker compose logs -f web
   ```

## 3. Comprobar que todo ha arrancado bien

```bash
docker compose ps
```

Deberías ver 4 servicios en estado `running`/`healthy`: `web`, `db`, `adminer`, `internal`.

Comprobaciones rápidas:

```bash
curl -s -o /dev/null -w "Tienda: %{http_code}\n" http://localhost:8080/index.php
curl -s -o /dev/null -w "Adminer: %{http_code}\n" http://localhost:8081
```

Ambas deberían devolver `200`. Si `web` da error 500 o no responde, revisa:

```bash
docker compose logs web
```

Abre en el navegador:

- **Tienda:** http://localhost:8080
- **Adminer:** http://localhost:8081 (sistema: `MySQL`, servidor: `db`, usuario:
  `tienda_user`, contraseña: `tienda_pass123`, base de datos: `tienda`)

## 4. Uso como cliente de la tienda

1. Entra en http://localhost:8080. Verás el catálogo de productos.
2. Puedes navegar sin cuenta (ver productos, buscar), pero para comprar u opinar necesitas
   registrarte: **Registrarse** → usuario, email, contraseña.
3. Con sesión iniciada:
   - Añade productos al carrito desde la ficha de producto (**Añadir al carrito**).
   - Revisa el carrito en **Carrito**, y pulsa **Finalizar compra**.
   - Consulta tus compras en **Mis pedidos**.
   - Dejar una opinión en la ficha de cualquier producto.
   - Editar tu perfil (email, bio) desde **Mi perfil**.
4. Si olvidas la contraseña, usa **¿Olvidaste tu contraseña?** en la pantalla de login (en
   este laboratorio el enlace de recuperación se muestra directamente en pantalla en vez de
   enviarse por email, para poder practicar sin un servidor de correo real).

Credenciales de partida ya cargadas (ver tabla completa en `README.md`):

| Usuario | Contraseña | Rol |
|---|---|---|
| admin | admin123 | Administrador |
| lucia | lucia2024 | Cliente |
| marcos | marcos2024 | Cliente |

## 5. Uso del panel de administración

1. Inicia sesión con `admin` / `admin123`.
2. En el menú superior aparecerá el enlace **Panel admin**, o accede directamente a
   http://localhost:8080/admin/index.php
3. Desde ahí puedes:
   - **Gestionar productos** — listar/filtrar productos.
   - **Gestionar usuarios** — ver todos los usuarios registrados.
   - **Subir imagen de producto** — subir un archivo (pensado para imágenes; en la práctica
     de la Unidad 2 se usa para demostrar que no valida el tipo de archivo).
   - **Importar catálogo (XML)** — subir un fichero XML de producto.
   - **Importar producto desde URL** — el servidor hace una petición HTTP a la URL indicada.
   - **Backup de base de datos** — genera un volcado SQL con `mysqldump` a partir del nombre
     de archivo indicado.

## 6. Reiniciar la base de datos a su estado inicial

Si durante las prácticas se ha "roto" algo (contraseñas cambiadas, usuarios añadidos,
productos corrompidos por inyecciones, ficheros subidos, etc.) y se quiere volver al estado
de partida:

```bash
docker compose down -v
docker compose up --build
```

`-v` elimina también el volumen de datos de MySQL, así que la próxima vez que arranque volverá
a cargar `db/init.sql` desde cero. Los ficheros subidos a `src/uploads/` **no** se borran con
esto porque esa carpeta está montada desde el host (`./src`) — si quieres limpiarla también:

```bash
find src/uploads -type f ! -name ".gitkeep" -delete
```

## 7. Detener el laboratorio

```bash
docker compose down       # detiene y elimina los contenedores, conserva los datos de MySQL
docker compose down -v    # además borra los datos de MySQL (vuelta a cero completa)
```

## 8. Despliegue alternativo sin Docker (XAMPP / LAMP)

Si en el aula no se puede usar Docker:

1. Copia el contenido de `src/` a la raíz servida por Apache (p. ej. `htdocs/` en XAMPP o
   `/var/www/html/` en un LAMP).
2. Crea una base de datos MySQL/MariaDB llamada `tienda` e importa el esquema:
   ```bash
   mysql -u root -p tienda < db/init.sql
   ```
3. Si tu usuario/contraseña de MySQL no coinciden con los de `src/config.php`
   (`tienda_user` / `tienda_pass123` / host `db`), edítalos ahí o exporta las variables de
   entorno `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` antes de arrancar PHP.
4. Da permisos de escritura a `src/uploads/` y `src/invoices/` (necesarios para las prácticas
   de subida de ficheros y backup).
5. Habilita `Options Indexes` para ese directorio en la configuración de Apache (mira
   `apache-config.conf` como referencia) — es necesario para la práctica de listado de
   directorio.
6. Asegúrate de que las extensiones PHP `mysqli`, `curl`, `dom`/`simplexml` estén activas
   (`php -m | grep -E "mysqli|curl|dom"`).

## 9. Solución de problemas comunes

| Síntoma | Causa probable | Solución |
|---|---|---|
| `permission denied` al ejecutar `docker` | tu usuario no está en el grupo `docker` | `sudo usermod -aG docker $USER` y reiniciar sesión, o usar `sudo docker compose ...` |
| El puerto 8080/8081/3306 ya está en uso | otro proceso/servicio local lo ocupa | cambia el puerto publicado en `docker-compose.yml` (p. ej. `"8090:80"`) o detén el otro servicio |
| `web` no arranca / error 500 en todas las páginas | la base de datos aún no está lista cuando arranca PHP | espera unos segundos y recarga; en despliegues lentos añade un `depends_on` con `condition: service_healthy` o reinicia solo `web`: `docker compose restart web` |
| Subida de archivos falla | permisos de `src/uploads/` | `chmod -R 777 src/uploads src/invoices` (aceptable en un laboratorio aislado, nunca en producción) |
| El SSRF a `http://internal:9000/` no responde | el contenedor `internal` no ha arrancado | `docker compose ps` y `docker compose logs internal` |
| Los datos no se resetean con `docker compose down` | falta el flag `-v` | usa `docker compose down -v` (ver sección 6) |
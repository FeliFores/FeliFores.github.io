# FF_LIBROS — Segundo Parcial Programación Web II

## Qué contiene

Sitio del Primer Parcial conservado (presentación, libro-cards, servicios, video, contacto, 404 y CSS). Evolucionado a **Opción B (administración de catálogo)**:

- MySQL: base `ff_libros`, tablas `libros` y `administradores`, y **8 libros de prueba** en `database/database.sql`.
- Conexión centralizada con MySQLi en `includes/conexion.php` y datos de conexión en `includes/config.local.php`.
- Portada con libros cargados desde MySQL (`mysqli_fetch_assoc`), buscador por `GET` y filtro por género con **consultas preparadas**.
- Cookie `genero_preferido` que recuerda el último género elegido.
- Panel protegido en `admin/` con **login + sesión + alta, edición y eliminación de libros**, con consultas preparadas.
- Mensajes flash en `$_SESSION`, validaciones del servidor, escape HTML con `htmlspecialchars` y tokens CSRF para formularios con cambios.
- Componentes `header.php`, `nav.php` y `footer.php` compartidos.

## Instalación local en XAMPP — paso a paso

1. Descomprimir `FeliFores_WebII` en `C:\xampp\htdocs\FeliFores_WebII`.
2. Abrir XAMPP y encender **Apache** y **MySQL**.
3. Abrir `http://localhost/phpmyadmin/`. Crear una base nueva llamada **`ff_libros`** con UTF8MB4.
4. Seleccionar esa base e ir a **Importar**, elegir `database/database.sql` y ejecutar. Se crearán las tablas y se importarán los **8 libros** y el administrador.
5. Copiar `includes/config.ejemplo.php` como `includes/config.local.php` y configurar host `localhost`, usuario `root`, contraseña local y base `ff_libros` según XAMPP. El archivo privado no se incluye en GitHub ni en el ZIP.
6. Abrir **`http://localhost/FeliFores_WebII/index.php`**. Allí deberían aparecer los 8 libros, con búsqueda por título y filtro de género.
7. Entrar a **`http://localhost/FeliFores_WebII/admin/login.php`**. Usuario: `admin`. La **contraseña inicial no está en este proyecto**; se compartió aparte para no publicarla. El panel te obligará a cambiarla al entrar por primera vez.
8. Probar: crear libro, editar título, eliminar libro de prueba, navegar y verificar que el filtro y la cookie se conservan.

## Subida a GitHub e InfinityFree

- **GitHub es solo el repositorio del código**. GitHub Pages **no ejecuta PHP**; la web PHP/MySQL debe alojarse en un servidor como InfinityFree.
- Subí la carpeta del proyecto al repositorio, con commits que muestren el progreso. `.gitignore` evita subir `includes/config.local.php`.
- En InfinityFree creá la base MySQL que te asigne el hosting, y **seleccioná esa base en phpMyAdmin del hosting** para importar `database/database.sql`. No intentes crear una base con otro nombre mediante el script.
- Subí los archivos web al `htdocs` de InfinityFree (si la web está en la raíz, subí el contenido de `FeliFores_WebII`, no necesariamente la carpeta). **Subí también `includes/config.local.php` de forma privada**, editándolo con el host, nombre de base, usuario, contraseña y puerto indicados por InfinityFree. No publiques nunca esas credenciales en GitHub.
- Entrá al panel y cambiá la contraseña inicial **en ambos entornos** si usás bases de datos diferentes.
- Verificá que listado, búsqueda, filtros, login, CRUD, imágenes y formularios funcionen en el dominio público.
- `enlaces.txt` contiene las URL reales de GitHub e InfinityFree; entregá el ZIP con el nombre pedido por la consigna.

## Seguridad / notas

- No compartas la contraseña inicial. Si `database.sql` se importa en un servidor público, cambiá la contraseña inmediatamente tras iniciar sesión.
- Al importar nuevamente el SQL sobre una base ya poblada, se duplicarán los libros o fallará la inserción del admin. Usá una **base nueva** al importar desde cero; realizá backups antes de modificar datos reales.
- Las imágenes nuevas se agregan manualmente en `imagenes/`; el formulario de libros usa un selector de imágenes existentes y no permite subidas remotas.
- La página de confirmación del formulario de contacto **muestra** los datos validados; no envía correos ni los guarda en la base de datos.
- Para la entrega final de la materia hará falta integrar también el módulo completo de registro y login de usuarios si continúa la misma consigna.

## Archivos principales

- `index.php`: catálogo, búsqueda y preferencias.
- `admin/login.php`, `admin/index.php`, `admin/libro.php`, `admin/eliminar.php`, `admin/cambiar_clave.php`, `admin/salir.php`.
- `includes/bootstrap.php`: sesión, CSRF, funciones de escape y flash.
- `includes/conexion.php`: MySQLi centralizado.
- `includes/config.local.php`: configuración local de desarrollo (no Git).
- `database/database.sql`: tablas y registros de prueba.
- `enlaces.txt`: enlaces definitivos del repositorio y del sitio en InfinityFree.

## Enlaces del segundo parcial

- **Repositorio**: https://github.com/FeliFores/FeliFores.github.io
- **Sitio online (PHP + MySQL)**: https://ff-libros.42web.io/
- **Panel de administración**: https://ff-libros.42web.io/admin/login.php
- **Entrega**: `26-ACN2BV-FORESTELLO-FELIPE-PARCIAL2.ZIP` (incluye `database/database.sql`, sin credenciales privadas de MySQL).

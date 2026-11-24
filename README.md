# BookManager

Sistema de gestion de libreria desarrollado con Programacion Orientada a Objetos en PHP y MySQL. Permite administrar el inventario de libros (titulos) y la red de tiendas, con una interfaz basada en la plantilla SB Admin 2.

**URL publica:** https://milibreria.infinityfree.io/

**Autor:** Joel Villegas Medina  
**Materia:** Lab. Lenguaje de Programacion  3
**Proyecto final**

## Tecnologias

- PHP 8.x (orientado a objetos)
- MySQL / MariaDB con PDO y sentencias preparadas
- Bootstrap (plantilla SB Admin 2)
- Despliegue en InfinityFree

## Funcionalidades

- CRUD completo de **Tiendas** (crear, listar, editar, eliminar).
- CRUD completo de **Titulos**, con selector de editorial cargado desde la tabla `publicadores`.
- Validaciones en PHP (nombre de tienda no vacio, precio no negativo, ID unico, fecha valida, editorial existente).
- Menu lateral funcional entre Inicio, Tiendas y Titulos.
- Mensajes de confirmacion y de error en cada operacion.

## Estructura del proyecto

```text
bookmanager/
 ├── assets/        CSS, JS e imagenes de la plantilla
 ├── config/
 │    ├── database.php          Clase Database (conexion PDO)
 │    └── config.example.php    Plantilla de credenciales
 ├── database/
 │    └── dblibreria.sql        Script de la base de datos
 ├── includes/
 │    ├── init.php              Arranque comun y funciones de ayuda
 │    ├── header.php            Cabecera y menu lateral
 │    └── footer.php            Pie de pagina y scripts
 ├── models/
 │    ├── Tienda.php            Modelo de tiendas
 │    └── Titulo.php            Modelo de titulos
 ├── views/
 │    ├── tiendas/              index, crear, editar, eliminar
 │    └── titulos/              index, crear, editar, eliminar
 └── index.php                  Pagina de inicio
```

## Arquitectura POO

- **Database**: encapsula la conexion PDO con propiedades privadas y la entrega mediante `getConnection()`.
- **Tienda y Titulo**: modelos con propiedades privadas, constructor que recibe la conexion y metodos `getAll()`, `getById()`, `create()`, `update()`, `delete()` y `validar()`.
- **Seguridad**: todas las consultas usan sentencias preparadas y las salidas HTML se escapan con `htmlspecialchars` (funcion `e()`).
- **Vistas**: reutilizan `header.php` y `footer.php`, y cargan el modelo correspondiente.

## Instalacion local

1. Instalar XAMPP (o similar) e iniciar Apache y MySQL.
2. Copiar el proyecto en `C:\xampp\htdocs\bookmanager`.
3. En phpMyAdmin crear la base `dblibreria` e importar `database/dblibreria.sql`.
4. Copiar `config/config.example.php` como `config/config.local.php` y ajustar las credenciales. Para uso local:

   ```php
   <?php
   return [
       'host'     => 'localhost',
       'dbname'   => 'dblibreria',
       'user'     => 'root',
       'pass'     => '',
       'charset'  => 'utf8mb4',
       'base_url' => '/bookmanager',
   ];
   ```

5. Abrir `http://localhost/bookmanager/`.

## Despliegue en InfinityFree

1. Crear la cuenta de hosting y la base de datos MySQL desde el panel.
2. Importar `database/dblibreria.sql` en el phpMyAdmin remoto.
3. Crear `config/config.local.php` en el servidor con las credenciales remotas y `base_url` vacio (`''`).
4. Subir el proyecto a la carpeta `htdocs`.

## Nota sobre las credenciales

El archivo `config/config.local.php` esta excluido del repositorio mediante `.gitignore` para no exponer contraseñas. Cada entorno (local y produccion) debe crear el suyo a partir de `config/config.example.php`.
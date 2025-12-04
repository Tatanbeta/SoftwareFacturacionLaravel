# Sistema de Facturación

Prueba técnica desarrollada con Laravel y MySQL. El sistema permite gestionar facturas y clientes.

## Tecnologías utilizadas

-   Laravel 12.1
-   PHP 8.2
-   MariaDB 10.4.27
-   Bootstrap 5
-   Blade templates
-   React
-   DOMPDF
-   Vite.js
-   API RESTful

## Requisitos previos

-   PHP 8.2
-   Composer
-   MySQL 8+ o MariaDB 10.4+
-   Node.js y NPM (opcional, solo para el demo de React)

## Instalación

### 1. Clonar el repositorio

    -bash
    git clone <url-del-repositorio>
    cd facturacion

### 2. Instalar dependencias de PHP

    -bash
    composer install

### 3. Configurar el archivo de entorno

Copiar el archivo de ejemplo:

    -bash
    cp .env.example .env

Editar el archivo `.env` y configurar la base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=facturacion
DB_USERNAME=root
DB_PASSWORD=(si tiene contraseña, sino en blanco)
```

### 4. Generar la clave de la aplicación

    -bash
    php artisan key:generate

### 5. Crear la base de datos

Opción A: Importar el script SQL incluido

    -bash
    # Importar el archivo BD/prueba_facturacion.sql desde phpMyAdmin
    # o desde la línea de comandos:
    mysql -u root -p facturacion < BD/prueba_facturacion.sql

Opción B: Usar las migraciones de Laravel

    -bash
    php artisan migrate

### 6. Iniciar el servidor de desarrollo

    -bash
    php artisan serve

La aplicación estará disponible en: `http://localhost:8000`

### 7. (Opcional) Ejecutar el demo de React

Si deseas probar el demo de React:

    -bash
    cd frontend-react
    npm install
    npm run dev

El demo estará disponible en: `http://localhost:3000`

## Funcionalidades implementadas

### Gestión de Clientes

-   Crear, editar, listar y eliminar clientes
-   Validación de diferentes campos

### Gestión de Facturas

-   Crear facturas con múltiples items
-   Tipos de factura: Contado y Crédito
-   Para facturas a crédito, el vencimiento se calcula automáticamente a 30 días
-   Cálculo automático de IVA (19%)
-   Cálculo de subtotales y totales en tiempo real

### Listado y Filtros

-   Tabla paginada de facturas
-   Filtros por:
    -   Rango de fechas
    -   Tipo de factura (Contado/Crédito)
    -   Cliente (búsqueda por cédula o nombre)

### API RESTful

La aplicación incluye una API REST para facturas y clientes.

**Endpoints de Facturas:**

-   GET `/api/invoices` - Listar facturas (con filtros opcionales)
-   GET `/api/invoices/{id}` - Ver detalle de una factura
-   POST `/api/invoices` - Crear factura
-   DELETE `/api/invoices/{id}` - Eliminar factura

**Endpoints de Clientes:**

-   GET `/api/personas` - Listar clientes
-   GET `/api/personas/{id}` - Ver detalle de un cliente
-   POST `/api/personas` - Crear cliente
-   PUT `/api/personas/{id}` - Actualizar cliente
-   DELETE `/api/personas/{id}` - Eliminar cliente

## Demo de React (Opcional)

Incluí un pequeño demo de React en la carpeta `frontend-react/` que consume la API. Es solo para demostrar que la API funciona correctamente.

## Estructura de la base de datos

Toda la estructura de la base de datos se encuentra en la carpeta llamada BD en la cual esta el script de creacion de la base de datos.
Adjunto voy a subir el SQL completo con los datos de la base de datos de prueba en la que estoy trabajando.

## Notas sobre la implementación

La aplicación está construida principalmente con Laravel, usando Blade templates y Bootstrap.

Aunque la prueba incluía React, mi experiencia con esta tecnología es básica. Por eso, dentro de la carpeta frontend-react/ encontrarás un pequeño demo funcional que consume la API del proyecto y muestra cómo se integraría React.

El resto de la aplicación funciona de manera completa con Laravel y está lista para probar.

## Autor

Ing Jhonatan Betancourt Bedoya.
Desarrollado como prueba técnica.

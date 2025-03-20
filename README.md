#Carrito de Compra Amplifica

Este proyecto es una integración con el servicio de Amplifica para gestionar un carrito de compras.

## Requisitos Previos

- PHP >= 8.1
- Composer
- Node.js y NPM
- MySQL o PostgreSQL
- Git

## Pasos de Instalación

### 1. Clonar el Repositorio

$git clone https://github.com/c0venn/laravel-envios.git

### 2. Instalar Dependencias

$ cd /proyecto-envios

$composer install

$npm install

### 3. Configuración del Entorno

Actualiza el archivo .env con las credenciales de tu base de datos
Luego, ejecuta las migraciones para crear las tablas de la base de datos

### 4. Ejecutar la Aplicación

Abre 2 terminales y ejecuta dentro de la carpeta raíz del proyecto los siguientes comandos:

Laravel/proyecto-envios>$ npm run dev 
<!-- FrontEnd -->
Laravel/proyecto-envios>$ php artisan serve 
<!-- BackEnd -->

El servidor pondrá a disposición la ruta de enlace http://127.0.0.1:8000/


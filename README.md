<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## ⚙️ Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/xJarucL/opiniones-encuestas.git
cd nombre-repositorio-clonado
```

## 2. Instalar dependencias

Para poder ejecutar el proyecto deben estar instaladas las dependencias necesarias. Ejecuta:

```bash
composer install
```

## 3. Configurar el entorno (.env)

Copia el archivo de entorno de ejemplo y genera la clave de aplicación:

```bash
php artisan key:generate
```

## 4. Crear base de datos

Crea en tu sistema gestor de base de datos la base indicada en el archivo .env. Asegúrate de que el nombre, usuario y contraseña coincidan.

## 5. Ejecutar migraciones

Con la base de datos creada, ejecuta las migraciones para que se generen las tablas necesarias:

```bash
php artisan migrate
```

## 6. Ejecutar seeders

Hay valores por defecto que debe tener la base de datos, definidos en los seeders. Ejecuta:

```bash
php artisan db:seed
```

### 7. Instalar TailWind (Se ocupa tener Node.js instalado)

```bash
npm install tailwindcss @tailwindcss/vite
```


### 8. Entorno en vite (tailwind)

Ejecutamos el entorno para que cada cambio en código se actualice al instante y se vea reflejado en nuestro servidor ejecutado.

```bash
npm run dev
```


### 9. Ejecutar el proyecto 

Finalmente, ejecuta el servidor local de desarrollo:

```bash
php artisan serve
```

El proyecto estará disponible en: http://localhost:8000

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

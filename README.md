# URL Shortener

A Laravel 12 URL shortener with role-based access control.

## Requirements

- PHP 8.2+
- Composer

## Setup

1. Install dependencies

```bash
composer install
```

2. Create your `.env` file and generate app key

```bash
php artisan key:generate
```

3. Create the mySQL database `url_shortener` in local phpmyadmin.

4. Run migrations

```bash
php artisan migrate
```

5. Seed the database

```bash
php artisan db:seed
```

6. Start the server

```bash
php artisan serve
```

Open `http://localhost:8000` or Your exposed host url.

## Default Super Admin

| Field    | Value                  |
|----------|------------------------|
| Email    | superadmin@gmail.com   |
| Password | password               |

## Reset Database

```bash
php artisan migrate:fresh --seed
```
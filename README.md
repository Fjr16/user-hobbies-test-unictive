# Laravel 13 JWT Auth

Aplikasi Laravel 13 dengan Blade + Bootstrap 5, dengan autentikasi API berbasis JWT menggunakan package [`php-open-source-saver/jwt-auth`](https://github.com/PHP-Open-Source-Saver/jwt-auth) (namespace `PHPOpenSourceSaver\JWTAuth`).

## Tech Stack

- Laravel 13
- PHP 8.3+
- Bootstrap 5 (via CDN)
- JWT: `php-open-source-saver/jwt-auth`
- MySQL / PostgreSQL

## Persyaratan

| Kebutuhan | Versi |
|-----------|-------|
| PHP | 8.3 - 8.5 |
| Composer | 2.x |
| Node.js | 20.19+ atau 22.12+ (LTS terbaru disarankan) |
| NPM | Mengikuti Node.js |
| Database | MySQL 8+ / MariaDB 10.6+ / PostgreSQL 13+ |


## Instalasi

### 1. Clone repository

```bash
git clone https://github.com/username/nama-repo.git
cd nama-repo
```

### 2. Install dependensi PHP

```bash
composer install
```

### 3. Salin file environment

```bash
cp .env.example .env
```

### 4. Atur koneksi database di `.env`

MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nama_database
DB_USERNAME=postgres
DB_PASSWORD=
```

Buat database kosong dengan nama yang sama sebelum lanjut.

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Generate JWT secret

```bash
php artisan jwt:secret
```

Perintah ini menambahkan `JWT_SECRET` ke file `.env`.

### 7. Jalankan migrasi (dan seeder jika ada)

```bash
php artisan migrate
php artisan db:seed
```

### 8. Install dependensi

```bash
npm install
```

### 9. Jalankan server

```bash
# Terminal 1: Laravel
php artisan serve

## Lisensi
[MIT](LICENSE)
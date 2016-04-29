# Disaster Management System

## Installation

1. Install php, composer, postgresql
2. Run `composer install`
3. Create database with name `dimas`
4. Copy paste `.env.example`, rename as `.env`
5. Edit `DB_USERNAME` and `DB_PASSWORD` based on postgresql configuration
6. Run `php artisan migrate` to run migration
7. (Optional) Run `php artisan migrate:reset` to drop all tables
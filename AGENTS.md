# Project Instructions — BatchLinks (Laravel)

## Overview

This is a fresh Laravel application. Treat it as a standard Laravel 11+ project using the modern Laravel structure (no `Http/Middleware` boilerplate unless needed, slim `app/` tree).

## Tech Stack

- **Framework:** Laravel (latest stable)
- **Language:** PHP 8.3+
- **Database:** MySQL / MariaDB (configured via `.env`)
- **Frontend build:** Vite
- **CSS framework:** Bootstrap 5
- **Testing:** PHPUnit / Pest (use whichever is installed)
- **Package manager:** Composer (PHP), npm (Node)

## Conventions

### PHP / Laravel

- Follow PSR-12.
- Prefer type hints and return types on all methods.
- Use Laravel’s validation layer for HTTP input — no manual validation in controllers.
- Keep controllers thin; business logic belongs in service classes or action classes under `app/Services` or `app/Actions`.
- Use Eloquent models; prefer query scopes over repeated query logic.
- Use migrations for schema changes; never edit production DB directly.
- Use route model binding where possible.
- Write resourceful controllers when the action maps to REST verbs.

### Naming

- Controllers: singular noun + `Controller`, e.g. `LinkController`
- Models: singular PascalCase, e.g. `Link`
- Migrations: `YYYY_MM_DD_HHMMSS_create_<table>_table.php`
- Blade views: kebab-case, nested by resource, e.g. `resources/views/links/index.blade.php`
- Routes: `routes/web.php` for web, `routes/api.php` for API

### Frontend

- Use Vite asset helpers: `@vite(['resources/css/app.css', 'resources/js/app.js'])`
- Keep JS minimal; prefer Bootstrap's JavaScript components or vanilla JS unless a larger SPA is requested.
- Bootstrap 5 utility classes only; avoid custom CSS unless necessary.

### Testing

- Write tests for every new feature.
- Prefer feature tests hitting real HTTP endpoints.
- Use factories for seeding test data.
- Run tests with: `php artisan test` or `vendor/bin/pest`

### Security

- Never commit `.env` files.
- Use Laravel’s authorization (gates/policies) for resource access.
- Sanitize output in Blade with `{{ }}` by default.
- Validate and type-hint all user input.

## Commands to Know

```bash
# Install PHP deps
composer install

# Install JS deps
npm install

# Run dev server
php artisan serve
npm run dev

# Run tests
php artisan test

# Make a model + migration + factory
php artisan make:model Link -mf

# Make a controller
php artisan make:controller LinkController --resource

# Run migrations
php artisan migrate

# Fresh migrate with seeders
php artisan migrate:fresh --seed
```

## Communication Style

- Be concise.
- Explain the “why” only when it is non-obvious.
- Always show exact file paths for created/modified files.
- Prefer dedicated tools (`read`, `edit`, `write`, `grep`) over shell commands for file operations.
- Run `php artisan test` after meaningful changes.

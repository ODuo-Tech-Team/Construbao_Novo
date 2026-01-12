# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Construbao is a PHP 7.4+ CMS for managing equipment rentals and standard pole sales. It uses vanilla PHP with PDO for database access, no frameworks or package managers. Served by Apache with mod_rewrite.

## Development Commands

```bash
# Start local server (requires PHP installed)
php -S localhost:8000

# Import database schema
mysql -u root -p construbao < database/schema.sql
```

No build, lint, or test commands exist - this is a traditional PHP project served directly by Apache.

## Architecture

### Core Files (includes/)

- **config.php** - Loads `.env`, defines constants (SITE_URL, DB_*, UPLOAD_PATH). Auto-detects site URL.
- **database.php** - PDO singleton with helper functions: `fetchAll()`, `fetchOne()`, `fetchById()`, `insert()`, `update()`, `delete()`, `query()`
- **auth.php** - Session-based auth with bcrypt. Key functions: `login()`, `logout()`, `isLoggedIn()`, `isAdmin()`, `requireLogin()`, `requireAdmin()`
- **functions.php** - Utilities: `e()` (XSS escape), `slugify()`, `uploadImage()`, `csrfField()`, `setFlash()`, `redirect()`

### Request Flow

1. Apache routes via `.htaccess` (clean URLs, removes .php extension)
2. Page includes `includes/config.php` which auto-loads `.env` and starts session
3. Admin pages call `requireLogin()` or `requireAdmin()` middleware
4. Forms validated with CSRF token via `csrfField()` / `validateCsrfToken()`
5. Database ops use parameterized queries via helper functions
6. Output escaped with `e()` function

### Admin Panel (admin/)

Each module follows identical CRUD pattern:
```
admin/{module}/
├── index.php    # List with search/pagination
├── criar.php    # Create form
├── editar.php   # Edit form (GET ?id=)
└── excluir.php  # Delete handler (GET ?id=)
```

Modules: `usuarios`, `equipamentos`, `postes`, `blog`, `depoimentos`, `categorias-equip`, `categorias-blog`

### Database

MySQL with 7 tables. Key relationships:
- `blog_posts` → `categorias_blog` (FK), `usuarios` (FK author)
- `equipamentos` → `categorias_equipamentos` (FK)
- Standalone: `postes`, `depoimentos`, `configuracoes`

All tables use `ativo` (tinyint) for soft-enable, `ordem` (int) for sorting.

### User Levels

- `admin`: Full access including user management
- `editor`: Content access only, no user management

### Blog Post Status

- `rascunho`: Draft, admin-only visible
- `publicado`: Published publicly
- `agendado`: Scheduled for future publication

### File Uploads

`uploadImage($file, $folder)` validates MIME type, enforces 2MB limit, generates unique filename. Stores in `/uploads/{folder}/`. Use `imageUrl($path)` to generate URLs.

## Key Patterns

```php
// Always escape output
<?= e($user['nome']) ?>

// CSRF in forms
<form method="POST">
    <?= csrfField() ?>
</form>

// Validate CSRF
if (!validateCsrfToken(post('csrf_token'))) { ... }

// Database queries - always parameterized
$user = fetchOne('usuarios', 'email = ?', [$email]);
$items = fetchAll('equipamentos', 'ativo = 1', [], 'ordem ASC');

// Flash messages
setFlash('success', 'Saved!');
redirect('/admin/equipamentos/');
```

## Environment

Copy `.env.example` to `.env`. Key variables:
- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` - Database connection
- `SITE_URL=auto` - Auto-detects site URL
- `APP_ENV=development|production` - Environment mode
- `APP_DEBUG=true|false` - Show errors
- `WHATSAPP_NUMBER` - WhatsApp floating button number

## Admin Access

Default login: `admin@construbao.com.br` / `admin123` (change immediately in production).

## Features

- **SEO Analyzer** (`admin/assets/js/seo-analyzer.js`): Real-time SEO scoring for blog posts based on title, description, focus keyword, and content
- **Quill.js Editor**: Rich text editing for blog posts (loaded via CDN)
- **WhatsApp Integration**: Floating button configured via `.env`

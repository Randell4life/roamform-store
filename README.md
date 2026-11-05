# ROAMFORM

**Travel Better. Live Organized.**

ROAMFORM is an independent Laravel storefront for a curated collection of travel and home-organization essentials. The project is being built as a custom PHP/Laravel application, with Filament used for private store administration.

## Requirements

- PHP 8.3+
- Composer
- Node.js and npm
- SQLite or MySQL

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure the database connection in `.env`. For SQLite, create the database file if it does not exist:

```bash
# Windows PowerShell
New-Item -ItemType File -Force database/database.sqlite
```

Then run:

```bash
php artisan migrate
npm install
npm run build
php artisan serve
```

Open the storefront at `http://127.0.0.1:8000`.

## Admin panel

The Filament admin panel is available at `/admin`. Register a normal account first, then explicitly grant admin access to that account from the project directory:

```bash
php artisan tinker
```

In Tinker, replace the email with the account you registered:

```php
App\\Models\\User::where('email', 'your-email@example.com')->update(['is_admin' => true]);
```

Only users with `is_admin = true` can access the admin panel. New accounts are not administrators by default.

The Products section supports creating, editing, publishing, unpublishing, searching, and deleting products. A product is shown on the public storefront only when **Published on storefront** is enabled.

## Current scope

- Public storefront and product detail pages
- Product database and migrations
- Filament admin authentication and product management
- Supplier reference fields for future dropshipping integration

Checkout, payment processing, and supplier fulfillment are intentionally not enabled yet. Payment-provider eligibility for a Georgia-based business and supplier shipping economics must be verified before accepting customer payments.

## Security

- Never commit `.env`, database files, `vendor/`, or `node_modules/`.
- Keep new user accounts non-admin by default.
- Use HTTPS and production-grade secrets before deployment.

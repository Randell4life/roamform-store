# ROAMFORM

**Travel Better. Live Organized.**

Independent Laravel storefront for curated travel and home-organization essentials.

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
npm run build
php artisan serve
```

Configure SQLite or MySQL in `.env`. For SQLite create `database/database.sqlite` before migrating.

## Admin panel

Open `/admin`. Create the first administrator with Tinker:

```bash
php artisan tinker
```

```php
$user = App\Models\User::create([
    'name' => 'Store Admin',
    'email' => 'admin@example.com',
    'password' => 'change-this-password',
]);
$user->forceFill(['is_admin' => true])->save();
```

## Catalog and inventory

- **Catalog → Categories:** create, edit, order, activate, and deactivate categories.
- **Catalog → Products:** assign categories, upload product images, manage SKU, prices, stock quantity, and low-stock thresholds.
- Supplier name, supplier product ID, and supplier product URL are available for dropshipping records.
- Low-stock products are highlighted in the product list.
- Published products and active categories appear on the storefront.
- Product images use Laravel's public storage disk; run `php artisan storage:link` once.

Stock is currently managed manually in the admin panel. Automatic stock deduction is not enabled until checkout and payment processing are implemented.

Checkout, payment processing, and supplier fulfillment remain disabled until payment-provider eligibility for a Georgia-based business and supplier economics are verified.

Never commit `.env`, database files, `vendor/`, or `node_modules/`.

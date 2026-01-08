# Bullet Ticket

Mobile-first event booking with per-fan caps, QR tickets, and role-based controls for admins, organizers, and fans.

## Features
- Role-based access: admin/organizer manage events and reports; fans browse/book.
- Booking guardrails: 2-ticket cap per fan per event; transactional stock decrements.
- QR tickets: per-ticket SVG QR stored on public disk; single-use validation endpoint.
- Reporting: sold vs remaining capacity table + CSV export; quick chart.
- Modern UI: minimal, role-aware nav and pages (dashboard, events, tickets, reports).

## Stack
- Laravel 10 (PHP 8.1+), MySQL
- Tailwind via Vite
- simplesoftwareio/simple-qrcode (SVG driver, no Imagick required)

## Setup
```bash
cp .env.example .env
php artisan key:generate
composer install
npm install
npm run build          # or npm run dev
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Roles & Seed Users
- Organizer: organizer@bullet.ly / organizer123 (see Database\Seeders\OrganizerSeeder.php)
- Create additional admins/fans via registration or seeders.

## Usage
### Web
- Events index: /events (public browse; create/edit only admin/organizer)
- Dashboard: /dashboard (authenticated; role badge + shortcuts)
- Book: submit tickets_count (1–2) to POST /events/{event}/book
- My Tickets: /my-tickets (QR list, download SVG)
- Reports: /reports (admin/organizer)

### API: Validate Ticket (admin/organizer via Sanctum)
```bash
curl -X POST \
	-H "Authorization: Bearer <SANCTUM_TOKEN>" \
	-H "Content-Type: application/json" \
	-d '{"qr_code":"<ticket-code>"}' \
	https://your-app.test/api/tickets/validate
```
Response (valid): `{ "status": "valid", "ticket_id": 1, "event": { ... } }`
Errors: 422 with messages for used/expired/not found.

### Booking (web form)
- Form posts `tickets_count` to `/events/{event}/book`; enforces per-fan cap and remaining stock.

## Configuration
- QR saved as SVG to `storage/app/public/qr_codes/<code>.svg` (requires `php artisan storage:link`).
- Role middleware `role:admin,organizer` guards event management and reports; bookings require auth.

## Troubleshooting
- Styling not updating: `php artisan view:clear && php artisan cache:clear`; ensure Vite build/dev running.
- Imagick error: not used; SVG driver avoids Imagick. If you switch to PNG, install the Imagick PHP extension.

## Deployment
```bash
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```
Set `APP_URL`/`ASSET_URL` appropriately and serve the `public` directory via your web server.

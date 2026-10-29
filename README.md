# Electronic National Registration Bureau (NRB)

This project is a modular PHP MVC application for managing national registration, birth registration, citizen data, ID issuance, and reporting.

## Requirements

- PHP 8.2+
- Composer
- SQLite for local development, MySQL for production
- Web server such as Apache or Nginx

## Quick start

1. Copy `.env.example` to `.env` if needed.
2. Run `composer dump-autoload`.
3. Start a local PHP server:
   `php -S localhost:8000 public/index.php`
4. Open `http://localhost:8000/login`.

## Default login

- Email: `admin@nrb.gov.mw`
- Password: `Password123!`

## Project structure

- `app/` — controllers, models, security, middleware, helpers, views
- `config/` — app configuration
- `public/` — front controller and public assets
- `routes/` — route definitions
- `storage/` — logs, sessions, cache, uploads
- `tests/` — verification scripts

## Security notes

- Password hashing uses Argon2ID.
- Session cookies are configured with HttpOnly and SameSite.
- Input sanitization and CSRF tokens are included throughout the base flow.
- Use HTTPS in production and a reverse proxy for rate limiting and TLS termination.

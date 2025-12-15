1. Pull latest `main`.
2. Run `composer install`.
3. Copy `.env.example` to `.env` and update DB and JWT settings.
4. Run `php artisan key:generate` and `php artisan jwt:secret`.
5. Run `php artisan migrate`.
6. Run `php artisan db:seed`
7. Start server with `php artisan serve`.

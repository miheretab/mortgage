
## How to set up the mortgate calculator app

- `composer install`
- create a database (name like mortgage) with utf8_general_ci collation
- `cp .env.example .env` copy and update env with database variables e.g change DB_DATABASE=laravel to the database you created (like mortgage)
- `php artisan key:generate` to generate key in the env
- `php artisan migrate` migrate the database structure
- `php artisan serve` inorder to run the server locally

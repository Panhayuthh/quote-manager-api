## How to run the api locally

1. Clone the repository
2. Cd into the repository
3. Run `composer install`
4. Copy the `.env.example` file to `.env` and fill in database credentials
5. Run `php artisan key:generate`
6. Run `php artisan migrate:fresh --seed`
7. Run `php artisan serve`
8. Visit `http://localhost:8000` in your browser
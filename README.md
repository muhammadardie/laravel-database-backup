# laravel-database-backup
App to manage backup database built using laravel

### Installation ###

* `git clone https://github.com/muhammadardie/laravel-database-backup.git`
* `cd projectname`
* `composer install`
* `cp .env.example .env`
* `php artisan key:generate`
* Create a database and inform *.env*
* give permission in public/ so webserver user can write (store temp database backup and avatar user)
* `php artisan migrate --seed` to create and populate tables
* set configuration in ```config/backup.php```
* `php artisan serve` to start the app on http://localhost:8000/

## Login

email: local@mail.com
password: 123456
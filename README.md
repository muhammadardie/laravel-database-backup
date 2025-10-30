# laravel-database-backup

A Laravel-based application to manage database backups

## Requirements

- PHP 8.2 or higher
- PostgreSQL installed and accessible

## Features

- **Scheduler Backup**: Schedule automatic backups of your PostgreSQL database at specified intervals.
- **Automatic Prune Backup**: Automatically prune old backups based on your desired retention period (e.g., 1 day, 2 days, etc.).
- **Store Backup to SFTP**: Easily store your backups to a remote SFTP server for added security and offsite storage.


## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/muhammadardie/laravel-database-backup.git
    ```

2. Navigate to the project directory:

    ```bash
    cd laravel-database-backup
    ```

3. Install dependencies:

    ```bash
    composer install
    ```

4. Copy the environment file:

    ```bash
    cp .env.example .env
    ```

5. Generate the application key:

    ```bash
    php artisan key:generate
    ```

6. Create a database and configure the `.env` file:

   - Add your database configuration details in the `.env` file:

     ```env
     DB_CONNECTION=pgsql
     DB_HOST=127.0.0.1
     DB_PORT=5432
     DB_DATABASE=your_database_name
     DB_USERNAME=your_database_user
     DB_PASSWORD=your_database_password
     ```

7. Specify the paths for `pg_dump` and `psql` in your `.env` file:

    ```env
    PG_DUMP_PATH=/usr/pgsql-16/bin/pg_dump
    PSQL_PATH=/usr/pgsql-16/bin/psql
    ```

8. Set permissions for the `public` , `storage` and `bootstrap/cache` directories to allow the web server user to write (`public` folder used to store temporary database backups and user avatars):

    ```bash
    chown -R nginx:nginx public storage bootstrap/cache
    chmod -R 775 public storage bootstrap/cache
    ```

9. Run the migrations and seed the database:

    ```bash
    php artisan migrate --seed
    ```
    
10. Start the application:

    ```bash
    php artisan serve
    ```

    Access the app at [http://localhost:8000/](http://localhost:8000/).

### Note

- The application is still in development and currently only supports PostgreSQL.

## Future Enhancements

- Support for additional database engines (e.g., MySQL, SQLite).
- Enhanced UI for managing backups.
- Integration with more cloud storage solutions for remote backups.

## Contributing

Contributions are welcome! If you have suggestions for improvements or new features, please create an issue or submit a pull request.


## License 
This project is licensed under the MIT License. You are free to use, modify, and distribute this software, provided that the original license is included with any substantial portions of the software.

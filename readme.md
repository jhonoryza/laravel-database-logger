# Laravel Database Logger

This package provides ability to store laravel log to the database table.

## Requirement

- PHP 8.1 - 8.4
- Laravel 9, 10, 11, 12

## Getting Started

1. install

```bash
composer require jhonoryza/laravel-database-logger
```

2. publish config files

```bash
php artisan vendor:publish --tag laravel-database-logger
```

3. check the `logging-db` config file and change logger db connection.

4. it is recommended to use different database connection from the main used one.

5. after package is installed, you can run `php artisan migrate` to create the table

6. then modify `logging.php`

    - add `database` channel to the `stack` channel

    ```php
    <?php
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'database'],
            'ignore_exceptions' => true,
        ],
    ```

    - add in `.env` file to set `DB_CONNECTION_LOGGER=pgsql`

---

## Security

If you've found a bug regarding security, please mail [jardik.oryza@gmail.com](mailto:jardik.oryza@gmail.com) instead of
using the issue tracker.

## License

The MIT License (MIT). Please see [License File](license.md) for more information.

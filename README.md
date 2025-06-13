# Translation Management Service

A robust translation management system built with Laravel that helps manage and organize translations across multiple languages.

## Features

-   Multi-language translation management
-   API-first architecture
-   Redis caching for improved performance
-   Comprehensive API documentation with Swagger
-   Unit and feature testing suite

## Requirements

-   PHP 8.4
-   Composer
-   MySQL
-   Redis
-   Docker (for containerized setup)

## Installation

### Option 1: Docker Setup (Recommended)

1. **Clone the repository**

    ```bash
    git clone https://github.com/MoizYounis/translation-management-service.git
    cd translation-management-service
    ```

2. **Copy environment file**

    ```bash
    cp .env.example .env
    ```

3. **Build and start containers**

    ```bash
    docker-compose up -d --build
    ```

4. **Install dependencies and setup application**

    ```bash
    docker-compose exec app composer install
    docker-compose exec app php artisan key:generate
    docker-compose exec app php artisan migrate
    docker-compose exec app php artisan migrate --env=testing
    docker-compose exec app php artisan db:seed
    ```

5. **Access the application**

    - Web: http://localhost:8000
    - API Documentation: http://localhost:8000/api/documentation

6. **Access the application(Optional)**

    If facing permission issue

    Hit command `pwd` and check your-project-path

    sudo chown -R www-data:www-data your-project-path/storage
    sudo chown -R www-data:www-data your-project-path/bootstrap/cache

### Option 2: Manual Setup

1. **Clone the repository**

    ```bash
    git clone https://github.com/MoizYounis/translation-management-service.git
    cd translation-management-service
    ```

2. **Install dependencies**

    ```bash
    composer install
    ```

3. **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Database Configuration**

    Configure your `.env` file with the following database settings:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=translation-service
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    For testing environment (`.env.testing`):

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=translation-service-test
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Redis Configuration**

    Configure Redis in your `.env` file:

    ```
    CACHE_DRIVER=redis
    SESSION_DRIVER=redis
    QUEUE_CONNECTION=redis
    REDIS_CLIENT=phpredis
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    ```

6. **Enable Redis Extension (Windows)**

    - Copy `php_redis.dll` from project root to PHP 8.4's `ext` folder
    - Add `extension=redis` to your `php.ini`
    - Restart your system
    - Start Redis from Laragon and restart Laragon

7. **Database Setup**
    ```bash
    php artisan migrate
    php artisan migrate --env=testing
    php artisan db:seed
    ```

## Testing

Run the test suite:

```bash
# For Docker setup
docker-compose exec app php artisan test

# For manual setup
php artisan test
```

## API Documentation

Access the Swagger API documentation at:

```
http://localhost:8000/api/documentation
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

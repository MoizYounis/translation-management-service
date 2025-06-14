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

### Option 1: Docker Setup

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

6. **Docker MySQL Credentials**

    - username: root
    - password: secret

7. **Access Denied(Optional)**

    If you're facing a permission issue

    Run the pwd command to check your project path.

    ```
    sudo chown -R www-data:www-data your-project-path/storage
    sudo chown -R www-data:www-data your-project-path/bootstrap/cache
    ```

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

# Note:
If the data does not appear after testing in APIs, simply run the seeders again to populate the data.
```

## API Documentation

Manual Serve if needed:

```bash
# For manual serve
php artisan serve
```

Access the Swagger API documentation at:

Note: If the Translations Export API does not work properly on Swagger, please test it on Postman it will work fine there.

```
http://localhost:8000/api/documentation
```

### 1. SOLID Principles Implementation

#### Single Responsibility Principle (SRP)

-   Each class has a specific, focused purpose
-   `TranslationService` exclusively manages translations
-   `AuthService` handles only authentication
-   `FormRequest` classes are dedicated to validation

#### Open/Closed Principle (OCP)

-   `BaseService` abstract class allows extension without modification
-   New features can be added without changing existing code
-   Extension points are provided through interfaces
-   System is open for extension but closed for modification

#### Liskov Substitution Principle (LSP)

-   `TranslationService` extends `BaseService`
-   Child classes maintain parent class behavior
-   Ensures type safety
-   Enables polymorphic behavior

#### Interface Segregation Principle (ISP)

-   `TranslationContract` is divided into smaller interfaces
-   `TranslationCrudContract` and `TranslationExportContract` for separate responsibilities
-   Clients only use methods they need
-   Prevents interface pollution

#### Dependency Inversion Principle (DIP)

-   High-level modules (controllers) depend on abstractions (interfaces)
-   `TranslationController` depends on `TranslationContract` interface
-   Concrete implementations can be easily swapped
-   Promotes loose coupling

### 2. Architectural Patterns

#### Repository Pattern

-   Abstracts data access layer
-   Encapsulates database operations
-   Facilitates testing
-   Provides flexibility to change database implementation
-   Separates business logic from data access

#### Service Layer Pattern

-   Encapsulates business logic in service layer
-   Controllers focus only on HTTP concerns
-   Improves code reusability
-   Centralizes business logic
-   Promotes separation of concerns

#### Factory Pattern

-   Used in ServiceProvider for object creation
-   Encapsulates object creation logic
-   Manages dependencies
-   Facilitates testing
-   Provides centralized object creation

#### Observer Pattern

-   `TranslationObserver` tracks model changes
-   Implements event-driven architecture
-   Maintains loose coupling
-   Manages side effects
-   Enables reactive programming

### 3. Security Patterns

#### Authentication Pattern

-   Implements Laravel Sanctum
-   Token-based authentication
-   Secure password hashing
-   Session management
-   API security

#### Validation Pattern

-   Form Request validation
-   Custom validation rules
-   Comprehensive error messages
-   Input sanitization
-   Data integrity checks

#### Exception Handling Pattern

-   Custom exception classes
-   Consistent error response format
-   Detailed logging
-   Graceful error handling
-   User-friendly error messages

### 4. Performance Patterns

#### Caching Strategy

-   Redis caching implementation
-   Cache invalidation on data changes
-   Configurable TTL
-   Cache tags for better management
-   Optimized data retrieval

#### Database Optimization

-   Proper indexing
-   Efficient queries
-   Pagination for large datasets
-   Query optimization
-   Database performance tuning

#### API Response Optimization

-   JSON response compression
-   Selective field loading
-   Efficient data serialization
-   Response caching
-   Reduced payload size

### 5. Testing Patterns

#### Unit Testing

-   Individual component testing
-   Mock object usage
-   Isolated testing environment
-   Test coverage tracking
-   Automated testing

#### Feature Testing

-   End-to-end functionality testing
-   API endpoint testing
-   Integration testing
-   Performance testing
-   User flow validation

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

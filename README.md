# Project Inventory Management System

A web application for managing inventory, including products, categories, clients, and providers with their associated documents. Built with the Laravel framework.

## Features

*   **User Authentication:** Secure login and logout functionality.
*   **Product Management:** CRUD operations for products. Products have a name, barcode, retail price, wholesale price, current stock, category, and an optional image.
*   **Category Management:** CRUD operations for product categories.
*   **Client Management:** CRUD operations for clients. Clients have a name, company, RFC, address, phone, and email. Supports soft deletes.
*   **Provider Management:** CRUD operations for providers. Providers have company details, contact information, and can have multiple documents uploaded. Supports soft deletes, force deletes, and restoration.
*   **Provider Document Management:** Upload, download, and delete documents associated with providers (e.g., contracts, catalogs).
*   **Database Migrations and Seeding:** For easy setup and initial data population.
*   **Testing Suite:** Includes unit tests for models and feature tests for controllers.

## Technologies Used

*   [Laravel](https://laravel.com/)
*   PHP
*   MySQL (or other compatible database)
*   Tailwind CSS (via Laravel Breeze/Jetstream default or custom setup)
*   Blade Templating Engine
*   PHPUnit for testing

## Setup and Installation

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd <repository-name>
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Create environment file:**
    Copy `.env.example` to `.env` and configure your database connection and other environment variables.
    ```bash
    cp .env.example .env
    ```

4.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```

5.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```

6.  **(Optional) Seed the database:**
    If seeders are available (e.g., for an admin user or default categories):
    ```bash
    php artisan db:seed
    ```
    *(Note: The `AdminUserSeeder`, `CategorySeeder`, `DatabaseSeeder`, `ProviderSeeder` were observed in the project structure)*

7.  **Link storage directory:**
    To make uploaded files (like product images and provider documents) publicly accessible:
    ```bash
    php artisan storage:link
    ```

8.  **Install NPM dependencies (if needed for frontend assets):**
    ```bash
    npm install
    npm run dev # or npm run build
    ```

9.  **Serve the application:**
    ```bash
    php artisan serve
    ```
    The application should now be accessible at `http://localhost:8000` (or the port shown).

## Running Tests

To run the PHPUnit test suite:

```bash
php artisan test
```

Or, for more verbose output:

```bash
./vendor/bin/phpunit
```

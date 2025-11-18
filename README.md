# Steps Nursery Management System (SNMS)

An all-in-one digital platform designed to manage all academic, administrative, operational, HR, and financial processes inside Steps Play School.

## Tech Stack

- **Backend**: Laravel 11 REST API
- **Authentication**: Laravel Sanctum
- **Database**: MySQL 8
- **Storage**: Cloud Object Storage (S3 or DigitalOcean Spaces)

## Setup Instructions

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8
- Node.js & npm (for frontend assets)

### Installation

1. Install dependencies:
   ```bash
   composer install
   ```

2. Copy environment file:
   ```bash
   cp .env.example .env
   ```

3. Generate application key:
   ```bash
   php artisan key:generate
   ```

4. Configure your database in `.env` file

5. Run migrations:
   ```bash
   php artisan migrate
   ```

6. Start the development server:
   ```bash
   php artisan serve
   ```

The application will be available at `http://localhost:8000`

## Features

- Student Management
- Online Admission
- Attendance Management
- Evaluation & Progress Reports
- Events & Journeys (Trips)
- HR & Payroll
- Accounting & Finance
- Inventory & Assets
- Parent App

## Documentation

For full product requirements, see [prd.md](prd.md)

## License

MIT

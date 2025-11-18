# Steps Nursery Management System (SNMS)

An all-in-one digital platform designed to manage all academic, administrative, operational, HR, and financial processes inside Steps Play School.

## Tech Stack

- **Backend**: Laravel 11 REST API
- **Authentication**: Laravel Sanctum
- **Database**: MySQL 8
- **Storage**: Cloud Object Storage (S3 or DigitalOcean Spaces)

## Project Structure

```
SNMS/
├── backend/          # Laravel backend API
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── routes/
│   └── ...
└── prd.md           # Product Requirements Document
```

## Setup Instructions

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8
- Node.js & npm (for frontend assets)

### Installation

1. Navigate to the backend directory:
   ```bash
   cd backend
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy environment file (if not already present):
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Configure your database in `.env` file

6. Run migrations:
   ```bash
   php artisan migrate
   ```

7. Start the development server:
   ```bash
   php artisan serve --host=127.0.0.1 --port=8000
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

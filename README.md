# Steps Nursery Management System (SNMS)

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![React](https://img.shields.io/badge/React-18.x-61DAFB?style=for-the-badge&logo=react&logoColor=black)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)

**A comprehensive nursery management platform for Steps Play School**

[Documentation](#documentation) •
[Features](#features) •
[Tech Stack](#tech-stack) •
[Getting Started](#getting-started) •
[Architecture](#architecture)

</div>

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Documentation](#documentation)
- [Project Structure](#project-structure)
- [Getting Started](#getting-started)
- [Development](#development)
- [Deployment](#deployment)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

---

## 🎯 Overview

**Steps Nursery Management System (SNMS)** is an all-in-one digital platform designed to manage all academic, administrative, operational, HR, and financial processes for Steps Play School. The system provides:

- **Admin Dashboard**: Comprehensive web-based dashboard for staff (Admin, Teachers, HR, Accountant, Reception)
- **Parent App**: Mobile-first web application for parents to track their children's progress
- **REST API**: Laravel-powered backend API with complete documentation

### Key Highlights

- ✅ **35+ Database Tables** with complete relationships
- ✅ **80+ RESTful API Endpoints** with OpenAPI documentation
- ✅ **150+ Granular Permissions** across 7 user roles
- ✅ **9 Core Modules** covering all nursery operations
- ✅ **Mobile-First Parent App** with PWA support
- ✅ **Bilingual Support** (English + Arabic)
- ✅ **Real-time Notifications** (WhatsApp, Email, In-app)
- ✅ **Payment Gateway Integration** (Paymob/Stripe)
- ✅ **Cloud Storage** (S3/DigitalOcean Spaces)

---

## 🚀 Features

### 1. Student Management
- Complete student profiles with documents and photos
- Class assignments and academic tracking
- Medical and emergency information
- Student document management
- Student code auto-generation

### 2. Online Admissions
- Digital admission application forms
- Document uploads
- Interview scheduling
- Application pipeline tracking (New → Review → Accept/Reject)
- Convert applications to enrolled students

### 3. Attendance Management
- **Student Attendance**: Daily tracking with QR code support
- **Teacher Attendance**: Staff attendance with overtime calculation
- Temperature checks
- Automatic parent notifications
- Monthly attendance reports
- Visual attendance calendar

### 4. Evaluations & Progress Reports
- Flexible evaluation templates
- Skills-based assessments (Cognitive, Social, Motor, Language, etc.)
- Photo and video attachments
- Published evaluations visible to parents
- Progress tracking over time
- Customizable scoring system

### 5. Events & Journeys (Trips)
- Event creation and management
- Parental permission workflow
- Event fee collection
- Participant tracking
- Event attendance marking

### 6. HR & Payroll
- Employee records and documents
- Contract management
- Leave request system (Annual, Sick, Emergency, etc.)
- Automated payroll calculation
- Payslip generation (PDF)
- Employee attendance tracking

### 7. Accounting & Finance
- Invoice generation (Tuition, Registration, Events, etc.)
- Payment processing (Cash, Card, Bank Transfer, Online)
- Payment gateway integration
- Partial payment support
- Expense tracking and approval
- Financial reports (Income, Expense, Cashflow, P&L)

### 8. Inventory & Assets
- Stock management with categories
- Low-stock alerts
- Stock movement tracking
- Asset lifecycle management
- Asset assignment to staff
- Condition tracking

### 9. Communication
- Internal messaging system
- Broadcast announcements
- In-app notifications
- WhatsApp notifications
- Email notifications
- Parent-teacher communication

### 10. Parent App Features
- Child dashboard with daily updates
- Attendance history
- View evaluations and progress
- Photo/video gallery
- Upcoming events
- Pay invoices online
- Receive notifications
- Messages from teachers/school

---

## 🛠 Tech Stack

### Backend
- **Framework**: Laravel 12.x
- **PHP**: 8.3+
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum (SPA + API tokens)
- **Storage**: S3 / DigitalOcean Spaces
- **Cache**: Redis
- **Queue**: Redis / AWS SQS
- **Testing**: PHPUnit, Pest

### Frontend
- **Framework**: React 18.x
- **Build Tool**: Vite
- **Language**: TypeScript
- **Styling**: Tailwind CSS 3.x
- **State Management**: React Context + TanStack Query
- **Forms**: React Hook Form + Zod
- **HTTP Client**: Axios
- **Icons**: Heroicons / Lucide React
- **Charts**: Recharts / Chart.js
- **Testing**: Vitest, React Testing Library

### Infrastructure
- **Web Server**: Nginx
- **Process Manager**: Supervisor (queue workers)
- **Containerization**: Docker (optional)
- **CI/CD**: GitHub Actions
- **Monitoring**: Sentry, New Relic (optional)

---

## 🏗 Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     Client Layer                             │
├──────────────────────────┬──────────────────────────────────┤
│   React Admin Dashboard  │    React Parent App               │
│   (Desktop/Tablet)       │    (Mobile-First PWA)             │
└──────────────┬───────────┴──────────────┬───────────────────┘
               │                          │
               │    HTTPS/REST API        │
               │                          │
┌──────────────┴──────────────────────────┴───────────────────┐
│              Laravel 12 API Gateway                          │
│         (Sanctum Auth + Rate Limiting)                       │
└──────────────┬──────────────────────────────────────────────┘
               │
┌──────────────┴──────────────────────────────────────────────┐
│                  Application Layer                           │
│  Controllers → Services → Repositories → Models              │
│  Policies (RBAC) | Middleware | Events | Notifications       │
└──────────────┬──────────────────────────────────────────────┘
               │
┌──────────────┴──────────────────────────────────────────────┐
│                   Data Layer                                 │
├──────────────────────────┬───────────────────────────────────┤
│      MySQL 8 Database    │   S3/DO Spaces (File Storage)    │
└──────────────────────────┴───────────────────────────────────┘
```

### Database Schema

**35+ Tables** organized into modules:
- Authentication & Authorization (5 tables)
- Student Management (4 tables)
- Attendance (2 tables)
- Evaluations (5 tables)
- Events (2 tables)
- Admissions (2 tables)
- HR & Payroll (4 tables)
- Finance (4 tables)
- Inventory & Assets (3 tables)
- Communication (3 tables)
- Settings (2 tables)

See [ERD.md](./docs/ERD.md) for complete database schema.

---

## 📚 Documentation

Comprehensive documentation is available in the `/docs` directory:

| Document | Description |
|----------|-------------|
| [ARCHITECTURE.md](./docs/ARCHITECTURE.md) | Complete system architecture overview |
| [ERD.md](./docs/ERD.md) | Database schema with all tables, fields, and relationships |
| [API_SPECIFICATIONS.md](./docs/API_SPECIFICATIONS.md) | Complete API endpoint documentation (80+ endpoints) |
| [RBAC_PERMISSIONS.md](./docs/RBAC_PERMISSIONS.md) | Role-Based Access Control matrix for all roles |
| [FOLDER_STRUCTURE.md](./docs/FOLDER_STRUCTURE.md) | Complete folder structure for Laravel & React apps |
| [DEPLOYMENT.md](./docs/DEPLOYMENT.md) | Deployment guide with Docker, Nginx, CI/CD setup |
| [CLARIFICATIONS_AND_PROPOSALS.md](./docs/CLARIFICATIONS_AND_PROPOSALS.md) | Design decisions and clarifications |

---

## 📁 Project Structure

```
SNMS/
├── backend/                 # Laravel 12 API
│   ├── app/
│   │   ├── Http/Controllers/Api/V1/
│   │   ├── Models/
│   │   ├── Policies/
│   │   ├── Services/
│   │   └── ...
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   └── routes/api.php
│
├── frontend/
│   ├── admin-dashboard/     # React Admin App
│   │   ├── src/
│   │   │   ├── components/
│   │   │   ├── pages/
│   │   │   ├── api/
│   │   │   └── ...
│   │   └── package.json
│   │
│   └── parent-app/          # React Parent App (Mobile-First)
│       ├── src/
│       └── package.json
│
├── docs/                    # Documentation
├── docker/                  # Docker configuration
└── README.md
```

See [FOLDER_STRUCTURE.md](./docs/FOLDER_STRUCTURE.md) for complete structure.

---

## 🚦 Getting Started

### Prerequisites

- PHP 8.3 or higher
- Composer 2.x
- Node.js 20.x or higher
- MySQL 8.0
- Redis (optional, recommended for production)

### Installation

#### 1. Clone the Repository

```bash
git clone https://github.com/yourorg/snms.git
cd snms
```

#### 2. Backend Setup (Laravel)

```bash
cd backend

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
# DB_DATABASE=snms
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed database with roles and permissions
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=SettingsSeeder

# (Optional) Seed demo data
php artisan db:seed --class=DemoDataSeeder

# Link storage
php artisan storage:link

# Start development server
php artisan serve
```

API will be available at: `http://localhost:8000/api/v1`

#### 3. Admin Dashboard Setup (React)

```bash
cd frontend/admin-dashboard

# Install dependencies
npm install

# Copy environment file
cp .env.example .env

# Configure API URL in .env
# VITE_API_BASE_URL=http://localhost:8000/api/v1

# Start development server
npm run dev
```

Admin dashboard will be available at: `http://localhost:5173`

#### 4. Parent App Setup (React)

```bash
cd frontend/parent-app

# Install dependencies
npm install

# Copy environment file
cp .env.example .env

# Configure API URL in .env
# VITE_API_BASE_URL=http://localhost:8000/api/v1

# Start development server
npm run dev
```

Parent app will be available at: `http://localhost:5174`

---

## 💻 Development

### Running the Development Environment

You'll need 3 terminals:

**Terminal 1 - Laravel API:**
```bash
cd backend
php artisan serve
```

**Terminal 2 - Admin Dashboard:**
```bash
cd frontend/admin-dashboard
npm run dev
```

**Terminal 3 - Parent App:**
```bash
cd frontend/parent-app
npm run dev
```

### Default Login Credentials (After Seeding)

**Super Admin:**
- Email: `admin@stepsnursery.com`
- Password: `password`

**Teacher:**
- Email: `teacher@stepsnursery.com`
- Password: `password`

**Parent:**
- Email: `parent@stepsnursery.com`
- Password: `password`

### Running Queue Workers (Development)

```bash
cd backend
php artisan queue:work
```

### Running Scheduled Tasks (Development)

```bash
cd backend
php artisan schedule:work
```

---

## 🧪 Testing

### Backend Tests (Laravel)

```bash
cd backend

# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run tests with coverage
php artisan test --coverage
```

### Frontend Tests (React)

```bash
cd frontend/admin-dashboard

# Run unit tests
npm run test

# Run tests with coverage
npm run test:coverage

# Run E2E tests (if configured)
npm run test:e2e
```

---

## 🚀 Deployment

See [DEPLOYMENT.md](./docs/DEPLOYMENT.md) for complete deployment guide.

### Quick Production Deployment

#### 1. Build Frontend Apps

```bash
# Admin Dashboard
cd frontend/admin-dashboard
npm run build

# Parent App
cd frontend/parent-app
npm run build
```

#### 2. Configure Laravel for Production

```bash
cd backend

# Install production dependencies
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force
```

#### 3. Configure Nginx

See [DEPLOYMENT.md](./docs/DEPLOYMENT.md) for Nginx configuration examples.

#### 4. Set Up Queue Workers

```bash
# Install Supervisor
sudo apt install supervisor

# Configure supervisor (see DEPLOYMENT.md)
# Start queue workers
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start snms-worker:*
```

---

## 📊 API Documentation

### Base URL
- **Development**: `http://localhost:8000/api/v1`
- **Production**: `https://api.stepsnursery.com/api/v1`

### Authentication

All endpoints (except login/register) require Sanctum token authentication:

```bash
# Login
POST /api/v1/auth/login
{
  "email": "user@example.com",
  "password": "password"
}

# Response
{
  "success": true,
  "data": {
    "token": "1|abc123...",
    "user": { ... }
  }
}

# Use token in subsequent requests
Authorization: Bearer {token}
```

### Key Endpoints

- **Students**: `/api/v1/students`
- **Attendance**: `/api/v1/attendance/students`
- **Evaluations**: `/api/v1/evaluations`
- **Events**: `/api/v1/events`
- **Invoices**: `/api/v1/invoices`
- **Payments**: `/api/v1/payments`

See [API_SPECIFICATIONS.md](./docs/API_SPECIFICATIONS.md) for complete API documentation.

---

## 👥 User Roles & Permissions

The system supports 7 user roles with granular permissions:

| Role | Description | Key Permissions |
|------|-------------|----------------|
| **Super Admin** | System owner | Full access to everything (*) |
| **Admin** | Daily operations manager | All modules except system settings |
| **Teacher** | Class instructor | Students, Attendance, Evaluations (own classes) |
| **Reception** | Front desk staff | Admissions, Students, Communication |
| **HR Officer** | Human resources | Employees, Leaves, Payroll |
| **Accountant** | Financial operations | Invoices, Payments, Expenses, Reports |
| **Parent** | Student guardian | Own children (read-only + payments) |

See [RBAC_PERMISSIONS.md](./docs/RBAC_PERMISSIONS.md) for complete permission matrix.

---

## 🌟 Key Features Implemented

### ✅ Phase 1 (Foundation)
- [x] Authentication & Authorization (Sanctum)
- [x] Role-Based Access Control (RBAC)
- [x] User Management
- [x] Database Schema (35+ tables)
- [x] API Structure (80+ endpoints)
- [x] Complete Documentation

### 🚧 Phase 2 (In Progress)
- [ ] Student Management Module (Controllers, UI)
- [ ] Attendance Module (QR Code Support)
- [ ] Class Management
- [ ] Guardian Management

### 📅 Phase 3 (Planned)
- [ ] Evaluations & Progress Reports
- [ ] Events & Trips Management
- [ ] Finance Module (Invoices, Payments)
- [ ] Payment Gateway Integration

### 🔮 Phase 4 (Future)
- [ ] HR & Payroll Module
- [ ] Inventory & Assets
- [ ] Real-time Notifications
- [ ] WhatsApp Integration
- [ ] Multi-Branch Support

---

## 🤝 Contributing

We welcome contributions! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Code Style

- **PHP**: Follow PSR-12 coding standards
- **JavaScript/TypeScript**: Use ESLint + Prettier
- **Commit Messages**: Use conventional commits format

---

## 📄 License

This project is proprietary software developed for Steps Play School.

© 2024 Steps Play School. All rights reserved.

---

## 📞 Support & Contact

For questions, issues, or support:

- **Documentation**: See `/docs` folder
- **Issues**: [GitHub Issues](https://github.com/yourorg/snms/issues)
- **Email**: support@stepsnursery.com

---

## 🙏 Acknowledgments

- Laravel Community
- React Community
- Tailwind CSS
- All open-source contributors

---

<div align="center">

**Built with ❤️ for Steps Play School**

[⬆ Back to Top](#steps-nursery-management-system-snms)

</div>

# Steps Nursery Management System (SNMS) - Technical Architecture

## 1. System Overview

SNMS is a full-stack nursery management platform built with:
- **Backend**: Laravel 12 REST API
- **Frontend**: React with Tailwind CSS (Admin Dashboard + Parent App)
- **Database**: MySQL 8
- **Authentication**: Laravel Sanctum
- **Storage**: S3/DigitalOcean Spaces
- **Integrations**: WhatsApp API, Email (SMTP), Payment Gateway (Paymob/Stripe)

## 2. High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     Client Layer                             │
├──────────────────────────┬──────────────────────────────────┤
│   React Admin Dashboard  │    React Parent App               │
│   (Desktop/Tablet)       │    (Mobile-First)                 │
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
├──────────────────────────────────────────────────────────────┤
│  Controllers → Services → Repositories → Models              │
│  Policies (RBAC) | Middleware | Events | Notifications       │
└──────────────┬──────────────────────────────────────────────┘
               │
┌──────────────┴──────────────────────────────────────────────┐
│                   Data Layer                                 │
├──────────────────────────┬───────────────────────────────────┤
│      MySQL 8 Database    │   S3/DO Spaces (File Storage)    │
└──────────────────────────┴───────────────────────────────────┘
               │
┌──────────────┴──────────────────────────────────────────────┐
│              External Integrations                           │
├──────────────┬───────────────┬──────────────────────────────┤
│  WhatsApp API│  SMTP (Email) │  Payment Gateway             │
└──────────────┴───────────────┴──────────────────────────────┘
```

## 3. Technology Stack Details

### 3.1 Backend (Laravel 12)
- **Framework**: Laravel 12.x
- **PHP Version**: 8.3+
- **Authentication**: Laravel Sanctum (SPA + API tokens)
- **Validation**: Form Requests
- **Authorization**: Policies + Gates
- **ORM**: Eloquent
- **Queue**: Redis/Database
- **Cache**: Redis
- **Storage**: Laravel Storage with S3 driver

### 3.2 Frontend (React)
- **Framework**: React 18+
- **Build Tool**: Vite
- **Routing**: React Router v6
- **State Management**: React Context API + TanStack Query (React Query)
- **Styling**: Tailwind CSS 3.x
- **UI Components**: Headless UI + Custom Components
- **Forms**: React Hook Form + Zod validation
- **HTTP Client**: Axios
- **Icons**: Heroicons/Lucide React

### 3.3 Database (MySQL 8)
- **Version**: MySQL 8.0+
- **Character Set**: utf8mb4
- **Collation**: utf8mb4_unicode_ci
- **Storage Engine**: InnoDB
- **Indexing Strategy**: Composite indexes on foreign keys and frequently queried fields

## 4. Application Architecture Patterns

### 4.1 Backend Architecture (Laravel)

**Repository Pattern** (Optional, for complex queries):
```
Controller → Service → Repository → Model → Database
```

**Standard MVC Pattern** (For CRUD operations):
```
Controller → Model → Database
```

**Key Principles**:
- Single Responsibility Principle
- Dependency Injection
- Interface-based design for services
- Event-driven architecture for notifications
- Job queues for long-running tasks (payroll, reports, emails)

### 4.2 Frontend Architecture (React)

**Component Hierarchy**:
```
Pages → Layouts → Features → Components → UI Elements
```

**Data Flow**:
```
Component → Custom Hook → API Service → Laravel API
```

**Key Principles**:
- Component composition over inheritance
- Custom hooks for business logic
- Separation of concerns (UI vs Logic)
- Atomic design principles
- Mobile-first responsive design

## 5. Security Architecture

### 5.1 Authentication Flow
1. User submits credentials to `/api/login`
2. Laravel validates and issues Sanctum token
3. Token stored in httpOnly cookie (SPA) or localStorage (mobile)
4. All subsequent requests include token in Authorization header
5. Middleware validates token and loads user

### 5.2 Authorization (RBAC)
- Role-based permissions stored in database
- Policy classes for each model
- Gate checks in controllers
- Middleware for route protection

### 5.3 Data Security
- All passwords hashed with bcrypt
- HTTPS enforced
- CORS configured for frontend domains
- SQL injection prevention (Eloquent ORM)
- XSS prevention (React auto-escaping)
- CSRF protection for state-changing operations
- File upload validation and sanitization

## 6. API Architecture

### 6.1 RESTful Design Principles
- Resource-based URLs
- HTTP verbs (GET, POST, PUT, PATCH, DELETE)
- Consistent response format
- Proper HTTP status codes
- API versioning (v1, v2)

### 6.2 Standard Response Format
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {},
  "meta": {
    "current_page": 1,
    "total": 100
  }
}
```

### 6.3 Error Response Format
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required"]
  }
}
```

## 7. Data Flow Architecture

### 7.1 Student Attendance Flow
```
Teacher (React)
  → POST /api/attendance
  → AttendanceController
  → AttendanceService
  → Create Attendance Record
  → Trigger Event (AttendanceMarked)
  → Send Notification to Parent (WhatsApp/Email)
  → Queue Job
```

### 7.2 Payroll Generation Flow
```
HR Officer (React)
  → POST /api/payroll/generate
  → PayrollController
  → PayrollService
  → Calculate Salary (base + overtime - deductions)
  → Generate Payslip PDF
  → Store in S3
  → Send Email to Employee
  → Queue Job
```

### 7.3 Invoice Payment Flow
```
Parent App
  → GET /api/invoices
  → View Pending Invoices
  → POST /api/payments
  → PaymentController
  → Payment Gateway Integration
  → Update Invoice Status
  → Generate Receipt
  → Send Confirmation
```

## 8. Scalability Considerations

### 8.1 Database Optimization
- Proper indexing on foreign keys
- Database query optimization (N+1 prevention with eager loading)
- Database connection pooling
- Read replicas for reporting queries

### 8.2 Caching Strategy
- Cache frequently accessed data (classes, settings)
- API response caching with cache tags
- Redis for session and cache storage
- CDN for static assets

### 8.3 Queue Management
- Background jobs for emails, notifications, reports
- Failed job handling and retry logic
- Job prioritization (high, normal, low)

### 8.4 File Storage
- S3/DigitalOcean Spaces for scalable storage
- Signed URLs for secure file access
- Image optimization and resizing
- CDN for file delivery

## 9. Multi-Tenancy Preparation (Future)

While the current version is single-tenant, the architecture supports future multi-tenancy:
- `branch_id` foreign key in major tables
- Scoped queries using global scopes
- Separate storage buckets per branch
- Subdomain or path-based routing

## 10. Monitoring & Logging

### 10.1 Application Logging
- Laravel Log channels (daily, slack, database)
- Error tracking (Sentry/Bugsnag)
- API request logging
- Audit trail for critical operations

### 10.2 Performance Monitoring
- Response time tracking
- Database query monitoring
- Redis monitoring
- Server resource monitoring (CPU, memory, disk)

## 11. Development Workflow

### 11.1 Git Workflow
- `main` branch (production)
- `develop` branch (staging)
- Feature branches (`feature/module-name`)
- Bug fixes (`bugfix/issue-description`)
- Pull request reviews required

### 11.2 Environment Setup
- **Local**: Docker Compose (MySQL, Redis, Mailpit)
- **Staging**: DigitalOcean/AWS
- **Production**: DigitalOcean/AWS with load balancer

### 11.3 CI/CD Pipeline
```
Push to branch
  → Run Tests (PHPUnit, Jest)
  → Code Quality (PHPStan, ESLint)
  → Build Assets (npm run build)
  → Deploy to Environment
```

## 12. Testing Strategy

### 12.1 Backend Testing
- **Unit Tests**: Models, Services, Helpers
- **Feature Tests**: API endpoints, Controllers
- **Integration Tests**: Payment gateway, Email service
- **Code Coverage**: Target 80%+

### 12.2 Frontend Testing
- **Unit Tests**: Utility functions, Hooks
- **Component Tests**: React Testing Library
- **E2E Tests**: Cypress/Playwright
- **Visual Regression**: Chromatic (optional)

## 13. Documentation

- **API Documentation**: OpenAPI/Swagger
- **Code Documentation**: PHPDoc, JSDoc
- **User Documentation**: Admin guide, Parent guide
- **Developer Documentation**: Setup guide, Architecture guide

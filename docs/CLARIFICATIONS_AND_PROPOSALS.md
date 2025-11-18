# Clarifications & Proposed Solutions - SNMS

## Overview
This document outlines areas in the PRD that need clarification or where design decisions were made based on best practices and assumptions.

---

## 1. Unclear Areas from PRD

### 1.1 Class Structure
**Issue**: The PRD mentions "classes" but doesn't specify:
- How many age groups/classes are typical?
- Can a student be in multiple classes (e.g., main class + extracurricular)?
- How are classes structured academically (terms, semesters)?

**Proposed Solution**:
- Single class assignment per student per academic year
- Support for 5-10 standard age groups (Toddlers, Pre-K, etc.)
- Academic year-based class structure (2024-2025)
- Allow multiple classes per academic year for future expansion

**Implementation**:
```php
// Student belongs to ONE class at a time
$student->class_id

// Academic year field in classes table
$class->academic_year = "2024-2025"

// Future: student_class_history table for tracking class changes
```

---

### 1.2 Evaluation System Details
**Issue**: The PRD mentions "skills-based assessment" but doesn't specify:
- What are the evaluation criteria categories?
- Scoring system (numeric, grades, descriptive)?
- Frequency of evaluations (weekly, monthly)?
- Who defines evaluation templates?

**Proposed Solution**:
- **Flexible Template System**: Admin/Teacher creates templates
- **Categories**: Cognitive, Social, Motor Skills, Language, Emotional, Creative
- **Scoring**: 1-5 scale per skill (customizable max_score)
- **Frequency**: Monthly, Weekly, Term-based, Annual (configurable)
- **Templates per Age Group**: Different templates for different classes

**Implementation**:
```
evaluation_templates
  ├── id
  ├── name: "Monthly Assessment - Toddlers"
  ├── type: monthly, weekly, term, annual
  └── age_group: "2-3 years"

evaluation_criteria
  ├── template_id
  ├── category: "Cognitive"
  ├── skill_name: "Recognizes colors"
  ├── max_score: 5
  └── display_order: 1

student_evaluations
  ├── student_id
  ├── template_id
  ├── evaluation_date
  └── is_published: boolean

evaluation_scores
  ├── evaluation_id
  ├── criteria_id
  ├── score: 4
  └── notes: "Improving!"
```

---

### 1.3 Payment Gateway Integration
**Issue**: PRD mentions "Paymob / Stripe" but doesn't specify:
- Which gateway is primary?
- Support for both or one?
- Partial payments allowed?
- Recurring payments for tuition?

**Proposed Solution**:
- **Primary**: Paymob (for MENA region)
- **Alternative**: Stripe (international)
- **Interface-based design** for easy swapping
- **Partial payments**: Supported
- **Recurring**: Future feature (manual for MVP)

**Implementation**:
```php
interface PaymentGatewayInterface {
    public function createPayment(Invoice $invoice, float $amount);
    public function verifyPayment(string $transactionId);
    public function refund(Payment $payment, float $amount);
}

class PaymobGateway implements PaymentGatewayInterface { }
class StripeGateway implements PaymentGatewayInterface { }

// Config: config/services.php
'payment_gateway' => env('PAYMENT_GATEWAY', 'paymob'),
```

---

### 1.4 WhatsApp Notification Strategy
**Issue**: PRD mentions "WhatsApp API notifications" but doesn't specify:
- Official WhatsApp Business API or third-party?
- Which events trigger WhatsApp notifications?
- Fallback if WhatsApp fails?

**Proposed Solution**:
- **WhatsApp Business API** (official) via service provider
- **Notification Events**:
  - Daily attendance marked (sent to parent)
  - Evaluation published
  - Invoice created
  - Event permission required
  - Important announcements
- **Fallback**: Email + In-app notification
- **Queue-based**: All notifications queued for reliability

**Implementation**:
```php
// Notification with multiple channels
$parent->notify(new AttendanceMarkedNotification($attendance));

// AttendanceMarkedNotification.php
public function via($notifiable) {
    return ['database', 'whatsapp', 'mail'];
}

public function toWhatsApp($notifiable) {
    return WhatsAppMessage::create()
        ->content("Your child {$this->student->name} was marked present today.")
        ->button('View Details', $this->url);
}
```

---

### 1.5 Multi-Branch Support (Future)
**Issue**: PRD mentions "Support multiple nursery branches in the future" but current structure unclear

**Proposed Solution**:
- **Current (Single Branch)**: No branch_id in tables
- **Migration Path**: Add `branch_id` to major tables when needed
- **Database Design**: Prepare for multi-tenancy but don't implement yet
- **Global Scopes**: When multi-branch is implemented, use Laravel global scopes

**Implementation Plan (Future)**:
```php
// When needed, add migration:
Schema::table('students', function (Blueprint $table) {
    $table->unsignedBigInteger('branch_id')->nullable()->after('id');
    $table->foreign('branch_id')->references('id')->on('branches');
});

// Global scope
protected static function booted() {
    static::addGlobalScope(new BranchScope);
}
```

---

### 1.6 QR Code Attendance
**Issue**: PRD mentions "manual / QR" attendance but doesn't detail QR implementation

**Proposed Solution**:
- **QR Code per Student**: Generated and stored in student record
- **QR Format**: Encrypted student_id + date
- **Scan Method**: Mobile app or web-based scanner
- **Security**: Time-based expiry, one-time use per day
- **Manual Override**: Always available for teachers

**Implementation**:
```php
// Generate QR code for student
public function generateAttendanceQR(Student $student) {
    $data = encrypt([
        'student_id' => $student->id,
        'date' => now()->format('Y-m-d'),
        'expires_at' => now()->addHours(2)
    ]);

    return QrCode::generate($data);
}

// Verify and mark attendance
public function scanQR($qrData) {
    $data = decrypt($qrData);

    if (Carbon::parse($data['expires_at'])->isPast()) {
        throw new Exception('QR code expired');
    }

    // Mark attendance...
}
```

---

## 2. Design Decisions & Assumptions

### 2.1 Student Code Generation
**Decision**: Auto-generated unique student codes

**Format**: `STU{YEAR}{SEQUENCE}`
- Example: `STU2024001`, `STU2024002`

**Implementation**:
```php
// StudentObserver.php
public function creating(Student $student) {
    $year = date('Y');
    $lastStudent = Student::whereYear('created_at', $year)
        ->orderBy('id', 'desc')
        ->first();

    $sequence = $lastStudent
        ? intval(substr($lastStudent->student_code, -3)) + 1
        : 1;

    $student->student_code = 'STU' . $year . str_pad($sequence, 3, '0', STR_PAD_LEFT);
}
```

---

### 2.2 Invoice Numbering
**Decision**: Consistent invoice numbering system

**Format**: `INV-{YEAR}-{SEQUENCE}`
- Example: `INV-2024-001`

**Similar for**:
- Payments: `PAY-2024-001`
- Expenses: `EXP-2024-001`
- Employees: `EMP001`
- Applications: `APP-2024-001`

---

### 2.3 File Storage Organization
**Decision**: Organized S3/DO Spaces structure

```
snms-production/
├── students/
│   ├── photos/
│   │   └── {student_id}/
│   │       └── profile.jpg
│   └── documents/
│       └── {student_id}/
│           ├── birth_certificate.pdf
│           └── medical_records.pdf
├── employees/
│   ├── photos/
│   └── documents/
├── evaluations/
│   └── {evaluation_id}/
│       ├── photo1.jpg
│       └── video1.mp4
├── receipts/
│   └── {expense_id}/
├── payslips/
│   └── {year}/{month}/
│       └── {employee_id}.pdf
└── invoices/
    └── {year}/
        └── {invoice_id}.pdf
```

---

### 2.4 Default Permissions Strategy
**Decision**: Permissive defaults with explicit denials

**Logic**:
- Super Admin: All permissions (wildcard `*`)
- Admin: Most permissions except system-critical (roles, settings)
- Teachers: Class and student-related permissions
- Parents: Read-only for their children

**Seeded on Installation**:
- 7 default roles
- 150+ permissions
- Default role-permission mappings

---

### 2.5 Mobile App Strategy (Parent App)
**Decision**: PWA (Progressive Web App) instead of native mobile app

**Reasons**:
- Single codebase for web + mobile
- No app store approval process
- Instant updates
- Lower development cost
- Offline capability via service workers

**Features**:
- Add to Home Screen
- Push notifications (via Firebase)
- Offline data caching
- Mobile-optimized UI

---

### 2.6 Localization (i18n)
**Assumption**: Bilingual support (English + Arabic)

**Implementation**:
```
backend/resources/lang/
  ├── en/
  │   ├── auth.php
  │   ├── students.php
  │   └── ...
  └── ar/
      ├── auth.php
      ├── students.php
      └── ...

frontend/src/locales/
  ├── en.json
  └── ar.json
```

**API**:
- Accept `Accept-Language` header
- Default: English
- User preference stored in database

---

### 2.7 Attendance Check-in/out Times
**Decision**: Optional check-in/out times

**Flexibility**:
- Can mark attendance without times (status only)
- Can add check-in time later
- Can add check-out time when student leaves
- Temperature check optional (can be null)

**Use Cases**:
- Quick morning attendance: Just mark Present/Absent
- Detailed tracking: Add check-in, check-out, temperature

---

### 2.8 Evaluation Publishing
**Decision**: Draft → Published workflow

**Process**:
1. Teacher creates evaluation (draft)
2. Teacher can edit/update while draft
3. Teacher publishes when ready
4. Once published:
   - Parents can view
   - Notification sent
   - Cannot be unpublished (can only be edited)

**Audit Trail**: All changes logged

---

### 2.9 Invoice Due Dates and Late Fees
**Assumption**: Late fees not implemented in MVP

**MVP Behavior**:
- Due dates tracked
- Status changes to "overdue" automatically
- No automatic late fee calculation
- Manual late fee addition by accountant

**Future**: Automated late fee calculation based on settings

---

### 2.10 Parent Account Creation
**Decision**: Created automatically when guardian is added

**Process**:
1. Reception adds guardian information
2. System automatically:
   - Creates user account with email
   - Sends welcome email with password reset link
   - Assigns "parent" role
   - Links user to guardian record

**Alternative**: Parent self-registration via admission application

---

## 3. Technical Decisions

### 3.1 API Versioning
**Decision**: Version 1 (v1) with versioned routes

**Route Structure**:
```
/api/v1/students
/api/v1/attendance
```

**Future**: v2 when breaking changes needed

---

### 3.2 Authentication Token Expiry
**Decision**:
- **Web (SPA)**: Cookie-based, session lifetime (24 hours)
- **Mobile**: Token-based, 30 days expiry
- **Refresh Token**: Supported

---

### 3.3 File Upload Limits
**Decision**:
- **Photos**: Max 5 MB (jpg, jpeg, png, webp)
- **Documents**: Max 10 MB (pdf, doc, docx)
- **Videos** (evaluations): Max 50 MB (mp4, mov)

---

### 3.4 Pagination Defaults
**Decision**:
- Default: 15 items per page
- Max: 100 items per page
- Customizable via `per_page` query parameter

---

### 3.5 Soft Deletes
**Decision**: Soft deletes for critical data

**Tables with Soft Deletes**:
- users
- students
- employees
- guardians
- messages

**Hard Deletes**:
- attendance records (immutable)
- payments (immutable)
- activity_logs (archived, not deleted)

---

### 3.6 Queue Driver
**Decision**:
- **Development**: Database queue
- **Production**: Redis queue
- **Alternative**: AWS SQS for scale

---

### 3.7 Real-time Features (Future)
**Proposal**: Laravel Reverb / Pusher for real-time updates

**Use Cases**:
- Live attendance updates
- Real-time chat between teachers and parents
- Live notifications

**MVP**: Polling (30-second intervals for notifications)

---

## 4. Recommended Enhancements (Not in PRD)

### 4.1 Daily Reports for Parents
**Proposal**: Automated daily summary sent at end of day

**Content**:
- Attendance status
- Meals consumed
- Nap time
- Activities participated
- Today's photos
- Any incidents/notes

**Channel**: WhatsApp + Email + In-app

---

### 4.2 Analytics Dashboard
**Proposal**: Advanced analytics for admin

**Metrics**:
- Student enrollment trends
- Attendance patterns
- Financial health (revenue, expenses, profit)
- Class occupancy rates
- Staff attendance trends

**Tools**: Chart.js / Recharts for visualizations

---

### 4.3 Parent Feedback System
**Proposal**: Parent satisfaction surveys

**Features**:
- Periodic satisfaction surveys
- Event feedback forms
- General suggestion box
- Rating system for services

---

### 4.4 Transportation Module
**Proposal**: Bus tracking and pickup management

**Features** (Future):
- Bus routes
- Student assignments to buses
- Pickup/dropoff time tracking
- Parent notifications
- GPS tracking integration

---

### 4.5 Meal Planning
**Proposal**: Weekly meal menu display

**Features**:
- Weekly meal plan creation
- Dietary restriction tracking
- Meal consumption tracking
- Parent visibility

---

## 5. Questions for Client

### High Priority
1. **Which payment gateway is preferred?** Paymob, Stripe, or both?
2. **WhatsApp integration**: Do you have a WhatsApp Business API account, or should we use a service provider?
3. **Evaluation criteria**: Do you have existing evaluation forms/criteria to digitize?
4. **Academic calendar**: How many terms/semesters per year?
5. **Fee structure**: Monthly? Term-based? Annual? Registration fees?

### Medium Priority
6. What file formats do you typically receive for student documents?
7. Are there any specific compliance requirements (data privacy, etc.)?
8. What are the typical class sizes?
9. Do you need support for multiple currencies?
10. What languages should the system support besides English?

### Low Priority
11. Do you need transportation management in phase 1?
12. Do you need meal planning features?
13. Do you want SMS notifications in addition to WhatsApp?
14. Any branding guidelines (colors, logo)?
15. Preferred hosting provider (DigitalOcean, AWS, other)?

---

## 6. Implementation Priorities

### Phase 1 (MVP - 8 weeks)
✅ Authentication & RBAC
✅ Student Management
✅ Guardian Management
✅ Class Management
✅ Attendance (Students & Teachers)
✅ Basic Finance (Invoices, Payments)
✅ Admin Dashboard (core features)
✅ Parent App (view-only features)

### Phase 2 (4 weeks)
- Evaluations & Progress Reports
- Events & Trips
- Online Admissions
- Payment Gateway Integration
- WhatsApp Notifications

### Phase 3 (4 weeks)
- HR & Payroll
- Inventory & Assets
- Expenses Management
- Advanced Reports
- Email Notifications

### Phase 4 (Future)
- Multi-branch support
- Transportation module
- Meal planning
- Real-time features
- Mobile native apps (if needed)

---

## 7. Development Standards

### Code Quality
- **PSR-12** coding standards (PHP)
- **ESLint + Prettier** (JavaScript/TypeScript)
- **Type safety**: PHP 8.3 types, TypeScript strict mode
- **Code coverage**: Target 80%+
- **Documentation**: PHPDoc, JSDoc

### Git Workflow
- **Main branch**: Production-ready code
- **Develop branch**: Integration branch
- **Feature branches**: `feature/module-name`
- **Hotfix branches**: `hotfix/issue-description`
- **Pull requests**: Required with code review

### Testing
- **Unit tests**: Models, Services
- **Feature tests**: API endpoints
- **E2E tests**: Critical user flows
- **Browser tests**: Laravel Dusk (optional)

---

**Last Updated**: 2024-01-15
**Document Status**: Ready for Review

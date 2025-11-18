# Entity Relationship Diagram (ERD) - SNMS

## Overview
This document details all database tables, fields, data types, constraints, and relationships for the Steps Nursery Management System.

## Database Conventions
- **Primary Key**: `id` (BIGINT UNSIGNED, AUTO_INCREMENT)
- **Foreign Keys**: `{table}_id` (BIGINT UNSIGNED)
- **Timestamps**: `created_at`, `updated_at` (TIMESTAMP)
- **Soft Deletes**: `deleted_at` (TIMESTAMP NULL)
- **Character Set**: utf8mb4_unicode_ci
- **Naming**: snake_case for tables and columns

---

## 1. Users & Authentication

### 1.1 users
**Purpose**: Core authentication table for all system users

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| name | VARCHAR(255) | NOT NULL | Full name |
| email | VARCHAR(255) | NOT NULL, UNIQUE | Email address |
| phone | VARCHAR(20) | NULLABLE, UNIQUE | Phone number |
| email_verified_at | TIMESTAMP | NULLABLE | Email verification timestamp |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| avatar | VARCHAR(255) | NULLABLE | Profile picture URL |
| is_active | BOOLEAN | DEFAULT TRUE | Account status |
| last_login_at | TIMESTAMP | NULLABLE | Last login timestamp |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- PRIMARY KEY (id)
- UNIQUE KEY (email)
- INDEX (phone)

---

### 1.2 roles
**Purpose**: Define user roles in the system

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| name | VARCHAR(50) | NOT NULL, UNIQUE | Role name (super_admin, admin, teacher, etc.) |
| display_name | VARCHAR(100) | NOT NULL | Human-readable name |
| description | TEXT | NULLABLE | Role description |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Default Roles**:
- super_admin
- admin
- teacher
- reception
- hr_officer
- accountant
- parent

---

### 1.3 permissions
**Purpose**: Define granular permissions

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| name | VARCHAR(100) | NOT NULL, UNIQUE | Permission name (students.view, students.create) |
| module | VARCHAR(50) | NOT NULL | Module name (students, attendance, etc.) |
| description | TEXT | NULLABLE | Permission description |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (module)

---

### 1.4 role_user
**Purpose**: Many-to-many relationship between users and roles

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| user_id | BIGINT UNSIGNED | NOT NULL, FK → users.id | User reference |
| role_id | BIGINT UNSIGNED | NOT NULL, FK → roles.id | Role reference |
| created_at | TIMESTAMP | NOT NULL | Assignment timestamp |

**Indexes**:
- UNIQUE KEY (user_id, role_id)
- INDEX (user_id)
- INDEX (role_id)

---

### 1.5 permission_role
**Purpose**: Many-to-many relationship between roles and permissions

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| role_id | BIGINT UNSIGNED | NOT NULL, FK → roles.id | Role reference |
| permission_id | BIGINT UNSIGNED | NOT NULL, FK → permissions.id | Permission reference |
| created_at | TIMESTAMP | NOT NULL | Assignment timestamp |

**Indexes**:
- UNIQUE KEY (role_id, permission_id)
- INDEX (role_id)
- INDEX (permission_id)

---

## 2. Student Management

### 2.1 guardians (parents)
**Purpose**: Store parent/guardian information

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| user_id | BIGINT UNSIGNED | NULLABLE, FK → users.id | Linked user account |
| father_name | VARCHAR(255) | NULLABLE | Father's full name |
| father_phone | VARCHAR(20) | NULLABLE | Father's phone |
| father_email | VARCHAR(255) | NULLABLE | Father's email |
| father_occupation | VARCHAR(100) | NULLABLE | Father's occupation |
| father_national_id | VARCHAR(50) | NULLABLE | Father's national ID |
| mother_name | VARCHAR(255) | NULLABLE | Mother's full name |
| mother_phone | VARCHAR(20) | NULLABLE | Mother's phone |
| mother_email | VARCHAR(255) | NULLABLE | Mother's email |
| mother_occupation | VARCHAR(100) | NULLABLE | Mother's occupation |
| mother_national_id | VARCHAR(50) | NULLABLE | Mother's national ID |
| address | TEXT | NULLABLE | Home address |
| city | VARCHAR(100) | NULLABLE | City |
| district | VARCHAR(100) | NULLABLE | District/Area |
| emergency_contact_name | VARCHAR(255) | NULLABLE | Emergency contact name |
| emergency_contact_phone | VARCHAR(20) | NULLABLE | Emergency contact phone |
| emergency_contact_relation | VARCHAR(50) | NULLABLE | Relation to child |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (user_id)
- INDEX (father_phone)
- INDEX (mother_phone)

---

### 2.2 classes
**Purpose**: Define nursery classes/groups

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| name | VARCHAR(100) | NOT NULL | Class name (e.g., "Toddlers A", "Pre-K B") |
| age_group | VARCHAR(50) | NULLABLE | Age range (e.g., "2-3 years") |
| capacity | INT UNSIGNED | NOT NULL | Maximum students |
| teacher_id | BIGINT UNSIGNED | NULLABLE, FK → users.id | Main teacher |
| assistant_teacher_id | BIGINT UNSIGNED | NULLABLE, FK → users.id | Assistant teacher |
| room_number | VARCHAR(50) | NULLABLE | Physical room number |
| academic_year | VARCHAR(10) | NOT NULL | Academic year (e.g., "2024-2025") |
| is_active | BOOLEAN | DEFAULT TRUE | Active status |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (teacher_id)
- INDEX (assistant_teacher_id)
- INDEX (academic_year)

---

### 2.3 students
**Purpose**: Store student information

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| student_code | VARCHAR(20) | NOT NULL, UNIQUE | Unique student identifier |
| first_name | VARCHAR(100) | NOT NULL | First name |
| last_name | VARCHAR(100) | NOT NULL | Last name |
| arabic_name | VARCHAR(255) | NULLABLE | Arabic name |
| date_of_birth | DATE | NOT NULL | Birth date |
| gender | ENUM('male', 'female') | NOT NULL | Gender |
| nationality | VARCHAR(100) | NULLABLE | Nationality |
| national_id | VARCHAR(50) | NULLABLE | National ID/Passport |
| birth_certificate_no | VARCHAR(50) | NULLABLE | Birth certificate number |
| photo | VARCHAR(255) | NULLABLE | Profile photo URL |
| class_id | BIGINT UNSIGNED | NULLABLE, FK → classes.id | Current class |
| guardian_id | BIGINT UNSIGNED | NOT NULL, FK → guardians.id | Guardian reference |
| enrollment_date | DATE | NOT NULL | Enrollment date |
| status | ENUM('active', 'graduated', 'withdrawn', 'waiting') | DEFAULT 'active' | Current status |
| medical_notes | TEXT | NULLABLE | Medical conditions/allergies |
| special_needs | TEXT | NULLABLE | Special needs/requirements |
| blood_type | VARCHAR(5) | NULLABLE | Blood type |
| has_special_diet | BOOLEAN | DEFAULT FALSE | Special diet flag |
| diet_notes | TEXT | NULLABLE | Diet restrictions |
| pickup_authorized_persons | JSON | NULLABLE | List of authorized pickup persons |
| notes | TEXT | NULLABLE | Additional notes |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |
| deleted_at | TIMESTAMP | NULLABLE | Soft delete timestamp |

**Indexes**:
- UNIQUE KEY (student_code)
- INDEX (class_id)
- INDEX (guardian_id)
- INDEX (status)
- INDEX (enrollment_date)

---

### 2.4 student_documents
**Purpose**: Store student-related documents

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| student_id | BIGINT UNSIGNED | NOT NULL, FK → students.id | Student reference |
| document_type | VARCHAR(50) | NOT NULL | Type (birth_cert, medical, photo, etc.) |
| file_name | VARCHAR(255) | NOT NULL | Original filename |
| file_path | VARCHAR(500) | NOT NULL | Storage path/URL |
| file_size | INT UNSIGNED | NULLABLE | File size in bytes |
| mime_type | VARCHAR(100) | NULLABLE | MIME type |
| uploaded_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Uploader |
| created_at | TIMESTAMP | NOT NULL | Upload timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (student_id)
- INDEX (document_type)

---

## 3. Admissions

### 3.1 admission_applications
**Purpose**: Online admission application tracking

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| application_number | VARCHAR(20) | NOT NULL, UNIQUE | Unique application ID |
| child_first_name | VARCHAR(100) | NOT NULL | Child's first name |
| child_last_name | VARCHAR(100) | NOT NULL | Child's last name |
| child_date_of_birth | DATE | NOT NULL | Child's DOB |
| child_gender | ENUM('male', 'female') | NOT NULL | Child's gender |
| preferred_class_id | BIGINT UNSIGNED | NULLABLE, FK → classes.id | Preferred class |
| guardian_name | VARCHAR(255) | NOT NULL | Guardian name |
| guardian_phone | VARCHAR(20) | NOT NULL | Guardian phone |
| guardian_email | VARCHAR(255) | NOT NULL | Guardian email |
| guardian_address | TEXT | NULLABLE | Address |
| status | ENUM('new', 'in_review', 'interview_scheduled', 'accepted', 'rejected', 'waitlisted') | DEFAULT 'new' | Application status |
| interview_date | DATETIME | NULLABLE | Scheduled interview |
| interviewed_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Interviewer |
| interview_notes | TEXT | NULLABLE | Interview feedback |
| rejection_reason | TEXT | NULLABLE | Rejection reason |
| assigned_to | BIGINT UNSIGNED | NULLABLE, FK → users.id | Staff assigned |
| notes | TEXT | NULLABLE | Internal notes |
| submitted_at | TIMESTAMP | NULLABLE | Submission timestamp |
| reviewed_at | TIMESTAMP | NULLABLE | Review timestamp |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- UNIQUE KEY (application_number)
- INDEX (status)
- INDEX (preferred_class_id)
- INDEX (interview_date)
- INDEX (submitted_at)

---

### 3.2 admission_documents
**Purpose**: Documents attached to admission applications

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| application_id | BIGINT UNSIGNED | NOT NULL, FK → admission_applications.id | Application reference |
| document_type | VARCHAR(50) | NOT NULL | Document type |
| file_name | VARCHAR(255) | NOT NULL | Original filename |
| file_path | VARCHAR(500) | NOT NULL | Storage path |
| file_size | INT UNSIGNED | NULLABLE | File size |
| created_at | TIMESTAMP | NOT NULL | Upload timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (application_id)

---

## 4. Attendance

### 4.1 student_attendance
**Purpose**: Daily student attendance records

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| student_id | BIGINT UNSIGNED | NOT NULL, FK → students.id | Student reference |
| class_id | BIGINT UNSIGNED | NOT NULL, FK → classes.id | Class reference |
| date | DATE | NOT NULL | Attendance date |
| status | ENUM('present', 'absent', 'late', 'excused', 'half_day') | NOT NULL | Attendance status |
| check_in_time | TIME | NULLABLE | Check-in time |
| check_out_time | TIME | NULLABLE | Check-out time |
| checked_in_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Staff who checked in |
| checked_out_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Staff who checked out |
| temperature | DECIMAL(3, 1) | NULLABLE | Temperature check |
| notes | TEXT | NULLABLE | Additional notes |
| marked_by | BIGINT UNSIGNED | NOT NULL, FK → users.id | Teacher who marked |
| parent_notified | BOOLEAN | DEFAULT FALSE | Parent notification sent |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- UNIQUE KEY (student_id, date)
- INDEX (class_id, date)
- INDEX (date)
- INDEX (status)

---

### 4.2 teacher_attendance
**Purpose**: Staff/teacher attendance records

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| user_id | BIGINT UNSIGNED | NOT NULL, FK → users.id | Teacher/staff reference |
| date | DATE | NOT NULL | Attendance date |
| status | ENUM('present', 'absent', 'late', 'on_leave', 'half_day') | NOT NULL | Attendance status |
| check_in_time | TIME | NULLABLE | Check-in time |
| check_out_time | TIME | NULLABLE | Check-out time |
| work_hours | DECIMAL(4, 2) | NULLABLE | Total work hours |
| overtime_hours | DECIMAL(4, 2) | DEFAULT 0 | Overtime hours |
| notes | TEXT | NULLABLE | Notes |
| marked_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Admin who marked |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- UNIQUE KEY (user_id, date)
- INDEX (date)
- INDEX (status)

---

## 5. Evaluations & Progress

### 5.1 evaluation_templates
**Purpose**: Define evaluation criteria templates

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| name | VARCHAR(255) | NOT NULL | Template name |
| type | ENUM('monthly', 'weekly', 'term', 'annual') | NOT NULL | Evaluation frequency |
| age_group | VARCHAR(50) | NULLABLE | Applicable age group |
| is_active | BOOLEAN | DEFAULT TRUE | Active status |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

### 5.2 evaluation_criteria
**Purpose**: Specific criteria within evaluation templates

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| template_id | BIGINT UNSIGNED | NOT NULL, FK → evaluation_templates.id | Template reference |
| category | VARCHAR(100) | NOT NULL | Category (cognitive, social, motor, etc.) |
| skill_name | VARCHAR(255) | NOT NULL | Skill being evaluated |
| description | TEXT | NULLABLE | Skill description |
| max_score | INT UNSIGNED | DEFAULT 5 | Maximum score |
| display_order | INT UNSIGNED | DEFAULT 0 | Display order |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (template_id)
- INDEX (category)

---

### 5.3 student_evaluations
**Purpose**: Student evaluation records

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| student_id | BIGINT UNSIGNED | NOT NULL, FK → students.id | Student reference |
| template_id | BIGINT UNSIGNED | NOT NULL, FK → evaluation_templates.id | Template reference |
| evaluation_date | DATE | NOT NULL | Evaluation date |
| period | VARCHAR(50) | NOT NULL | Period (Jan 2024, Week 1, etc.) |
| evaluated_by | BIGINT UNSIGNED | NOT NULL, FK → users.id | Teacher reference |
| overall_comment | TEXT | NULLABLE | General comments |
| is_published | BOOLEAN | DEFAULT FALSE | Visible to parents |
| published_at | TIMESTAMP | NULLABLE | Publication timestamp |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (student_id, evaluation_date)
- INDEX (template_id)
- INDEX (evaluated_by)

---

### 5.4 evaluation_scores
**Purpose**: Detailed scores for each criterion

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| evaluation_id | BIGINT UNSIGNED | NOT NULL, FK → student_evaluations.id | Evaluation reference |
| criteria_id | BIGINT UNSIGNED | NOT NULL, FK → evaluation_criteria.id | Criteria reference |
| score | INT UNSIGNED | NOT NULL | Score achieved |
| notes | TEXT | NULLABLE | Specific notes |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (evaluation_id)
- INDEX (criteria_id)

---

### 5.5 evaluation_media
**Purpose**: Photos/videos attached to evaluations

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| evaluation_id | BIGINT UNSIGNED | NOT NULL, FK → student_evaluations.id | Evaluation reference |
| media_type | ENUM('photo', 'video', 'document') | NOT NULL | Media type |
| file_name | VARCHAR(255) | NOT NULL | Original filename |
| file_path | VARCHAR(500) | NOT NULL | Storage path |
| file_size | INT UNSIGNED | NULLABLE | File size |
| caption | TEXT | NULLABLE | Media caption |
| created_at | TIMESTAMP | NOT NULL | Upload timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (evaluation_id)

---

## 6. Events & Journeys

### 6.1 events
**Purpose**: Nursery events and trips

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| title | VARCHAR(255) | NOT NULL | Event title |
| description | TEXT | NULLABLE | Event description |
| type | ENUM('trip', 'celebration', 'parent_meeting', 'workshop', 'other') | NOT NULL | Event type |
| event_date | DATE | NOT NULL | Event date |
| start_time | TIME | NULLABLE | Start time |
| end_time | TIME | NULLABLE | End time |
| location | VARCHAR(255) | NULLABLE | Event location |
| target_classes | JSON | NULLABLE | Array of class IDs |
| requires_permission | BOOLEAN | DEFAULT FALSE | Requires parent consent |
| permission_deadline | DATE | NULLABLE | Consent deadline |
| has_fee | BOOLEAN | DEFAULT FALSE | Requires payment |
| fee_amount | DECIMAL(10, 2) | NULLABLE | Fee amount |
| max_participants | INT UNSIGNED | NULLABLE | Maximum participants |
| created_by | BIGINT UNSIGNED | NOT NULL, FK → users.id | Creator |
| status | ENUM('draft', 'published', 'cancelled', 'completed') | DEFAULT 'draft' | Event status |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (event_date)
- INDEX (type)
- INDEX (status)

---

### 6.2 event_participants
**Purpose**: Track student participation in events

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| event_id | BIGINT UNSIGNED | NOT NULL, FK → events.id | Event reference |
| student_id | BIGINT UNSIGNED | NOT NULL, FK → students.id | Student reference |
| permission_status | ENUM('pending', 'approved', 'declined') | DEFAULT 'pending' | Parent consent |
| permission_given_at | TIMESTAMP | NULLABLE | Consent timestamp |
| payment_status | ENUM('pending', 'paid', 'waived') | DEFAULT 'pending' | Payment status |
| payment_id | BIGINT UNSIGNED | NULLABLE, FK → payments.id | Payment reference |
| attendance_status | ENUM('present', 'absent', 'not_marked') | DEFAULT 'not_marked' | Actual attendance |
| notes | TEXT | NULLABLE | Notes |
| created_at | TIMESTAMP | NOT NULL | Registration timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- UNIQUE KEY (event_id, student_id)
- INDEX (student_id)
- INDEX (permission_status)
- INDEX (payment_status)

---

## 7. HR & Payroll

### 7.1 employees
**Purpose**: Employee records (extends users table)

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| user_id | BIGINT UNSIGNED | NOT NULL, UNIQUE, FK → users.id | User account |
| employee_code | VARCHAR(20) | NOT NULL, UNIQUE | Employee ID |
| national_id | VARCHAR(50) | NULLABLE | National ID |
| date_of_birth | DATE | NULLABLE | Birth date |
| gender | ENUM('male', 'female') | NULLABLE | Gender |
| marital_status | ENUM('single', 'married', 'divorced', 'widowed') | NULLABLE | Marital status |
| address | TEXT | NULLABLE | Address |
| city | VARCHAR(100) | NULLABLE | City |
| emergency_contact_name | VARCHAR(255) | NULLABLE | Emergency contact |
| emergency_contact_phone | VARCHAR(20) | NULLABLE | Emergency phone |
| department | VARCHAR(100) | NULLABLE | Department (teaching, admin, etc.) |
| position | VARCHAR(100) | NULLABLE | Job position |
| specialization | VARCHAR(255) | NULLABLE | Specialization/qualifications |
| contract_type | ENUM('full_time', 'part_time', 'contract', 'intern') | NOT NULL | Employment type |
| contract_start_date | DATE | NOT NULL | Contract start |
| contract_end_date | DATE | NULLABLE | Contract end (if applicable) |
| base_salary | DECIMAL(10, 2) | NOT NULL | Monthly base salary |
| allowances | JSON | NULLABLE | Additional allowances |
| bank_name | VARCHAR(100) | NULLABLE | Bank name |
| bank_account_number | VARCHAR(50) | NULLABLE | Account number |
| bank_iban | VARCHAR(50) | NULLABLE | IBAN |
| status | ENUM('active', 'on_leave', 'suspended', 'terminated') | DEFAULT 'active' | Employment status |
| termination_date | DATE | NULLABLE | Termination date |
| termination_reason | TEXT | NULLABLE | Termination reason |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |
| deleted_at | TIMESTAMP | NULLABLE | Soft delete timestamp |

**Indexes**:
- UNIQUE KEY (employee_code)
- INDEX (user_id)
- INDEX (status)
- INDEX (department)

---

### 7.2 employee_documents
**Purpose**: Employee-related documents

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| employee_id | BIGINT UNSIGNED | NOT NULL, FK → employees.id | Employee reference |
| document_type | VARCHAR(50) | NOT NULL | Type (contract, certificate, id, etc.) |
| file_name | VARCHAR(255) | NOT NULL | Original filename |
| file_path | VARCHAR(500) | NOT NULL | Storage path |
| expiry_date | DATE | NULLABLE | Document expiry |
| uploaded_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Uploader |
| created_at | TIMESTAMP | NOT NULL | Upload timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (employee_id)
- INDEX (expiry_date)

---

### 7.3 leave_requests
**Purpose**: Employee leave management

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| employee_id | BIGINT UNSIGNED | NOT NULL, FK → employees.id | Employee reference |
| leave_type | ENUM('annual', 'sick', 'emergency', 'unpaid', 'maternity', 'other') | NOT NULL | Leave type |
| start_date | DATE | NOT NULL | Leave start |
| end_date | DATE | NOT NULL | Leave end |
| total_days | INT UNSIGNED | NOT NULL | Total leave days |
| reason | TEXT | NULLABLE | Leave reason |
| status | ENUM('pending', 'approved', 'rejected', 'cancelled') | DEFAULT 'pending' | Request status |
| approved_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Approver |
| approval_notes | TEXT | NULLABLE | Approval/rejection notes |
| approved_at | TIMESTAMP | NULLABLE | Approval timestamp |
| created_at | TIMESTAMP | NOT NULL | Request timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (employee_id)
- INDEX (status)
- INDEX (start_date, end_date)

---

### 7.4 payroll
**Purpose**: Monthly payroll records

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| employee_id | BIGINT UNSIGNED | NOT NULL, FK → employees.id | Employee reference |
| month | VARCHAR(7) | NOT NULL | Month (YYYY-MM) |
| base_salary | DECIMAL(10, 2) | NOT NULL | Base salary |
| allowances | DECIMAL(10, 2) | DEFAULT 0 | Total allowances |
| overtime_amount | DECIMAL(10, 2) | DEFAULT 0 | Overtime pay |
| bonuses | DECIMAL(10, 2) | DEFAULT 0 | Bonuses |
| deductions | DECIMAL(10, 2) | DEFAULT 0 | Total deductions |
| tax | DECIMAL(10, 2) | DEFAULT 0 | Tax amount |
| insurance | DECIMAL(10, 2) | DEFAULT 0 | Insurance deduction |
| gross_salary | DECIMAL(10, 2) | NOT NULL | Gross salary |
| net_salary | DECIMAL(10, 2) | NOT NULL | Net salary |
| payment_date | DATE | NULLABLE | Payment date |
| payment_method | ENUM('bank_transfer', 'cash', 'cheque') | NULLABLE | Payment method |
| payment_reference | VARCHAR(100) | NULLABLE | Transaction reference |
| status | ENUM('draft', 'approved', 'paid') | DEFAULT 'draft' | Payroll status |
| payslip_path | VARCHAR(500) | NULLABLE | Payslip PDF path |
| notes | TEXT | NULLABLE | Notes |
| generated_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Generated by |
| approved_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Approved by |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- UNIQUE KEY (employee_id, month)
- INDEX (month)
- INDEX (status)
- INDEX (payment_date)

---

## 8. Accounting & Finance

### 8.1 fee_structures
**Purpose**: Define tuition fee structures

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| name | VARCHAR(255) | NOT NULL | Fee structure name |
| class_id | BIGINT UNSIGNED | NULLABLE, FK → classes.id | Applicable class |
| academic_year | VARCHAR(10) | NOT NULL | Academic year |
| fee_type | ENUM('monthly', 'term', 'annual', 'registration', 'activity') | NOT NULL | Fee type |
| amount | DECIMAL(10, 2) | NOT NULL | Fee amount |
| is_active | BOOLEAN | DEFAULT TRUE | Active status |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (class_id)
- INDEX (academic_year)

---

### 8.2 invoices
**Purpose**: Financial invoices

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| invoice_number | VARCHAR(20) | NOT NULL, UNIQUE | Unique invoice number |
| student_id | BIGINT UNSIGNED | NOT NULL, FK → students.id | Student reference |
| guardian_id | BIGINT UNSIGNED | NOT NULL, FK → guardians.id | Guardian reference |
| invoice_type | ENUM('tuition', 'registration', 'event', 'transportation', 'meal', 'other') | NOT NULL | Invoice type |
| description | TEXT | NULLABLE | Invoice description |
| amount | DECIMAL(10, 2) | NOT NULL | Total amount |
| discount | DECIMAL(10, 2) | DEFAULT 0 | Discount amount |
| tax | DECIMAL(10, 2) | DEFAULT 0 | Tax amount |
| total_amount | DECIMAL(10, 2) | NOT NULL | Final amount |
| due_date | DATE | NOT NULL | Payment due date |
| status | ENUM('draft', 'sent', 'paid', 'partial', 'overdue', 'cancelled') | DEFAULT 'draft' | Invoice status |
| issued_date | DATE | NOT NULL | Issue date |
| paid_amount | DECIMAL(10, 2) | DEFAULT 0 | Amount paid |
| balance | DECIMAL(10, 2) | NOT NULL | Remaining balance |
| notes | TEXT | NULLABLE | Notes |
| created_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Creator |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- UNIQUE KEY (invoice_number)
- INDEX (student_id)
- INDEX (guardian_id)
- INDEX (status)
- INDEX (due_date)

---

### 8.3 payments
**Purpose**: Payment transactions

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| payment_number | VARCHAR(20) | NOT NULL, UNIQUE | Unique payment number |
| invoice_id | BIGINT UNSIGNED | NOT NULL, FK → invoices.id | Invoice reference |
| amount | DECIMAL(10, 2) | NOT NULL | Payment amount |
| payment_method | ENUM('cash', 'card', 'bank_transfer', 'online', 'cheque') | NOT NULL | Payment method |
| payment_date | DATE | NOT NULL | Payment date |
| transaction_reference | VARCHAR(100) | NULLABLE | External transaction ID |
| payment_gateway | VARCHAR(50) | NULLABLE | Gateway (Paymob, Stripe) |
| status | ENUM('pending', 'completed', 'failed', 'refunded') | DEFAULT 'pending' | Payment status |
| notes | TEXT | NULLABLE | Notes |
| received_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Staff who received |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- UNIQUE KEY (payment_number)
- INDEX (invoice_id)
- INDEX (status)
- INDEX (payment_date)

---

### 8.4 expenses
**Purpose**: Nursery expenses tracking

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| expense_number | VARCHAR(20) | NOT NULL, UNIQUE | Unique expense number |
| category | VARCHAR(100) | NOT NULL | Expense category |
| subcategory | VARCHAR(100) | NULLABLE | Subcategory |
| description | TEXT | NOT NULL | Expense description |
| amount | DECIMAL(10, 2) | NOT NULL | Expense amount |
| expense_date | DATE | NOT NULL | Expense date |
| payment_method | ENUM('cash', 'card', 'bank_transfer', 'cheque') | NOT NULL | Payment method |
| vendor_name | VARCHAR(255) | NULLABLE | Vendor/supplier |
| receipt_number | VARCHAR(100) | NULLABLE | Receipt number |
| receipt_path | VARCHAR(500) | NULLABLE | Receipt file path |
| status | ENUM('pending', 'approved', 'paid', 'rejected') | DEFAULT 'pending' | Expense status |
| approved_by | BIGINT UNSIGNED | NULLABLE, FK → users.id | Approver |
| recorded_by | BIGINT UNSIGNED | NOT NULL, FK → users.id | Recorded by |
| notes | TEXT | NULLABLE | Notes |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- UNIQUE KEY (expense_number)
- INDEX (category)
- INDEX (expense_date)
- INDEX (status)

---

## 9. Inventory & Assets

### 9.1 stock_items
**Purpose**: Inventory management

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| item_code | VARCHAR(50) | NOT NULL, UNIQUE | Item code/SKU |
| name | VARCHAR(255) | NOT NULL | Item name |
| category | VARCHAR(100) | NOT NULL | Category (uniform, stationery, kitchen, etc.) |
| description | TEXT | NULLABLE | Item description |
| unit | VARCHAR(50) | NOT NULL | Unit (piece, box, kg, etc.) |
| quantity | INT UNSIGNED | NOT NULL | Current quantity |
| min_quantity | INT UNSIGNED | NOT NULL | Minimum stock level |
| max_quantity | INT UNSIGNED | NULLABLE | Maximum stock level |
| unit_price | DECIMAL(10, 2) | NULLABLE | Unit price |
| supplier_name | VARCHAR(255) | NULLABLE | Supplier name |
| supplier_contact | VARCHAR(100) | NULLABLE | Supplier contact |
| location | VARCHAR(100) | NULLABLE | Storage location |
| status | ENUM('available', 'low_stock', 'out_of_stock', 'discontinued') | DEFAULT 'available' | Stock status |
| last_restock_date | DATE | NULLABLE | Last restock date |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- UNIQUE KEY (item_code)
- INDEX (category)
- INDEX (status)

---

### 9.2 stock_movements
**Purpose**: Track inventory movements

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| stock_item_id | BIGINT UNSIGNED | NOT NULL, FK → stock_items.id | Item reference |
| movement_type | ENUM('purchase', 'issue', 'return', 'adjustment', 'damage') | NOT NULL | Movement type |
| quantity | INT | NOT NULL | Quantity (+ or -) |
| previous_quantity | INT UNSIGNED | NOT NULL | Stock before movement |
| new_quantity | INT UNSIGNED | NOT NULL | Stock after movement |
| unit_cost | DECIMAL(10, 2) | NULLABLE | Cost per unit |
| total_cost | DECIMAL(10, 2) | NULLABLE | Total cost |
| reference_number | VARCHAR(100) | NULLABLE | PO/Invoice number |
| reason | TEXT | NULLABLE | Movement reason |
| performed_by | BIGINT UNSIGNED | NOT NULL, FK → users.id | User who performed |
| movement_date | DATE | NOT NULL | Movement date |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (stock_item_id)
- INDEX (movement_date)
- INDEX (movement_type)

---

### 9.3 assets
**Purpose**: Asset management (equipment, furniture, etc.)

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| asset_code | VARCHAR(50) | NOT NULL, UNIQUE | Asset code/tag |
| name | VARCHAR(255) | NOT NULL | Asset name |
| category | VARCHAR(100) | NOT NULL | Category (furniture, electronics, etc.) |
| description | TEXT | NULLABLE | Asset description |
| brand | VARCHAR(100) | NULLABLE | Brand/manufacturer |
| model | VARCHAR(100) | NULLABLE | Model number |
| serial_number | VARCHAR(100) | NULLABLE | Serial number |
| purchase_date | DATE | NOT NULL | Purchase date |
| purchase_price | DECIMAL(10, 2) | NOT NULL | Purchase price |
| current_value | DECIMAL(10, 2) | NULLABLE | Current value |
| supplier | VARCHAR(255) | NULLABLE | Supplier |
| warranty_expiry | DATE | NULLABLE | Warranty expiry |
| location | VARCHAR(100) | NULLABLE | Physical location |
| assigned_to | BIGINT UNSIGNED | NULLABLE, FK → users.id | Assigned user |
| condition | ENUM('excellent', 'good', 'fair', 'poor', 'damaged') | DEFAULT 'good' | Asset condition |
| status | ENUM('active', 'inactive', 'maintenance', 'disposed') | DEFAULT 'active' | Asset status |
| disposal_date | DATE | NULLABLE | Disposal date |
| disposal_reason | TEXT | NULLABLE | Disposal reason |
| notes | TEXT | NULLABLE | Notes |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- UNIQUE KEY (asset_code)
- INDEX (category)
- INDEX (status)
- INDEX (assigned_to)

---

## 10. Communication & Notifications

### 10.1 notifications
**Purpose**: System notifications

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| user_id | BIGINT UNSIGNED | NOT NULL, FK → users.id | Recipient |
| type | VARCHAR(100) | NOT NULL | Notification type |
| title | VARCHAR(255) | NOT NULL | Notification title |
| message | TEXT | NOT NULL | Notification message |
| action_url | VARCHAR(500) | NULLABLE | Action link |
| is_read | BOOLEAN | DEFAULT FALSE | Read status |
| read_at | TIMESTAMP | NULLABLE | Read timestamp |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (user_id, is_read)
- INDEX (created_at)

---

### 10.2 messages
**Purpose**: Internal messaging system

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| sender_id | BIGINT UNSIGNED | NOT NULL, FK → users.id | Sender |
| recipient_id | BIGINT UNSIGNED | NOT NULL, FK → users.id | Recipient |
| subject | VARCHAR(255) | NOT NULL | Message subject |
| body | TEXT | NOT NULL | Message body |
| is_read | BOOLEAN | DEFAULT FALSE | Read status |
| read_at | TIMESTAMP | NULLABLE | Read timestamp |
| parent_message_id | BIGINT UNSIGNED | NULLABLE, FK → messages.id | Reply to message |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |
| deleted_at | TIMESTAMP | NULLABLE | Soft delete timestamp |

**Indexes**:
- INDEX (sender_id)
- INDEX (recipient_id)
- INDEX (parent_message_id)

---

### 10.3 announcements
**Purpose**: Broadcast announcements

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| title | VARCHAR(255) | NOT NULL | Announcement title |
| content | TEXT | NOT NULL | Announcement content |
| type | ENUM('general', 'urgent', 'event', 'holiday', 'maintenance') | DEFAULT 'general' | Announcement type |
| target_audience | JSON | NULLABLE | Target roles/classes |
| attachment_path | VARCHAR(500) | NULLABLE | Attachment file |
| is_published | BOOLEAN | DEFAULT FALSE | Published status |
| published_at | TIMESTAMP | NULLABLE | Publication timestamp |
| expires_at | TIMESTAMP | NULLABLE | Expiry timestamp |
| created_by | BIGINT UNSIGNED | NOT NULL, FK → users.id | Creator |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- INDEX (is_published)
- INDEX (published_at)
- INDEX (expires_at)

---

## 11. Activity Logs & Audit

### 11.1 activity_logs
**Purpose**: System audit trail

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| user_id | BIGINT UNSIGNED | NULLABLE, FK → users.id | User who performed action |
| log_name | VARCHAR(100) | NULLABLE | Log category |
| description | TEXT | NOT NULL | Action description |
| subject_type | VARCHAR(255) | NULLABLE | Model class name |
| subject_id | BIGINT UNSIGNED | NULLABLE | Model ID |
| causer_type | VARCHAR(255) | NULLABLE | User type |
| causer_id | BIGINT UNSIGNED | NULLABLE | User ID |
| properties | JSON | NULLABLE | Additional data |
| ip_address | VARCHAR(45) | NULLABLE | User IP |
| user_agent | TEXT | NULLABLE | Browser info |
| created_at | TIMESTAMP | NOT NULL | Action timestamp |

**Indexes**:
- INDEX (user_id)
- INDEX (subject_type, subject_id)
- INDEX (created_at)

---

## 12. Settings & Configuration

### 12.1 settings
**Purpose**: System configuration

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| key | VARCHAR(100) | NOT NULL, UNIQUE | Setting key |
| value | TEXT | NULLABLE | Setting value |
| type | VARCHAR(50) | DEFAULT 'string' | Data type |
| group | VARCHAR(50) | NOT NULL | Setting group |
| description | TEXT | NULLABLE | Setting description |
| is_public | BOOLEAN | DEFAULT FALSE | Visible to frontend |
| created_at | TIMESTAMP | NOT NULL | Creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

**Indexes**:
- UNIQUE KEY (key)
- INDEX (group)

---

## Entity Relationships Summary

### One-to-Many Relationships
- **users → students** (teacher → students they teach)
- **guardians → students** (one guardian has multiple children)
- **classes → students** (one class has multiple students)
- **users → teacher_attendance** (one teacher has multiple attendance records)
- **students → student_attendance** (one student has multiple attendance records)
- **students → student_evaluations** (one student has multiple evaluations)
- **evaluation_templates → student_evaluations**
- **employees → payroll** (one employee has multiple payroll records)
- **students → invoices** (one student has multiple invoices)
- **invoices → payments** (one invoice can have multiple payments)
- **events → event_participants**
- **stock_items → stock_movements**

### Many-to-Many Relationships
- **users ↔ roles** (via role_user)
- **roles ↔ permissions** (via permission_role)
- **events ↔ students** (via event_participants)

### One-to-One Relationships
- **users ← employees** (one user has one employee record)
- **users ← guardians** (one user can be one guardian)

---

## Database Constraints & Business Rules

1. **Student Code**: Auto-generated, unique (e.g., STU2024001)
2. **Invoice Number**: Auto-generated, unique (e.g., INV-2024-001)
3. **Payment Number**: Auto-generated, unique (e.g., PAY-2024-001)
4. **Employee Code**: Auto-generated, unique (e.g., EMP001)
5. **Attendance**: Unique per student per date
6. **Class Capacity**: Trigger warning when capacity exceeded
7. **Invoice Balance**: Calculated field (total_amount - paid_amount)
8. **Stock Status**: Auto-update based on quantity vs min_quantity
9. **Cascade Deletes**: Soft deletes for students, employees, users
10. **Foreign Key Constraints**: ON DELETE RESTRICT for most relationships

---

## Indexing Strategy

### Primary Indexes (Auto-created)
- All `id` fields

### Unique Indexes
- Email, phone (users)
- Student codes, invoice numbers, payment numbers
- Attendance records (student_id + date)

### Composite Indexes
- (student_id, class_id, date) for attendance queries
- (employee_id, month) for payroll queries
- (invoice_id, status) for payment tracking

### Full-Text Indexes (Optional)
- Student names for search
- Employee names for search
- Announcement content

---

**Total Tables**: 35+
**Core Modules**: 11
**Estimated Database Size**: 10-50GB (for 1000 students over 5 years)

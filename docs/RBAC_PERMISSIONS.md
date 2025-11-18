# RBAC (Role-Based Access Control) - SNMS

## Overview
This document defines the permission matrix for all user roles in the Steps Nursery Management System.

## Role Hierarchy

```
Super Admin (Full System Access)
    │
    ├── Admin (All Operations)
    │
    ├── Teacher (Class & Students)
    │
    ├── Reception (Admissions & Communication)
    │
    ├── HR Officer (Staff Management)
    │
    ├── Accountant (Finance & Accounting)
    │
    └── Parent (Own Children Only)
```

---

## Permission Naming Convention

Format: `{module}.{action}`

**Actions**:
- `view` - View/Read records
- `view_any` - View list of records
- `create` - Create new records
- `update` - Edit existing records
- `delete` - Delete records
- `restore` - Restore soft-deleted records
- `force_delete` - Permanently delete records
- `publish` - Publish content (evaluations, announcements)
- `approve` - Approve requests (leaves, expenses)
- `export` - Export data (reports, PDFs)

---

## 1. Students Module

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `students.view_any` | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ (Own only) |
| `students.view` | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ (Own only) |
| `students.create` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `students.update` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `students.delete` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `students.restore` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `students.upload_photo` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| `students.upload_documents` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `students.export` | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |

**Business Rules**:
- Teachers can only view students in their assigned classes
- Parents can only access their own children's information
- Reception has full CRUD except delete

---

## 2. Guardians Module

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `guardians.view_any` | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| `guardians.view` | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ (Self) |
| `guardians.create` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `guardians.update` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ✅ (Self) |
| `guardians.delete` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

**Business Rules**:
- Parents can update their own contact information
- Teachers can view guardian info for their class students

---

## 3. Classes Module

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `classes.view_any` | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| `classes.view` | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| `classes.create` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `classes.update` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `classes.delete` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `classes.assign_teacher` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `classes.assign_students` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |

---

## 4. Attendance Module

### Student Attendance

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `attendance.students.view_any` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ (Own) |
| `attendance.students.view` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ (Own) |
| `attendance.students.mark` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| `attendance.students.update` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `attendance.students.export` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |

### Teacher Attendance

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `attendance.teachers.view_any` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `attendance.teachers.view` | ✅ | ✅ | ✅ (Self) | ❌ | ✅ | ❌ | ❌ |
| `attendance.teachers.mark` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `attendance.teachers.update` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `attendance.teachers.export` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |

**Business Rules**:
- Teachers can mark attendance for their assigned classes
- Teachers can view their own attendance
- Parents can only view their children's attendance

---

## 5. Evaluations Module

### Evaluation Templates

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `evaluations.templates.view_any` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `evaluations.templates.create` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `evaluations.templates.update` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `evaluations.templates.delete` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

### Student Evaluations

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `evaluations.view_any` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ (Own) |
| `evaluations.view` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ (Own, Published only) |
| `evaluations.create` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `evaluations.update` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `evaluations.delete` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `evaluations.publish` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `evaluations.upload_media` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `evaluations.export` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ (Own) |

**Business Rules**:
- Teachers can create evaluations for their class students
- Parents can only view published evaluations for their children
- Only published evaluations are visible to parents

---

## 6. Events & Journeys Module

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `events.view_any` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ |
| `events.view` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ |
| `events.create` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| `events.update` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| `events.delete` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `events.register_student` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ (Own) |
| `events.manage_permissions` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ (Give own permission) |
| `events.mark_attendance` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| `events.export` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |

**Business Rules**:
- Parents can register their children and give permission
- Teachers can mark attendance for events

---

## 7. Admissions Module

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `admissions.view_any` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `admissions.view` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `admissions.submit` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ✅ (Public) |
| `admissions.update_status` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `admissions.schedule_interview` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `admissions.approve` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `admissions.reject` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `admissions.convert_to_student` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `admissions.export` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |

**Business Rules**:
- Public endpoint for admission submission (no auth required)
- Only Admin can approve/reject applications
- Reception manages the admission pipeline

---

## 8. HR & Payroll Module

### Employees

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `employees.view_any` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `employees.view` | ✅ | ✅ | ✅ (Self) | ❌ | ✅ | ❌ | ❌ |
| `employees.create` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `employees.update` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `employees.delete` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `employees.upload_documents` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `employees.export` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |

### Leave Requests

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `leaves.view_any` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `leaves.view` | ✅ | ✅ | ✅ (Own) | ✅ (Own) | ✅ | ✅ (Own) | ❌ |
| `leaves.create` | ✅ | ✅ | ✅ (Own) | ✅ (Own) | ✅ | ✅ (Own) | ❌ |
| `leaves.update` | ✅ | ✅ | ✅ (Own, Pending only) | ✅ (Own, Pending only) | ✅ | ✅ (Own, Pending only) | ❌ |
| `leaves.cancel` | ✅ | ✅ | ✅ (Own) | ✅ (Own) | ✅ | ✅ (Own) | ❌ |
| `leaves.approve` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `leaves.reject` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |

### Payroll

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `payroll.view_any` | ✅ | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ |
| `payroll.view` | ✅ | ✅ | ✅ (Own) | ✅ (Own) | ✅ | ✅ | ❌ |
| `payroll.generate` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `payroll.approve` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `payroll.mark_paid` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `payroll.download_payslip` | ✅ | ✅ | ✅ (Own) | ✅ (Own) | ✅ | ✅ | ❌ |
| `payroll.export` | ✅ | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ |

**Business Rules**:
- All staff can submit leave requests
- HR Officer approves/rejects leave requests
- HR generates payroll, Accountant approves and processes payment
- Staff can view their own payroll history

---

## 9. Accounting & Finance Module

### Invoices

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `invoices.view_any` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ✅ (Own) |
| `invoices.view` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ✅ (Own) |
| `invoices.create` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `invoices.update` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `invoices.delete` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `invoices.send` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `invoices.cancel` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `invoices.export` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ✅ (Own) |

### Payments

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `payments.view_any` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ✅ (Own) |
| `payments.view` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ✅ (Own) |
| `payments.create` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ✅ (Own invoices) |
| `payments.update` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `payments.refund` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `payments.export` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |

### Expenses

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `expenses.view_any` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `expenses.view` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `expenses.create` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `expenses.update` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `expenses.delete` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `expenses.approve` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `expenses.export` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |

### Financial Reports

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `reports.financial.view` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `reports.financial.export` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |

**Business Rules**:
- Accountant manages all financial operations
- Admin can approve expenses
- Parents can view and pay their own invoices

---

## 10. Inventory & Assets Module

### Stock Management

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `inventory.view_any` | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| `inventory.view` | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| `inventory.create` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `inventory.update` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `inventory.delete` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `inventory.record_movement` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `inventory.export` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |

### Assets

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `assets.view_any` | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ |
| `assets.view` | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ |
| `assets.create` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `assets.update` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `assets.delete` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `assets.assign` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `assets.export` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |

**Business Rules**:
- Teachers can view inventory/assets for classroom needs
- Accountant manages inventory and assets

---

## 11. Communication Module

### Messages

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `messages.view_any` | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `messages.view` | ✅ | ✅ (Own) | ✅ (Own) | ✅ (Own) | ✅ (Own) | ✅ (Own) | ✅ (Own) |
| `messages.create` | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| `messages.delete` | ✅ | ✅ (Own) | ✅ (Own) | ✅ (Own) | ✅ (Own) | ✅ (Own) | ✅ (Own) |

### Announcements

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `announcements.view_any` | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| `announcements.view` | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| `announcements.create` | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `announcements.update` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `announcements.delete` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `announcements.publish` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

### Notifications

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `notifications.view` | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| `notifications.mark_read` | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| `notifications.delete` | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

**Business Rules**:
- All users can send messages to each other
- Only Admin and Reception can create announcements
- Everyone can view announcements targeted to them

---

## 12. Reports & Analytics

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `reports.dashboard` | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| `reports.students` | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ (Own) |
| `reports.attendance` | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ (Own) |
| `reports.evaluations` | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ (Own) |
| `reports.financial` | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `reports.hr` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `reports.export` | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ (Own data) |

---

## 13. System Settings

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `settings.view` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `settings.update` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

---

## 14. User Management

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `users.view_any` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `users.view` | ✅ | ✅ | ✅ (Self) | ✅ (Self) | ✅ | ✅ (Self) | ✅ (Self) |
| `users.create` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `users.update` | ✅ | ✅ | ✅ (Self) | ✅ (Self) | ✅ | ✅ (Self) | ✅ (Self) |
| `users.delete` | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `users.assign_roles` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `users.reset_password` | ✅ | ✅ | ✅ (Self) | ✅ (Self) | ✅ | ✅ (Self) | ✅ (Self) |

---

## 15. Roles & Permissions Management

| Permission | Super Admin | Admin | Teacher | Reception | HR | Accountant | Parent |
|------------|------------|-------|---------|-----------|----|-----------| -------|
| `roles.view_any` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `roles.create` | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `roles.update` | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `roles.delete` | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `permissions.view_any` | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| `permissions.assign` | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |

---

## Permission Scopes & Filters

### Scope Rules

**Teacher Scope**:
- Can only access students in their assigned classes
- Can only mark attendance for their classes
- Can only create evaluations for their students

**Parent Scope**:
- Can only access their own children's data
- Can only view published evaluations
- Can only view attendance for their children

**HR Officer Scope**:
- Can access all employee data
- Cannot access financial data

**Accountant Scope**:
- Can access all financial data
- Cannot access HR-specific data (salaries are shared)

---

## Implementation Guidelines

### 1. Laravel Policy Structure

```php
class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('students.view_any');
    }

    public function view(User $user, Student $student): bool
    {
        if ($user->hasRole('parent')) {
            return $student->guardian_id === $user->guardian->id;
        }

        if ($user->hasRole('teacher')) {
            return $user->classrooms->contains($student->class_id);
        }

        return $user->hasPermission('students.view');
    }
}
```

### 2. Middleware for Route Protection

```php
Route::middleware(['auth:sanctum', 'permission:students.view_any'])
    ->get('/students', [StudentController::class, 'index']);
```

### 3. Database Seeder

All default roles and permissions should be seeded during installation:

```php
php artisan db:seed --class=RolesAndPermissionsSeeder
```

---

## Special Permissions

### Super Admin Bypass
- Super Admin has `*` permission (wildcard) - full access to everything
- Super Admin cannot be deleted
- Only one Super Admin should exist

### Parent App Specific
- Parents don't need explicit permissions for many actions
- Access is controlled by relationship (guardian_id)
- Published content filter applies automatically

### Audit Trail
- All sensitive actions are logged
- Permissions: `activity_logs.view`, `activity_logs.delete`
- Only Super Admin and Admin can view audit logs

---

## Permission Seeder Structure

```sql
INSERT INTO permissions (name, module) VALUES
    ('students.view_any', 'students'),
    ('students.view', 'students'),
    ('students.create', 'students'),
    ...
```

---

## Role Assignment Rules

1. **One Primary Role**: Each user has one primary role
2. **Multiple Permissions**: Roles can have multiple permissions
3. **Permission Inheritance**: Not implemented (flat structure)
4. **Dynamic Roles**: Admin can create custom roles (Super Admin only)

---

**Total Permissions**: 150+
**Default Roles**: 7
**Permission Categories**: 15 modules

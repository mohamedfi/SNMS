# API Specifications - SNMS

## Base URL
```
Development: http://localhost:8000/api/v1
Production: https://api.stepsnursery.com/api/v1
```

## Authentication
All endpoints (except login/register) require authentication via Laravel Sanctum token.

**Headers Required**:
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Standard Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": {},
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "last_page": 7
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required"],
    "password": ["The password must be at least 8 characters"]
  }
}
```

## HTTP Status Codes
- `200` - OK (Success)
- `201` - Created (Resource created)
- `204` - No Content (Success with no response body)
- `400` - Bad Request (Invalid input)
- `401` - Unauthorized (Authentication failed)
- `403` - Forbidden (Authorization failed)
- `404` - Not Found (Resource not found)
- `422` - Unprocessable Entity (Validation failed)
- `429` - Too Many Requests (Rate limit exceeded)
- `500` - Internal Server Error

---

## 1. Authentication & Authorization

### 1.1 Login
**POST** `/auth/login`

**Request Body**:
```json
{
  "email": "admin@stepsnursery.com",
  "password": "password123",
  "remember": true
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Admin",
      "email": "admin@stepsnursery.com",
      "avatar": "https://storage.example.com/avatars/1.jpg",
      "roles": ["admin"],
      "permissions": ["students.view", "students.create"]
    },
    "token": "1|abc123def456...",
    "token_type": "Bearer"
  }
}
```

**Errors**:
- `422` - Invalid credentials
- `403` - Account inactive

---

### 1.2 Logout
**POST** `/auth/logout`

**Response** (200):
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

### 1.3 Get Current User
**GET** `/auth/user`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Admin",
    "email": "admin@stepsnursery.com",
    "phone": "+1234567890",
    "avatar": "https://storage.example.com/avatars/1.jpg",
    "roles": ["admin"],
    "permissions": ["students.view", "students.create"],
    "last_login_at": "2024-01-15T10:30:00Z"
  }
}
```

---

### 1.4 Refresh Token
**POST** `/auth/refresh`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "token": "2|new_token_here...",
    "token_type": "Bearer"
  }
}
```

---

### 1.5 Forgot Password
**POST** `/auth/forgot-password`

**Request Body**:
```json
{
  "email": "user@example.com"
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Password reset link sent to your email"
}
```

---

### 1.6 Reset Password
**POST** `/auth/reset-password`

**Request Body**:
```json
{
  "email": "user@example.com",
  "token": "reset_token_here",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Password reset successfully"
}
```

---

## 2. Student Management

### 2.1 List Students
**GET** `/students`

**Query Parameters**:
- `page` (int) - Page number (default: 1)
- `per_page` (int) - Items per page (default: 15, max: 100)
- `search` (string) - Search by name or student code
- `class_id` (int) - Filter by class
- `status` (string) - Filter by status (active, graduated, withdrawn, waiting)
- `sort_by` (string) - Sort field (default: created_at)
- `sort_order` (string) - Sort order (asc, desc)

**Example Request**:
```
GET /students?page=1&per_page=15&class_id=3&status=active&search=John
```

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "student_code": "STU2024001",
      "first_name": "John",
      "last_name": "Doe",
      "arabic_name": "جون دو",
      "full_name": "John Doe",
      "date_of_birth": "2021-05-15",
      "age": "2 years 8 months",
      "gender": "male",
      "photo": "https://storage.example.com/students/1.jpg",
      "class": {
        "id": 3,
        "name": "Toddlers A",
        "teacher": "Ms. Sarah"
      },
      "guardian": {
        "id": 1,
        "father_name": "Michael Doe",
        "father_phone": "+1234567890",
        "mother_name": "Jane Doe",
        "mother_phone": "+0987654321"
      },
      "status": "active",
      "enrollment_date": "2024-01-10",
      "medical_notes": "Allergic to peanuts",
      "has_special_needs": false
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 45,
    "last_page": 3
  }
}
```

---

### 2.2 Get Single Student
**GET** `/students/{id}`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "student_code": "STU2024001",
    "first_name": "John",
    "last_name": "Doe",
    "arabic_name": "جون دو",
    "date_of_birth": "2021-05-15",
    "gender": "male",
    "nationality": "Saudi",
    "national_id": "1234567890",
    "birth_certificate_no": "BC123456",
    "photo": "https://storage.example.com/students/1.jpg",
    "class": {
      "id": 3,
      "name": "Toddlers A",
      "teacher": {
        "id": 5,
        "name": "Ms. Sarah",
        "phone": "+1234567890"
      }
    },
    "guardian": {
      "id": 1,
      "father_name": "Michael Doe",
      "father_phone": "+1234567890",
      "father_email": "michael@example.com",
      "mother_name": "Jane Doe",
      "mother_phone": "+0987654321",
      "mother_email": "jane@example.com",
      "address": "123 Main St, City",
      "emergency_contact_name": "Uncle Bob",
      "emergency_contact_phone": "+1111111111"
    },
    "medical_notes": "Allergic to peanuts",
    "special_needs": null,
    "blood_type": "A+",
    "has_special_diet": true,
    "diet_notes": "No dairy products",
    "pickup_authorized_persons": [
      {
        "name": "Grandma Mary",
        "phone": "+2222222222",
        "relation": "Grandmother"
      }
    ],
    "documents": [
      {
        "id": 1,
        "type": "birth_certificate",
        "file_name": "birth_cert.pdf",
        "file_url": "https://storage.example.com/docs/birth_cert.pdf",
        "uploaded_at": "2024-01-10T09:00:00Z"
      }
    ],
    "status": "active",
    "enrollment_date": "2024-01-10",
    "notes": "Very active child",
    "created_at": "2024-01-10T09:00:00Z",
    "updated_at": "2024-01-15T14:30:00Z"
  }
}
```

---

### 2.3 Create Student
**POST** `/students`

**Request Body**:
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "arabic_name": "جون دو",
  "date_of_birth": "2021-05-15",
  "gender": "male",
  "nationality": "Saudi",
  "national_id": "1234567890",
  "birth_certificate_no": "BC123456",
  "class_id": 3,
  "guardian_id": 1,
  "enrollment_date": "2024-01-10",
  "status": "active",
  "medical_notes": "Allergic to peanuts",
  "special_needs": null,
  "blood_type": "A+",
  "has_special_diet": true,
  "diet_notes": "No dairy products",
  "pickup_authorized_persons": [
    {
      "name": "Grandma Mary",
      "phone": "+2222222222",
      "relation": "Grandmother"
    }
  ],
  "notes": "Very active child"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Student created successfully",
  "data": {
    "id": 1,
    "student_code": "STU2024001",
    "first_name": "John",
    "last_name": "Doe",
    ...
  }
}
```

**Validation Rules**:
- `first_name`: required, string, max:100
- `last_name`: required, string, max:100
- `date_of_birth`: required, date, before:today
- `gender`: required, in:male,female
- `class_id`: nullable, exists:classes,id
- `guardian_id`: required, exists:guardians,id
- `enrollment_date`: required, date

---

### 2.4 Update Student
**PUT/PATCH** `/students/{id}`

**Request Body**: (Same as Create, all fields optional)

**Response** (200):
```json
{
  "success": true,
  "message": "Student updated successfully",
  "data": { ... }
}
```

---

### 2.5 Delete Student (Soft Delete)
**DELETE** `/students/{id}`

**Response** (200):
```json
{
  "success": true,
  "message": "Student deleted successfully"
}
```

---

### 2.6 Upload Student Photo
**POST** `/students/{id}/photo`

**Request Body** (multipart/form-data):
```
photo: [file]
```

**Response** (200):
```json
{
  "success": true,
  "message": "Photo uploaded successfully",
  "data": {
    "photo_url": "https://storage.example.com/students/1.jpg"
  }
}
```

**Validation**:
- Max size: 5MB
- Allowed types: jpg, jpeg, png

---

### 2.7 Upload Student Document
**POST** `/students/{id}/documents`

**Request Body** (multipart/form-data):
```
document_type: birth_certificate
file: [file]
```

**Response** (201):
```json
{
  "success": true,
  "message": "Document uploaded successfully",
  "data": {
    "id": 1,
    "document_type": "birth_certificate",
    "file_name": "birth_cert.pdf",
    "file_url": "https://storage.example.com/docs/1/birth_cert.pdf"
  }
}
```

---

## 3. Guardian Management

### 3.1 List Guardians
**GET** `/guardians`

**Query Parameters**: Same pagination/search as students

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "father_name": "Michael Doe",
      "father_phone": "+1234567890",
      "father_email": "michael@example.com",
      "mother_name": "Jane Doe",
      "mother_phone": "+0987654321",
      "mother_email": "jane@example.com",
      "address": "123 Main St, City",
      "city": "Riyadh",
      "children_count": 2,
      "children": [
        {
          "id": 1,
          "name": "John Doe",
          "student_code": "STU2024001"
        },
        {
          "id": 2,
          "name": "Jane Doe Jr",
          "student_code": "STU2024002"
        }
      ]
    }
  ]
}
```

---

### 3.2 Create Guardian
**POST** `/guardians`

**Request Body**:
```json
{
  "father_name": "Michael Doe",
  "father_phone": "+1234567890",
  "father_email": "michael@example.com",
  "father_occupation": "Engineer",
  "father_national_id": "1234567890",
  "mother_name": "Jane Doe",
  "mother_phone": "+0987654321",
  "mother_email": "jane@example.com",
  "mother_occupation": "Teacher",
  "mother_national_id": "0987654321",
  "address": "123 Main St, City",
  "city": "Riyadh",
  "district": "Al Olaya",
  "emergency_contact_name": "Uncle Bob",
  "emergency_contact_phone": "+1111111111",
  "emergency_contact_relation": "Uncle"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Guardian created successfully",
  "data": { ... }
}
```

---

## 4. Class Management

### 4.1 List Classes
**GET** `/classes`

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Toddlers A",
      "age_group": "2-3 years",
      "capacity": 15,
      "current_students": 12,
      "available_seats": 3,
      "teacher": {
        "id": 5,
        "name": "Ms. Sarah",
        "phone": "+1234567890"
      },
      "assistant_teacher": {
        "id": 6,
        "name": "Ms. Emily"
      },
      "room_number": "101",
      "academic_year": "2024-2025",
      "is_active": true
    }
  ]
}
```

---

### 4.2 Create Class
**POST** `/classes`

**Request Body**:
```json
{
  "name": "Toddlers A",
  "age_group": "2-3 years",
  "capacity": 15,
  "teacher_id": 5,
  "assistant_teacher_id": 6,
  "room_number": "101",
  "academic_year": "2024-2025",
  "is_active": true
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Class created successfully",
  "data": { ... }
}
```

---

### 4.3 Get Class Students
**GET** `/classes/{id}/students`

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "student_code": "STU2024001",
      "name": "John Doe",
      "photo": "...",
      "status": "active"
    }
  ]
}
```

---

## 5. Attendance Management

### 5.1 Mark Student Attendance
**POST** `/attendance/students`

**Request Body**:
```json
{
  "date": "2024-01-15",
  "class_id": 3,
  "attendance": [
    {
      "student_id": 1,
      "status": "present",
      "check_in_time": "08:00:00",
      "temperature": 36.5,
      "notes": null
    },
    {
      "student_id": 2,
      "status": "absent",
      "notes": "Sick"
    },
    {
      "student_id": 3,
      "status": "late",
      "check_in_time": "09:30:00"
    }
  ]
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Attendance marked successfully",
  "data": {
    "total_students": 15,
    "present": 12,
    "absent": 2,
    "late": 1,
    "notifications_sent": 3
  }
}
```

---

### 5.2 Get Student Attendance
**GET** `/attendance/students/{student_id}`

**Query Parameters**:
- `start_date` (date) - Start date
- `end_date` (date) - End date
- `month` (string) - Month (YYYY-MM)

**Example**:
```
GET /attendance/students/1?month=2024-01
```

**Response** (200):
```json
{
  "success": true,
  "data": {
    "student": {
      "id": 1,
      "name": "John Doe",
      "student_code": "STU2024001"
    },
    "period": "January 2024",
    "summary": {
      "total_days": 20,
      "present": 18,
      "absent": 1,
      "late": 1,
      "attendance_rate": "95%"
    },
    "records": [
      {
        "date": "2024-01-15",
        "status": "present",
        "check_in_time": "08:00:00",
        "check_out_time": "14:00:00",
        "temperature": 36.5
      },
      {
        "date": "2024-01-16",
        "status": "absent",
        "notes": "Sick"
      }
    ]
  }
}
```

---

### 5.3 Get Class Attendance (Daily)
**GET** `/attendance/classes/{class_id}`

**Query Parameters**:
- `date` (date) - Specific date (default: today)

**Response** (200):
```json
{
  "success": true,
  "data": {
    "class": {
      "id": 3,
      "name": "Toddlers A"
    },
    "date": "2024-01-15",
    "summary": {
      "total_students": 15,
      "present": 12,
      "absent": 2,
      "late": 1,
      "not_marked": 0
    },
    "students": [
      {
        "student_id": 1,
        "name": "John Doe",
        "status": "present",
        "check_in_time": "08:00:00"
      }
    ]
  }
}
```

---

### 5.4 Mark Teacher Attendance
**POST** `/attendance/teachers`

**Request Body**:
```json
{
  "user_id": 5,
  "date": "2024-01-15",
  "status": "present",
  "check_in_time": "07:30:00",
  "check_out_time": "15:30:00",
  "notes": null
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Teacher attendance marked successfully"
}
```

---

### 5.5 Get Teacher Attendance Report
**GET** `/attendance/teachers/{user_id}`

**Query Parameters**: Same as student attendance

**Response**: Similar structure to student attendance

---

## 6. Evaluations & Progress Reports

### 6.1 List Evaluation Templates
**GET** `/evaluations/templates`

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Monthly Assessment - Toddlers",
      "type": "monthly",
      "age_group": "2-3 years",
      "is_active": true,
      "criteria_count": 15,
      "categories": ["Cognitive", "Social", "Motor Skills", "Language"]
    }
  ]
}
```

---

### 6.2 Get Template Details with Criteria
**GET** `/evaluations/templates/{id}`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Monthly Assessment - Toddlers",
    "type": "monthly",
    "age_group": "2-3 years",
    "criteria": [
      {
        "id": 1,
        "category": "Cognitive",
        "skill_name": "Recognizes colors",
        "description": "Can identify and name basic colors",
        "max_score": 5,
        "display_order": 1
      },
      {
        "id": 2,
        "category": "Social",
        "skill_name": "Plays with others",
        "description": "Engages in group activities",
        "max_score": 5,
        "display_order": 2
      }
    ]
  }
}
```

---

### 6.3 Create Student Evaluation
**POST** `/evaluations`

**Request Body**:
```json
{
  "student_id": 1,
  "template_id": 1,
  "evaluation_date": "2024-01-15",
  "period": "January 2024",
  "overall_comment": "Great progress this month!",
  "scores": [
    {
      "criteria_id": 1,
      "score": 4,
      "notes": "Getting better at colors"
    },
    {
      "criteria_id": 2,
      "score": 5,
      "notes": "Very social and friendly"
    }
  ],
  "is_published": false
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Evaluation created successfully",
  "data": {
    "id": 1,
    "student": {
      "id": 1,
      "name": "John Doe"
    },
    "template": "Monthly Assessment - Toddlers",
    "evaluation_date": "2024-01-15",
    "period": "January 2024",
    "overall_score": "90%",
    "is_published": false
  }
}
```

---

### 6.4 Get Student Evaluations
**GET** `/students/{id}/evaluations`

**Query Parameters**:
- `academic_year` (string)
- `type` (string) - monthly, weekly, term, annual

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "template": "Monthly Assessment - Toddlers",
      "evaluation_date": "2024-01-15",
      "period": "January 2024",
      "evaluated_by": "Ms. Sarah",
      "overall_score": "90%",
      "is_published": true,
      "published_at": "2024-01-20T10:00:00Z"
    }
  ]
}
```

---

### 6.5 Get Evaluation Details
**GET** `/evaluations/{id}`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "student": {
      "id": 1,
      "name": "John Doe",
      "photo": "..."
    },
    "template": "Monthly Assessment - Toddlers",
    "evaluation_date": "2024-01-15",
    "period": "January 2024",
    "evaluated_by": {
      "id": 5,
      "name": "Ms. Sarah"
    },
    "overall_comment": "Great progress this month!",
    "scores": [
      {
        "category": "Cognitive",
        "skill_name": "Recognizes colors",
        "score": 4,
        "max_score": 5,
        "percentage": 80,
        "notes": "Getting better at colors"
      }
    ],
    "category_averages": {
      "Cognitive": 85,
      "Social": 95,
      "Motor Skills": 90
    },
    "overall_score": 90,
    "media": [
      {
        "id": 1,
        "type": "photo",
        "url": "https://storage.example.com/eval/1.jpg",
        "caption": "Playing with colors"
      }
    ],
    "is_published": true,
    "published_at": "2024-01-20T10:00:00Z"
  }
}
```

---

### 6.6 Upload Evaluation Media
**POST** `/evaluations/{id}/media`

**Request Body** (multipart/form-data):
```
media_type: photo
file: [file]
caption: "Playing with colors"
```

**Response** (201):
```json
{
  "success": true,
  "message": "Media uploaded successfully",
  "data": {
    "id": 1,
    "type": "photo",
    "url": "https://storage.example.com/eval/1.jpg",
    "caption": "Playing with colors"
  }
}
```

---

### 6.7 Publish Evaluation
**POST** `/evaluations/{id}/publish`

**Response** (200):
```json
{
  "success": true,
  "message": "Evaluation published and parents notified",
  "data": {
    "is_published": true,
    "published_at": "2024-01-20T10:00:00Z"
  }
}
```

---

## 7. Events & Journeys

### 7.1 List Events
**GET** `/events`

**Query Parameters**:
- `type` - trip, celebration, parent_meeting, workshop, other
- `status` - draft, published, cancelled, completed
- `start_date` - Filter from date
- `end_date` - Filter to date

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Zoo Trip",
      "description": "Educational visit to the city zoo",
      "type": "trip",
      "event_date": "2024-02-15",
      "start_time": "09:00:00",
      "end_time": "14:00:00",
      "location": "City Zoo",
      "target_classes": ["Toddlers A", "Toddlers B"],
      "requires_permission": true,
      "permission_deadline": "2024-02-10",
      "has_fee": true,
      "fee_amount": 50.00,
      "max_participants": 30,
      "current_participants": 25,
      "status": "published",
      "created_by": "Admin User"
    }
  ]
}
```

---

### 7.2 Create Event
**POST** `/events`

**Request Body**:
```json
{
  "title": "Zoo Trip",
  "description": "Educational visit to the city zoo",
  "type": "trip",
  "event_date": "2024-02-15",
  "start_time": "09:00:00",
  "end_time": "14:00:00",
  "location": "City Zoo",
  "target_classes": [1, 2],
  "requires_permission": true,
  "permission_deadline": "2024-02-10",
  "has_fee": true,
  "fee_amount": 50.00,
  "max_participants": 30,
  "status": "published"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Event created successfully",
  "data": { ... }
}
```

---

### 7.3 Get Event Details
**GET** `/events/{id}`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Zoo Trip",
    "description": "Educational visit to the city zoo",
    "event_date": "2024-02-15",
    "participants_summary": {
      "total_registered": 25,
      "permission_approved": 20,
      "permission_pending": 3,
      "permission_declined": 2,
      "payment_completed": 18,
      "payment_pending": 7
    },
    "participants": [
      {
        "student_id": 1,
        "student_name": "John Doe",
        "student_code": "STU2024001",
        "permission_status": "approved",
        "permission_given_at": "2024-02-05T10:00:00Z",
        "payment_status": "paid",
        "attendance_status": "not_marked"
      }
    ]
  }
}
```

---

### 7.4 Register Student for Event
**POST** `/events/{id}/register`

**Request Body**:
```json
{
  "student_id": 1
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Student registered successfully",
  "data": {
    "requires_permission": true,
    "requires_payment": true,
    "fee_amount": 50.00,
    "permission_deadline": "2024-02-10"
  }
}
```

---

### 7.5 Update Permission Status (Parent)
**PUT** `/events/{event_id}/participants/{student_id}/permission`

**Request Body**:
```json
{
  "permission_status": "approved"
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Permission updated successfully"
}
```

---

### 7.6 Mark Event Attendance
**POST** `/events/{id}/attendance`

**Request Body**:
```json
{
  "attendance": [
    {
      "student_id": 1,
      "status": "present"
    },
    {
      "student_id": 2,
      "status": "absent"
    }
  ]
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Attendance marked successfully"
}
```

---

## 8. HR & Payroll

### 8.1 List Employees
**GET** `/employees`

**Query Parameters**:
- `search` - Search by name or employee code
- `department` - Filter by department
- `status` - active, on_leave, suspended, terminated
- `contract_type` - full_time, part_time, contract, intern

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "employee_code": "EMP001",
      "name": "Sarah Johnson",
      "email": "sarah@stepsnursery.com",
      "phone": "+1234567890",
      "department": "Teaching",
      "position": "Lead Teacher",
      "contract_type": "full_time",
      "contract_start_date": "2023-01-15",
      "base_salary": 5000.00,
      "status": "active"
    }
  ]
}
```

---

### 8.2 Create Employee
**POST** `/employees`

**Request Body**:
```json
{
  "user_id": 5,
  "national_id": "1234567890",
  "date_of_birth": "1990-05-15",
  "gender": "female",
  "marital_status": "single",
  "address": "123 Main St",
  "city": "Riyadh",
  "emergency_contact_name": "John Doe",
  "emergency_contact_phone": "+0987654321",
  "department": "Teaching",
  "position": "Lead Teacher",
  "specialization": "Early Childhood Education",
  "contract_type": "full_time",
  "contract_start_date": "2023-01-15",
  "base_salary": 5000.00,
  "allowances": {
    "housing": 1000.00,
    "transportation": 500.00
  },
  "bank_name": "ABC Bank",
  "bank_account_number": "1234567890",
  "bank_iban": "SA1234567890"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Employee created successfully",
  "data": { ... }
}
```

---

### 8.3 Submit Leave Request
**POST** `/leave-requests`

**Request Body**:
```json
{
  "employee_id": 1,
  "leave_type": "annual",
  "start_date": "2024-02-15",
  "end_date": "2024-02-20",
  "reason": "Family vacation"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Leave request submitted successfully",
  "data": {
    "id": 1,
    "total_days": 6,
    "status": "pending"
  }
}
```

---

### 8.4 Approve/Reject Leave Request
**PUT** `/leave-requests/{id}`

**Request Body**:
```json
{
  "status": "approved",
  "approval_notes": "Approved for requested dates"
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Leave request approved successfully"
}
```

---

### 8.5 Generate Payroll
**POST** `/payroll/generate`

**Request Body**:
```json
{
  "month": "2024-01",
  "employee_ids": [1, 2, 3]
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Payroll generated successfully",
  "data": {
    "month": "2024-01",
    "total_employees": 3,
    "total_gross": 15000.00,
    "total_deductions": 1500.00,
    "total_net": 13500.00,
    "records": [
      {
        "employee_id": 1,
        "employee_name": "Sarah Johnson",
        "base_salary": 5000.00,
        "allowances": 1500.00,
        "overtime": 200.00,
        "gross_salary": 6700.00,
        "deductions": 500.00,
        "net_salary": 6200.00
      }
    ]
  }
}
```

---

### 8.6 Get Employee Payroll History
**GET** `/employees/{id}/payroll`

**Query Parameters**:
- `year` - Filter by year

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "month": "2024-01",
      "base_salary": 5000.00,
      "gross_salary": 6700.00,
      "net_salary": 6200.00,
      "payment_date": "2024-01-25",
      "status": "paid",
      "payslip_url": "https://storage.example.com/payslips/1.pdf"
    }
  ]
}
```

---

### 8.7 Download Payslip
**GET** `/payroll/{id}/payslip`

**Response**: PDF file download

---

## 9. Accounting & Finance

### 9.1 List Invoices
**GET** `/invoices`

**Query Parameters**:
- `student_id` - Filter by student
- `status` - draft, sent, paid, partial, overdue, cancelled
- `type` - tuition, registration, event, transportation, meal, other
- `start_date` - Filter from date
- `end_date` - Filter to date

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "invoice_number": "INV-2024-001",
      "student": {
        "id": 1,
        "name": "John Doe",
        "student_code": "STU2024001"
      },
      "guardian": {
        "id": 1,
        "father_name": "Michael Doe",
        "father_phone": "+1234567890"
      },
      "invoice_type": "tuition",
      "description": "January 2024 Tuition",
      "amount": 1000.00,
      "discount": 100.00,
      "tax": 0.00,
      "total_amount": 900.00,
      "paid_amount": 500.00,
      "balance": 400.00,
      "status": "partial",
      "due_date": "2024-01-31",
      "issued_date": "2024-01-01"
    }
  ],
  "meta": {
    "summary": {
      "total_invoices": 50,
      "total_amount": 50000.00,
      "total_paid": 45000.00,
      "total_outstanding": 5000.00
    }
  }
}
```

---

### 9.2 Create Invoice
**POST** `/invoices`

**Request Body**:
```json
{
  "student_id": 1,
  "invoice_type": "tuition",
  "description": "January 2024 Tuition",
  "amount": 1000.00,
  "discount": 100.00,
  "tax": 0.00,
  "due_date": "2024-01-31",
  "issued_date": "2024-01-01",
  "status": "sent",
  "notes": "Monthly tuition fee"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Invoice created successfully",
  "data": {
    "id": 1,
    "invoice_number": "INV-2024-001",
    "total_amount": 900.00,
    "status": "sent"
  }
}
```

---

### 9.3 Record Payment
**POST** `/payments`

**Request Body**:
```json
{
  "invoice_id": 1,
  "amount": 500.00,
  "payment_method": "bank_transfer",
  "payment_date": "2024-01-15",
  "transaction_reference": "TXN123456",
  "notes": "Partial payment"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Payment recorded successfully",
  "data": {
    "id": 1,
    "payment_number": "PAY-2024-001",
    "amount": 500.00,
    "invoice": {
      "invoice_number": "INV-2024-001",
      "new_balance": 400.00,
      "status": "partial"
    }
  }
}
```

---

### 9.4 Get Payment History
**GET** `/invoices/{id}/payments`

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "payment_number": "PAY-2024-001",
      "amount": 500.00,
      "payment_method": "bank_transfer",
      "payment_date": "2024-01-15",
      "status": "completed",
      "received_by": "Admin User"
    }
  ]
}
```

---

### 9.5 List Expenses
**GET** `/expenses`

**Query Parameters**:
- `category` - Filter by category
- `start_date`, `end_date` - Date range
- `status` - pending, approved, paid, rejected

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "expense_number": "EXP-2024-001",
      "category": "Utilities",
      "subcategory": "Electricity",
      "description": "Monthly electricity bill",
      "amount": 500.00,
      "expense_date": "2024-01-15",
      "payment_method": "bank_transfer",
      "vendor_name": "Electric Company",
      "status": "paid",
      "receipt_url": "https://storage.example.com/receipts/1.pdf"
    }
  ]
}
```

---

### 9.6 Create Expense
**POST** `/expenses`

**Request Body**:
```json
{
  "category": "Utilities",
  "subcategory": "Electricity",
  "description": "Monthly electricity bill",
  "amount": 500.00,
  "expense_date": "2024-01-15",
  "payment_method": "bank_transfer",
  "vendor_name": "Electric Company",
  "receipt_number": "REC123",
  "status": "pending",
  "notes": "January 2024 bill"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Expense created successfully",
  "data": { ... }
}
```

---

### 9.7 Financial Reports
**GET** `/reports/financial`

**Query Parameters**:
- `type` - income, expense, cashflow, profit_loss
- `start_date`, `end_date` - Date range
- `group_by` - day, week, month, year

**Response** (200):
```json
{
  "success": true,
  "data": {
    "report_type": "profit_loss",
    "period": "January 2024",
    "summary": {
      "total_income": 50000.00,
      "total_expenses": 30000.00,
      "net_profit": 20000.00,
      "profit_margin": "40%"
    },
    "income_breakdown": {
      "tuition": 45000.00,
      "registration": 3000.00,
      "events": 2000.00
    },
    "expense_breakdown": {
      "salaries": 20000.00,
      "utilities": 5000.00,
      "supplies": 3000.00,
      "other": 2000.00
    }
  }
}
```

---

## 10. Inventory & Assets

### 10.1 List Stock Items
**GET** `/inventory/stock`

**Query Parameters**:
- `category` - Filter by category
- `status` - available, low_stock, out_of_stock, discontinued

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "item_code": "ITEM001",
      "name": "School Uniform - Size S",
      "category": "Uniform",
      "unit": "piece",
      "quantity": 50,
      "min_quantity": 20,
      "unit_price": 25.00,
      "status": "available",
      "supplier_name": "Uniform Supplier Inc"
    }
  ]
}
```

---

### 10.2 Record Stock Movement
**POST** `/inventory/movements`

**Request Body**:
```json
{
  "stock_item_id": 1,
  "movement_type": "purchase",
  "quantity": 50,
  "unit_cost": 20.00,
  "reference_number": "PO-2024-001",
  "movement_date": "2024-01-15",
  "reason": "Restock for new semester"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Stock movement recorded successfully",
  "data": {
    "previous_quantity": 30,
    "new_quantity": 80,
    "total_cost": 1000.00
  }
}
```

---

### 10.3 List Assets
**GET** `/assets`

**Query Parameters**:
- `category` - Filter by category
- `status` - active, inactive, maintenance, disposed
- `condition` - excellent, good, fair, poor, damaged

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "asset_code": "ASSET001",
      "name": "Classroom Projector",
      "category": "Electronics",
      "brand": "Epson",
      "purchase_date": "2023-06-15",
      "purchase_price": 2000.00,
      "current_value": 1500.00,
      "location": "Room 101",
      "condition": "good",
      "status": "active",
      "warranty_expiry": "2025-06-15"
    }
  ]
}
```

---

## 11. Communication

### 11.1 List Notifications
**GET** `/notifications`

**Query Parameters**:
- `is_read` - Filter by read status (true/false)
- `type` - Filter by type

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "type": "attendance_alert",
      "title": "Student Absent",
      "message": "John Doe was marked absent today",
      "action_url": "/students/1/attendance",
      "is_read": false,
      "created_at": "2024-01-15T09:00:00Z"
    }
  ],
  "meta": {
    "unread_count": 5
  }
}
```

---

### 11.2 Mark Notification as Read
**PUT** `/notifications/{id}/read`

**Response** (200):
```json
{
  "success": true,
  "message": "Notification marked as read"
}
```

---

### 11.3 List Announcements
**GET** `/announcements`

**Query Parameters**:
- `type` - general, urgent, event, holiday, maintenance

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "School Holiday Notice",
      "content": "The school will be closed on...",
      "type": "holiday",
      "is_published": true,
      "published_at": "2024-01-10T10:00:00Z",
      "expires_at": "2024-02-01T00:00:00Z",
      "created_by": "Admin User"
    }
  ]
}
```

---

### 11.4 Create Announcement
**POST** `/announcements`

**Request Body**:
```json
{
  "title": "School Holiday Notice",
  "content": "The school will be closed on...",
  "type": "holiday",
  "target_audience": {
    "roles": ["parent", "teacher"],
    "classes": [1, 2, 3]
  },
  "is_published": true,
  "expires_at": "2024-02-01T00:00:00Z"
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Announcement created and published",
  "data": { ... }
}
```

---

### 11.5 Send Message
**POST** `/messages`

**Request Body**:
```json
{
  "recipient_id": 10,
  "subject": "Meeting Request",
  "body": "I would like to schedule a meeting..."
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Message sent successfully",
  "data": {
    "id": 1,
    "created_at": "2024-01-15T10:00:00Z"
  }
}
```

---

## 12. Online Admissions

### 12.1 Submit Admission Application
**POST** `/admissions/apply`

**Request Body** (multipart/form-data):
```json
{
  "child_first_name": "Emma",
  "child_last_name": "Smith",
  "child_date_of_birth": "2021-03-15",
  "child_gender": "female",
  "preferred_class_id": 2,
  "guardian_name": "Robert Smith",
  "guardian_phone": "+1234567890",
  "guardian_email": "robert@example.com",
  "guardian_address": "123 Main St",
  "documents": {
    "birth_certificate": [file],
    "medical_records": [file],
    "parent_id": [file]
  }
}
```

**Response** (201):
```json
{
  "success": true,
  "message": "Application submitted successfully",
  "data": {
    "id": 1,
    "application_number": "APP-2024-001",
    "status": "new",
    "submitted_at": "2024-01-15T10:00:00Z"
  }
}
```

---

### 12.2 List Applications
**GET** `/admissions/applications`

**Query Parameters**:
- `status` - new, in_review, interview_scheduled, accepted, rejected, waitlisted

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "application_number": "APP-2024-001",
      "child_name": "Emma Smith",
      "child_date_of_birth": "2021-03-15",
      "guardian_name": "Robert Smith",
      "guardian_phone": "+1234567890",
      "preferred_class": "Toddlers B",
      "status": "new",
      "submitted_at": "2024-01-15T10:00:00Z"
    }
  ]
}
```

---

### 12.3 Update Application Status
**PUT** `/admissions/applications/{id}`

**Request Body**:
```json
{
  "status": "interview_scheduled",
  "interview_date": "2024-01-20T10:00:00Z",
  "interviewed_by": 3,
  "notes": "Scheduled for interview"
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Application status updated successfully"
}
```

---

## 13. Parent App Endpoints

### 13.1 Get My Children
**GET** `/parent/children`

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "student_code": "STU2024001",
      "photo": "https://storage.example.com/students/1.jpg",
      "class": "Toddlers A",
      "status": "active"
    }
  ]
}
```

---

### 13.2 Get Child Dashboard
**GET** `/parent/children/{id}/dashboard`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "student": {
      "id": 1,
      "name": "John Doe",
      "photo": "...",
      "class": "Toddlers A"
    },
    "today": {
      "attendance": {
        "status": "present",
        "check_in_time": "08:00:00",
        "temperature": 36.5
      },
      "activities": [
        {
          "time": "09:00",
          "activity": "Circle Time",
          "description": "Morning songs and stories"
        }
      ],
      "meals": [
        {
          "time": "10:00",
          "meal": "Snack",
          "items": ["Apple", "Crackers"],
          "consumed": "All"
        }
      ],
      "nap_time": {
        "start": "12:30",
        "end": "14:00",
        "duration": "1.5 hours"
      }
    },
    "this_month": {
      "attendance_rate": "95%",
      "present_days": 18,
      "absent_days": 1
    },
    "recent_media": [
      {
        "id": 1,
        "type": "photo",
        "url": "...",
        "caption": "Playing with blocks",
        "created_at": "2024-01-15T10:00:00Z"
      }
    ],
    "upcoming_events": [
      {
        "id": 1,
        "title": "Zoo Trip",
        "date": "2024-02-15",
        "requires_permission": true,
        "has_fee": true
      }
    ],
    "pending_invoices": [
      {
        "id": 1,
        "invoice_number": "INV-2024-001",
        "amount": 900.00,
        "due_date": "2024-01-31"
      }
    ]
  }
}
```

---

### 13.3 Get Child Attendance History
**GET** `/parent/children/{id}/attendance`

**Query Parameters**: Same as admin attendance endpoint

---

### 13.4 Get Child Evaluations
**GET** `/parent/children/{id}/evaluations`

**Response**: Only published evaluations

---

### 13.5 Get Pending Invoices
**GET** `/parent/invoices`

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "invoice_number": "INV-2024-001",
      "student_name": "John Doe",
      "description": "January 2024 Tuition",
      "total_amount": 900.00,
      "paid_amount": 500.00,
      "balance": 400.00,
      "due_date": "2024-01-31",
      "status": "partial"
    }
  ]
}
```

---

### 13.6 Pay Invoice (Redirect to Payment Gateway)
**POST** `/parent/invoices/{id}/pay`

**Request Body**:
```json
{
  "amount": 400.00,
  "payment_method": "online",
  "payment_gateway": "paymob"
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Redirecting to payment gateway",
  "data": {
    "payment_url": "https://payment.paymob.com/checkout/...",
    "transaction_id": "TXN123456"
  }
}
```

---

### 13.7 Get Messages
**GET** `/parent/messages`

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "from": "Ms. Sarah (Teacher)",
      "subject": "Great progress!",
      "preview": "John is doing great with...",
      "is_read": false,
      "created_at": "2024-01-15T10:00:00Z"
    }
  ]
}
```

---

## 14. Reports & Analytics

### 14.1 Dashboard Statistics
**GET** `/dashboard/stats`

**Response** (200):
```json
{
  "success": true,
  "data": {
    "students": {
      "total": 150,
      "active": 145,
      "on_waiting_list": 5
    },
    "attendance_today": {
      "total_students": 145,
      "present": 140,
      "absent": 3,
      "late": 2
    },
    "staff": {
      "total": 25,
      "present_today": 24,
      "on_leave": 1
    },
    "finance": {
      "revenue_this_month": 50000.00,
      "expenses_this_month": 30000.00,
      "outstanding_invoices": 15000.00,
      "overdue_invoices": 2000.00
    },
    "events": {
      "upcoming_count": 3,
      "this_week": 1
    }
  }
}
```

---

### 14.2 Attendance Report
**GET** `/reports/attendance`

**Query Parameters**:
- `start_date`, `end_date` - Date range
- `class_id` - Filter by class
- `export_format` - pdf, excel, csv

---

### 14.3 Financial Report
**GET** `/reports/financial`
(Covered in section 9.7)

---

## 15. Settings

### 15.1 Get System Settings
**GET** `/settings`

**Query Parameters**:
- `group` - Filter by group (general, email, payment, etc.)

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "key": "school_name",
      "value": "Steps Play School",
      "type": "string",
      "group": "general"
    },
    {
      "key": "academic_year",
      "value": "2024-2025",
      "type": "string",
      "group": "general"
    }
  ]
}
```

---

### 15.2 Update Setting
**PUT** `/settings/{key}`

**Request Body**:
```json
{
  "value": "2024-2025"
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Setting updated successfully"
}
```

---

## Rate Limiting

- **Unauthenticated requests**: 60 per minute
- **Authenticated requests**: 120 per minute
- **Login endpoint**: 5 per minute per IP

**Rate Limit Headers**:
```
X-RateLimit-Limit: 120
X-RateLimit-Remaining: 115
X-RateLimit-Reset: 1705320000
```

---

## Webhook Events (Optional)

For third-party integrations:

- `student.created`
- `attendance.marked`
- `invoice.created`
- `payment.completed`
- `evaluation.published`

---

## API Versioning

Current version: **v1**

Future versions will be accessible via:
- `/api/v2/...`

**Version Deprecation Policy**: 6 months notice before deprecation

---

**Total Endpoints**: 80+
**Authentication**: Laravel Sanctum (Token-based)
**Documentation**: OpenAPI 3.0 / Swagger UI

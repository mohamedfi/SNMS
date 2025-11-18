// User & Auth Types
export interface User {
  id: number;
  name: string;
  email: string;
  phone?: string;
  avatar?: string;
  is_active: boolean;
  last_login_at?: string;
  roles: string[];
  permissions: string[];
  created_at: string;
  updated_at: string;
}

export interface LoginCredentials {
  email: string;
  password: string;
  remember?: boolean;
}

export interface AuthResponse {
  user: User;
  token: string;
  token_type: string;
}

// Student Types
export interface Student {
  id: number;
  student_code: string;
  first_name: string;
  last_name: string;
  full_name: string;
  arabic_name?: string;
  date_of_birth: string;
  age: string;
  gender: 'male' | 'female';
  nationality?: string;
  national_id?: string;
  birth_certificate_no?: string;
  photo?: string;
  class?: {
    id: number;
    name: string;
    age_group?: string;
    teacher?: string;
  };
  guardian?: {
    id: number;
    father_name?: string;
    father_phone?: string;
    mother_name?: string;
    mother_phone?: string;
    primary_contact: string;
    primary_phone?: string;
  };
  enrollment_date: string;
  status: 'active' | 'graduated' | 'withdrawn' | 'waiting';
  medical_notes?: string;
  special_needs?: string;
  blood_type?: string;
  has_special_diet: boolean;
  diet_notes?: string;
  pickup_authorized_persons?: Array<{
    name: string;
    phone: string;
    relation: string;
  }>;
  notes?: string;
  documents?: StudentDocument[];
  created_at: string;
  updated_at: string;
}

export interface StudentDocument {
  id: number;
  type: string;
  file_name: string;
  file_url: string;
  uploaded_at: string;
}

export interface CreateStudentData {
  first_name: string;
  last_name: string;
  arabic_name?: string;
  date_of_birth: string;
  gender: 'male' | 'female';
  nationality?: string;
  national_id?: string;
  birth_certificate_no?: string;
  class_id?: number;
  guardian_id: number;
  enrollment_date: string;
  status?: 'active' | 'graduated' | 'withdrawn' | 'waiting';
  medical_notes?: string;
  special_needs?: string;
  blood_type?: string;
  has_special_diet?: boolean;
  diet_notes?: string;
  pickup_authorized_persons?: Array<{
    name: string;
    phone: string;
    relation: string;
  }>;
  notes?: string;
}

// Guardian Types
export interface Guardian {
  id: number;
  father_name?: string;
  father_phone?: string;
  father_email?: string;
  father_occupation?: string;
  mother_name?: string;
  mother_phone?: string;
  mother_email?: string;
  mother_occupation?: string;
  address?: string;
  city?: string;
  district?: string;
  emergency_contact_name?: string;
  emergency_contact_phone?: string;
  emergency_contact_relation?: string;
  children_count?: number;
  created_at: string;
  updated_at: string;
}

// Class Types
export interface SchoolClass {
  id: number;
  name: string;
  age_group?: string;
  capacity: number;
  current_enrollment?: number;
  available_seats?: number;
  teacher?: {
    id: number;
    name: string;
  };
  assistant_teacher?: {
    id: number;
    name: string;
  };
  room_number?: string;
  academic_year: string;
  is_active: boolean;
  created_at: string;
  updated_at: string;
}

// Attendance Types
export interface StudentAttendance {
  id: number;
  student: {
    id: number;
    name: string;
    student_code: string;
  };
  date: string;
  status: 'present' | 'absent' | 'late' | 'excused' | 'half_day';
  check_in_time?: string;
  check_out_time?: string;
  temperature?: number;
  notes?: string;
  marked_by: {
    id: number;
    name: string;
  };
  parent_notified: boolean;
  created_at: string;
}

export interface AttendanceSummary {
  total_days: number;
  present: number;
  absent: number;
  late: number;
  excused: number;
  attendance_rate: string;
}

// Invoice Types
export interface Invoice {
  id: number;
  invoice_number: string;
  student: {
    id: number;
    name: string;
    student_code: string;
  };
  guardian: {
    id: number;
    name: string;
    phone?: string;
  };
  invoice_type: 'tuition' | 'registration' | 'event' | 'transportation' | 'meal' | 'other';
  description?: string;
  amount: number;
  discount: number;
  tax: number;
  total_amount: number;
  paid_amount: number;
  balance: number;
  status: 'draft' | 'sent' | 'paid' | 'partial' | 'overdue' | 'cancelled';
  due_date: string;
  issued_date: string;
  notes?: string;
  created_at: string;
  updated_at: string;
}

// API Response Types
export interface ApiResponse<T = any> {
  success: boolean;
  message: string;
  data?: T;
  errors?: Record<string, string[]>;
}

export interface PaginatedResponse<T = any> {
  success: boolean;
  message: string;
  data: T[];
  meta: {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
    from: number;
    to: number;
  };
}

// Notification Types
export interface Notification {
  id: number;
  type: string;
  title: string;
  message: string;
  action_url?: string;
  is_read: boolean;
  read_at?: string;
  created_at: string;
}

// Dashboard Stats Types
export interface DashboardStats {
  students: {
    total: number;
    active: number;
    on_waiting_list: number;
  };
  attendance_today: {
    total_students: number;
    present: number;
    absent: number;
    late: number;
  };
  staff: {
    total: number;
    present_today: number;
    on_leave: number;
  };
  finance: {
    revenue_this_month: number;
    expenses_this_month: number;
    outstanding_invoices: number;
    overdue_invoices: number;
  };
  events: {
    upcoming_count: number;
    this_week: number;
  };
}

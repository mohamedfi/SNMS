# Folder Structure - SNMS

## Project Repository Structure

```
SNMS/
├── backend/                    # Laravel 12 API
├── frontend/
│   ├── admin-dashboard/        # React Admin App
│   └── parent-app/             # React Parent App
├── docs/                       # Documentation
├── docker/                     # Docker configuration
├── scripts/                    # Deployment scripts
└── README.md
```

---

## Backend (Laravel 12 API) Structure

```
backend/
├── app/
│   ├── Console/
│   │   ├── Commands/
│   │   │   ├── GenerateMonthlyInvoices.php
│   │   │   ├── GeneratePayroll.php
│   │   │   ├── SendAttendanceReminders.php
│   │   │   └── CleanupOldNotifications.php
│   │   └── Kernel.php
│   │
│   ├── Events/
│   │   ├── StudentAttendanceMarked.php
│   │   ├── EvaluationPublished.php
│   │   ├── InvoiceCreated.php
│   │   ├── PaymentReceived.php
│   │   ├── LeaveRequestSubmitted.php
│   │   └── EventCreated.php
│   │
│   ├── Exceptions/
│   │   ├── Handler.php
│   │   ├── InsufficientPermissionException.php
│   │   └── PaymentFailedException.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── V1/
│   │   │   │   │   ├── Auth/
│   │   │   │   │   │   ├── AuthController.php
│   │   │   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   │   │   └── ResetPasswordController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Students/
│   │   │   │   │   │   ├── StudentController.php
│   │   │   │   │   │   ├── StudentDocumentController.php
│   │   │   │   │   │   └── StudentPhotoController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Guardians/
│   │   │   │   │   │   └── GuardianController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Classes/
│   │   │   │   │   │   └── ClassController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Attendance/
│   │   │   │   │   │   ├── StudentAttendanceController.php
│   │   │   │   │   │   └── TeacherAttendanceController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Evaluations/
│   │   │   │   │   │   ├── EvaluationTemplateController.php
│   │   │   │   │   │   ├── EvaluationCriteriaController.php
│   │   │   │   │   │   ├── StudentEvaluationController.php
│   │   │   │   │   │   └── EvaluationMediaController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Events/
│   │   │   │   │   │   ├── EventController.php
│   │   │   │   │   │   └── EventParticipantController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Admissions/
│   │   │   │   │   │   ├── AdmissionApplicationController.php
│   │   │   │   │   │   └── AdmissionDocumentController.php
│   │   │   │   │   │
│   │   │   │   │   ├── HR/
│   │   │   │   │   │   ├── EmployeeController.php
│   │   │   │   │   │   ├── EmployeeDocumentController.php
│   │   │   │   │   │   ├── LeaveRequestController.php
│   │   │   │   │   │   └── PayrollController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Finance/
│   │   │   │   │   │   ├── InvoiceController.php
│   │   │   │   │   │   ├── PaymentController.php
│   │   │   │   │   │   ├── ExpenseController.php
│   │   │   │   │   │   └── FeeStructureController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Inventory/
│   │   │   │   │   │   ├── StockItemController.php
│   │   │   │   │   │   ├── StockMovementController.php
│   │   │   │   │   │   └── AssetController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Communication/
│   │   │   │   │   │   ├── MessageController.php
│   │   │   │   │   │   ├── AnnouncementController.php
│   │   │   │   │   │   └── NotificationController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Reports/
│   │   │   │   │   │   ├── DashboardController.php
│   │   │   │   │   │   ├── AttendanceReportController.php
│   │   │   │   │   │   ├── FinancialReportController.php
│   │   │   │   │   │   └── StudentReportController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Parent/
│   │   │   │   │   │   ├── ParentDashboardController.php
│   │   │   │   │   │   ├── ParentChildController.php
│   │   │   │   │   │   ├── ParentAttendanceController.php
│   │   │   │   │   │   ├── ParentEvaluationController.php
│   │   │   │   │   │   ├── ParentInvoiceController.php
│   │   │   │   │   │   ├── ParentPaymentController.php
│   │   │   │   │   │   └── ParentEventController.php
│   │   │   │   │   │
│   │   │   │   │   ├── Settings/
│   │   │   │   │   │   └── SettingController.php
│   │   │   │   │   │
│   │   │   │   │   └── Users/
│   │   │   │   │       ├── UserController.php
│   │   │   │   │       ├── RoleController.php
│   │   │   │   │       └── PermissionController.php
│   │   │   │   │
│   │   │   │   └── V2/                  # Future API version
│   │   │   │
│   │   │   └── Controller.php           # Base Controller
│   │   │
│   │   ├── Middleware/
│   │   │   ├── Authenticate.php
│   │   │   ├── CheckPermission.php
│   │   │   ├── CheckRole.php
│   │   │   ├── ForceJsonResponse.php
│   │   │   ├── LogApiRequest.php
│   │   │   └── ValidateApiVersion.php
│   │   │
│   │   ├── Requests/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginRequest.php
│   │   │   │   ├── RegisterRequest.php
│   │   │   │   └── ResetPasswordRequest.php
│   │   │   │
│   │   │   ├── Students/
│   │   │   │   ├── StoreStudentRequest.php
│   │   │   │   ├── UpdateStudentRequest.php
│   │   │   │   └── UploadStudentDocumentRequest.php
│   │   │   │
│   │   │   ├── Attendance/
│   │   │   │   ├── MarkStudentAttendanceRequest.php
│   │   │   │   └── MarkTeacherAttendanceRequest.php
│   │   │   │
│   │   │   ├── Evaluations/
│   │   │   │   ├── StoreEvaluationRequest.php
│   │   │   │   └── StoreEvaluationTemplateRequest.php
│   │   │   │
│   │   │   ├── Events/
│   │   │   │   └── StoreEventRequest.php
│   │   │   │
│   │   │   ├── HR/
│   │   │   │   ├── StoreEmployeeRequest.php
│   │   │   │   ├── StoreLeaveRequestRequest.php
│   │   │   │   └── GeneratePayrollRequest.php
│   │   │   │
│   │   │   ├── Finance/
│   │   │   │   ├── StoreInvoiceRequest.php
│   │   │   │   ├── StorePaymentRequest.php
│   │   │   │   └── StoreExpenseRequest.php
│   │   │   │
│   │   │   └── Admissions/
│   │   │       └── StoreAdmissionApplicationRequest.php
│   │   │
│   │   ├── Resources/
│   │   │   ├── StudentResource.php
│   │   │   ├── StudentCollection.php
│   │   │   ├── GuardianResource.php
│   │   │   ├── ClassResource.php
│   │   │   ├── AttendanceResource.php
│   │   │   ├── EvaluationResource.php
│   │   │   ├── EventResource.php
│   │   │   ├── EmployeeResource.php
│   │   │   ├── PayrollResource.php
│   │   │   ├── InvoiceResource.php
│   │   │   ├── PaymentResource.php
│   │   │   ├── ExpenseResource.php
│   │   │   ├── StockItemResource.php
│   │   │   ├── AssetResource.php
│   │   │   ├── MessageResource.php
│   │   │   ├── AnnouncementResource.php
│   │   │   ├── NotificationResource.php
│   │   │   └── UserResource.php
│   │   │
│   │   └── Kernel.php
│   │
│   ├── Jobs/
│   │   ├── SendAttendanceNotification.php
│   │   ├── SendEvaluationNotification.php
│   │   ├── GeneratePayslipPDF.php
│   │   ├── GenerateInvoicePDF.php
│   │   ├── ProcessPaymentGatewayWebhook.php
│   │   ├── SendWhatsAppNotification.php
│   │   └── SendEmailNotification.php
│   │
│   ├── Listeners/
│   │   ├── SendAttendanceNotificationListener.php
│   │   ├── SendEvaluationPublishedNotification.php
│   │   ├── SendInvoiceCreatedNotification.php
│   │   ├── SendPaymentReceivedNotification.php
│   │   └── LogActivityListener.php
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Permission.php
│   │   ├── Student.php
│   │   ├── Guardian.php
│   │   ├── Class.php (rename to SchoolClass.php due to reserved keyword)
│   │   ├── StudentDocument.php
│   │   ├── StudentAttendance.php
│   │   ├── TeacherAttendance.php
│   │   ├── EvaluationTemplate.php
│   │   ├── EvaluationCriteria.php
│   │   ├── StudentEvaluation.php
│   │   ├── EvaluationScore.php
│   │   ├── EvaluationMedia.php
│   │   ├── Event.php
│   │   ├── EventParticipant.php
│   │   ├── AdmissionApplication.php
│   │   ├── AdmissionDocument.php
│   │   ├── Employee.php
│   │   ├── EmployeeDocument.php
│   │   ├── LeaveRequest.php
│   │   ├── Payroll.php
│   │   ├── FeeStructure.php
│   │   ├── Invoice.php
│   │   ├── Payment.php
│   │   ├── Expense.php
│   │   ├── StockItem.php
│   │   ├── StockMovement.php
│   │   ├── Asset.php
│   │   ├── Message.php
│   │   ├── Announcement.php
│   │   ├── Notification.php
│   │   ├── ActivityLog.php
│   │   └── Setting.php
│   │
│   ├── Notifications/
│   │   ├── AttendanceMarkedNotification.php
│   │   ├── EvaluationPublishedNotification.php
│   │   ├── InvoiceCreatedNotification.php
│   │   ├── PaymentReceivedNotification.php
│   │   ├── LeaveRequestStatusNotification.php
│   │   ├── EventCreatedNotification.php
│   │   └── WelcomeNotification.php
│   │
│   ├── Observers/
│   │   ├── StudentObserver.php
│   │   ├── InvoiceObserver.php
│   │   ├── PaymentObserver.php
│   │   └── AttendanceObserver.php
│   │
│   ├── Policies/
│   │   ├── StudentPolicy.php
│   │   ├── GuardianPolicy.php
│   │   ├── ClassPolicy.php
│   │   ├── AttendancePolicy.php
│   │   ├── EvaluationPolicy.php
│   │   ├── EventPolicy.php
│   │   ├── EmployeePolicy.php
│   │   ├── LeaveRequestPolicy.php
│   │   ├── PayrollPolicy.php
│   │   ├── InvoicePolicy.php
│   │   ├── PaymentPolicy.php
│   │   ├── ExpensePolicy.php
│   │   ├── StockItemPolicy.php
│   │   ├── AssetPolicy.php
│   │   ├── MessagePolicy.php
│   │   ├── AnnouncementPolicy.php
│   │   ├── SettingPolicy.php
│   │   └── UserPolicy.php
│   │
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   ├── RouteServiceProvider.php
│   │   └── RepositoryServiceProvider.php (Optional)
│   │
│   ├── Rules/
│   │   ├── ValidPhoneNumber.php
│   │   ├── ValidNationalId.php
│   │   ├── ValidIBAN.php
│   │   └── UniqueStu dentCode.php
│   │
│   ├── Services/
│   │   ├── AttendanceService.php
│   │   ├── EvaluationService.php
│   │   ├── PayrollService.php
│   │   ├── InvoiceService.php
│   │   ├── PaymentService.php
│   │   ├── PaymentGateway/
│   │   │   ├── PaymentGatewayInterface.php
│   │   │   ├── PaymobGateway.php
│   │   │   └── StripeGateway.php
│   │   ├── Notification/
│   │   │   ├── WhatsAppService.php
│   │   │   └── EmailService.php
│   │   ├── Storage/
│   │   │   └── FileStorageService.php
│   │   └── Report/
│   │       ├── AttendanceReportService.php
│   │       ├── FinancialReportService.php
│   │       └── PDFGeneratorService.php
│   │
│   └── Traits/
│       ├── HasPermissions.php
│       ├── HasRoles.php
│       ├── Searchable.php
│       ├── Filterable.php
│       └── HasActivityLog.php
│
├── bootstrap/
│   ├── app.php
│   └── cache/
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cors.php
│   ├── database.php
│   ├── filesystems.php
│   ├── mail.php
│   ├── queue.php
│   ├── sanctum.php
│   ├── services.php          # Payment gateway, WhatsApp API config
│   └── snms.php              # Custom app configurations
│
├── database/
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── StudentFactory.php
│   │   ├── GuardianFactory.php
│   │   └── [Other factories...]
│   │
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_roles_table.php
│   │   ├── 2024_01_01_000003_create_permissions_table.php
│   │   ├── 2024_01_01_000004_create_role_user_table.php
│   │   ├── 2024_01_01_000005_create_permission_role_table.php
│   │   ├── 2024_01_01_000010_create_guardians_table.php
│   │   ├── 2024_01_01_000011_create_classes_table.php
│   │   ├── 2024_01_01_000012_create_students_table.php
│   │   ├── 2024_01_01_000013_create_student_documents_table.php
│   │   ├── 2024_01_01_000020_create_student_attendance_table.php
│   │   ├── 2024_01_01_000021_create_teacher_attendance_table.php
│   │   ├── 2024_01_01_000030_create_evaluation_templates_table.php
│   │   ├── 2024_01_01_000031_create_evaluation_criteria_table.php
│   │   ├── 2024_01_01_000032_create_student_evaluations_table.php
│   │   ├── 2024_01_01_000033_create_evaluation_scores_table.php
│   │   ├── 2024_01_01_000034_create_evaluation_media_table.php
│   │   ├── 2024_01_01_000040_create_events_table.php
│   │   ├── 2024_01_01_000041_create_event_participants_table.php
│   │   ├── 2024_01_01_000050_create_admission_applications_table.php
│   │   ├── 2024_01_01_000051_create_admission_documents_table.php
│   │   ├── 2024_01_01_000060_create_employees_table.php
│   │   ├── 2024_01_01_000061_create_employee_documents_table.php
│   │   ├── 2024_01_01_000062_create_leave_requests_table.php
│   │   ├── 2024_01_01_000063_create_payroll_table.php
│   │   ├── 2024_01_01_000070_create_fee_structures_table.php
│   │   ├── 2024_01_01_000071_create_invoices_table.php
│   │   ├── 2024_01_01_000072_create_payments_table.php
│   │   ├── 2024_01_01_000073_create_expenses_table.php
│   │   ├── 2024_01_01_000080_create_stock_items_table.php
│   │   ├── 2024_01_01_000081_create_stock_movements_table.php
│   │   ├── 2024_01_01_000082_create_assets_table.php
│   │   ├── 2024_01_01_000090_create_messages_table.php
│   │   ├── 2024_01_01_000091_create_announcements_table.php
│   │   ├── 2024_01_01_000092_create_notifications_table.php
│   │   ├── 2024_01_01_000093_create_activity_logs_table.php
│   │   └── 2024_01_01_000094_create_settings_table.php
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RolesAndPermissionsSeeder.php
│       ├── SettingsSeeder.php
│       ├── DemoDataSeeder.php
│       └── [Other seeders...]
│
├── public/
│   ├── index.php
│   └── .htaccess
│
├── resources/
│   ├── lang/
│   │   ├── en/
│   │   └── ar/              # Arabic translations
│   └── views/
│       ├── emails/
│       │   ├── attendance-notification.blade.php
│       │   ├── invoice-created.blade.php
│       │   └── payment-received.blade.php
│       └── pdfs/
│           ├── payslip.blade.php
│           ├── invoice.blade.php
│           └── report.blade.php
│
├── routes/
│   ├── api.php              # API routes
│   ├── web.php
│   ├── console.php
│   └── channels.php         # Broadcasting channels
│
├── storage/
│   ├── app/
│   │   ├── public/
│   │   │   ├── students/
│   │   │   ├── employees/
│   │   │   ├── documents/
│   │   │   ├── evaluations/
│   │   │   ├── receipts/
│   │   │   └── payslips/
│   │   └── private/
│   ├── framework/
│   └── logs/
│
├── tests/
│   ├── Feature/
│   │   ├── Auth/
│   │   │   ├── LoginTest.php
│   │   │   └── RegistrationTest.php
│   │   ├── Students/
│   │   │   ├── StudentCRUDTest.php
│   │   │   └── StudentAttendanceTest.php
│   │   ├── Finance/
│   │   │   ├── InvoiceTest.php
│   │   │   └── PaymentTest.php
│   │   └── [Other feature tests...]
│   │
│   ├── Unit/
│   │   ├── Models/
│   │   │   ├── StudentTest.php
│   │   │   └── [Other model tests...]
│   │   ├── Services/
│   │   │   ├── PayrollServiceTest.php
│   │   │   └── [Other service tests...]
│   │   └── [Other unit tests...]
│   │
│   ├── TestCase.php
│   └── CreatesApplication.php
│
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── composer.lock
├── package.json             # For Laravel Mix/Vite (if needed)
├── phpunit.xml
└── README.md
```

---

## Frontend - Admin Dashboard Structure

```
admin-dashboard/
├── public/
│   ├── index.html
│   ├── favicon.ico
│   └── assets/
│       └── images/
│
├── src/
│   ├── api/
│   │   ├── client.ts                  # Axios instance
│   │   ├── endpoints.ts               # API endpoint constants
│   │   └── services/
│   │       ├── authService.ts
│   │       ├── studentService.ts
│   │       ├── attendanceService.ts
│   │       ├── evaluationService.ts
│   │       ├── eventService.ts
│   │       ├── admissionService.ts
│   │       ├── hrService.ts
│   │       ├── financeService.ts
│   │       ├── inventoryService.ts
│   │       ├── communicationService.ts
│   │       └── reportService.ts
│   │
│   ├── assets/
│   │   ├── images/
│   │   ├── icons/
│   │   └── styles/
│   │       ├── index.css              # Main Tailwind CSS
│   │       └── custom.css
│   │
│   ├── components/
│   │   ├── common/
│   │   │   ├── Button.tsx
│   │   │   ├── Input.tsx
│   │   │   ├── Select.tsx
│   │   │   ├── Textarea.tsx
│   │   │   ├── Checkbox.tsx
│   │   │   ├── Radio.tsx
│   │   │   ├── DatePicker.tsx
│   │   │   ├── TimePicker.tsx
│   │   │   ├── FileUpload.tsx
│   │   │   ├── Avatar.tsx
│   │   │   ├── Badge.tsx
│   │   │   ├── Card.tsx
│   │   │   ├── Modal.tsx
│   │   │   ├── Drawer.tsx
│   │   │   ├── Tabs.tsx
│   │   │   ├── Table.tsx
│   │   │   ├── Pagination.tsx
│   │   │   ├── SearchBar.tsx
│   │   │   ├── Spinner.tsx
│   │   │   ├── Toast.tsx
│   │   │   └── EmptyState.tsx
│   │   │
│   │   ├── layout/
│   │   │   ├── Sidebar.tsx
│   │   │   ├── Header.tsx
│   │   │   ├── Footer.tsx
│   │   │   ├── MainLayout.tsx
│   │   │   └── AuthLayout.tsx
│   │   │
│   │   ├── students/
│   │   │   ├── StudentList.tsx
│   │   │   ├── StudentCard.tsx
│   │   │   ├── StudentForm.tsx
│   │   │   ├── StudentDetails.tsx
│   │   │   ├── StudentFilter.tsx
│   │   │   └── StudentDocuments.tsx
│   │   │
│   │   ├── attendance/
│   │   │   ├── AttendanceCalendar.tsx
│   │   │   ├── AttendanceMarkForm.tsx
│   │   │   ├── AttendanceStats.tsx
│   │   │   └── AttendanceReport.tsx
│   │   │
│   │   ├── evaluations/
│   │   │   ├── EvaluationForm.tsx
│   │   │   ├── EvaluationList.tsx
│   │   │   ├── EvaluationCard.tsx
│   │   │   ├── EvaluationTemplateBuilder.tsx
│   │   │   └── ScoreInput.tsx
│   │   │
│   │   ├── events/
│   │   │   ├── EventList.tsx
│   │   │   ├── EventCard.tsx
│   │   │   ├── EventForm.tsx
│   │   │   ├── EventParticipants.tsx
│   │   │   └── EventAttendance.tsx
│   │   │
│   │   ├── finance/
│   │   │   ├── InvoiceList.tsx
│   │   │   ├── InvoiceForm.tsx
│   │   │   ├── InvoiceDetails.tsx
│   │   │   ├── PaymentForm.tsx
│   │   │   ├── ExpenseList.tsx
│   │   │   └── FinancialChart.tsx
│   │   │
│   │   ├── hr/
│   │   │   ├── EmployeeList.tsx
│   │   │   ├── EmployeeForm.tsx
│   │   │   ├── LeaveRequestList.tsx
│   │   │   ├── PayrollTable.tsx
│   │   │   └── PayrollGenerator.tsx
│   │   │
│   │   └── dashboard/
│   │       ├── DashboardCard.tsx
│   │       ├── StatsWidget.tsx
│   │       ├── RecentActivity.tsx
│   │       ├── QuickActions.tsx
│   │       └── Charts/
│   │           ├── LineChart.tsx
│   │           ├── BarChart.tsx
│   │           ├── PieChart.tsx
│   │           └── DonutChart.tsx
│   │
│   ├── contexts/
│   │   ├── AuthContext.tsx
│   │   ├── ThemeContext.tsx
│   │   ├── NotificationContext.tsx
│   │   └── PermissionContext.tsx
│   │
│   ├── hooks/
│   │   ├── useAuth.ts
│   │   ├── usePermission.ts
│   │   ├── useDebounce.ts
│   │   ├── useLocalStorage.ts
│   │   ├── useQuery.ts
│   │   ├── useMutation.ts
│   │   ├── useTable.ts
│   │   ├── useForm.ts
│   │   └── useFileUpload.ts
│   │
│   ├── layouts/
│   │   ├── AdminLayout.tsx
│   │   ├── AuthLayout.tsx
│   │   └── BlankLayout.tsx
│   │
│   ├── pages/
│   │   ├── auth/
│   │   │   ├── Login.tsx
│   │   │   ├── ForgotPassword.tsx
│   │   │   └── ResetPassword.tsx
│   │   │
│   │   ├── dashboard/
│   │   │   └── Dashboard.tsx
│   │   │
│   │   ├── students/
│   │   │   ├── StudentListPage.tsx
│   │   │   ├── StudentDetailPage.tsx
│   │   │   ├── CreateStudentPage.tsx
│   │   │   └── EditStudentPage.tsx
│   │   │
│   │   ├── guardians/
│   │   │   ├── GuardianListPage.tsx
│   │   │   └── GuardianDetailPage.tsx
│   │   │
│   │   ├── classes/
│   │   │   ├── ClassListPage.tsx
│   │   │   └── ClassDetailPage.tsx
│   │   │
│   │   ├── attendance/
│   │   │   ├── MarkAttendancePage.tsx
│   │   │   ├── AttendanceReportPage.tsx
│   │   │   └── TeacherAttendancePage.tsx
│   │   │
│   │   ├── evaluations/
│   │   │   ├── EvaluationListPage.tsx
│   │   │   ├── CreateEvaluationPage.tsx
│   │   │   ├── EvaluationDetailPage.tsx
│   │   │   └── TemplateBuilderPage.tsx
│   │   │
│   │   ├── events/
│   │   │   ├── EventListPage.tsx
│   │   │   ├── CreateEventPage.tsx
│   │   │   └── EventDetailPage.tsx
│   │   │
│   │   ├── admissions/
│   │   │   ├── ApplicationListPage.tsx
│   │   │   └── ApplicationDetailPage.tsx
│   │   │
│   │   ├── hr/
│   │   │   ├── EmployeeListPage.tsx
│   │   │   ├── EmployeeDetailPage.tsx
│   │   │   ├── LeaveManagementPage.tsx
│   │   │   └── PayrollPage.tsx
│   │   │
│   │   ├── finance/
│   │   │   ├── InvoicesPage.tsx
│   │   │   ├── PaymentsPage.tsx
│   │   │   ├── ExpensesPage.tsx
│   │   │   └── FinancialReportsPage.tsx
│   │   │
│   │   ├── inventory/
│   │   │   ├── StockListPage.tsx
│   │   │   └── AssetsPage.tsx
│   │   │
│   │   ├── communication/
│   │   │   ├── MessagesPage.tsx
│   │   │   ├── AnnouncementsPage.tsx
│   │   │   └── NotificationsPage.tsx
│   │   │
│   │   ├── reports/
│   │   │   └── ReportsPage.tsx
│   │   │
│   │   ├── settings/
│   │   │   ├── SettingsPage.tsx
│   │   │   ├── ProfilePage.tsx
│   │   │   ├── UsersPage.tsx
│   │   │   └── RolesPermissionsPage.tsx
│   │   │
│   │   └── errors/
│   │       ├── NotFound.tsx
│   │       ├── Unauthorized.tsx
│   │       └── ServerError.tsx
│   │
│   ├── routes/
│   │   ├── index.tsx
│   │   ├── PrivateRoute.tsx
│   │   └── PermissionRoute.tsx
│   │
│   ├── types/
│   │   ├── api.ts
│   │   ├── models.ts
│   │   ├── auth.ts
│   │   ├── student.ts
│   │   ├── attendance.ts
│   │   ├── evaluation.ts
│   │   ├── event.ts
│   │   ├── finance.ts
│   │   └── index.ts
│   │
│   ├── utils/
│   │   ├── constants.ts
│   │   ├── helpers.ts
│   │   ├── formatters.ts
│   │   ├── validators.ts
│   │   ├── dateUtils.ts
│   │   ├── storageUtils.ts
│   │   └── permissions.ts
│   │
│   ├── App.tsx
│   ├── main.tsx
│   └── vite-env.d.ts
│
├── .env.example
├── .eslintrc.js
├── .gitignore
├── .prettierrc
├── index.html
├── package.json
├── postcss.config.js
├── tailwind.config.js
├── tsconfig.json
├── vite.config.ts
└── README.md
```

---

## Frontend - Parent App Structure

```
parent-app/
├── public/
│   ├── index.html
│   ├── manifest.json         # PWA manifest
│   └── service-worker.js     # Service worker for PWA
│
├── src/
│   ├── api/                  # Same structure as admin
│   │   ├── client.ts
│   │   ├── endpoints.ts
│   │   └── services/
│   │       ├── authService.ts
│   │       ├── childService.ts
│   │       ├── attendanceService.ts
│   │       ├── evaluationService.ts
│   │       ├── invoiceService.ts
│   │       ├── eventService.ts
│   │       └── messageService.ts
│   │
│   ├── assets/
│   │   ├── images/
│   │   ├── icons/
│   │   └── styles/
│   │       └── index.css
│   │
│   ├── components/
│   │   ├── common/           # Reusable mobile-first components
│   │   │   ├── Button.tsx
│   │   │   ├── Card.tsx
│   │   │   ├── Avatar.tsx
│   │   │   ├── Badge.tsx
│   │   │   ├── BottomSheet.tsx
│   │   │   ├── PullToRefresh.tsx
│   │   │   └── SwipeableCard.tsx
│   │   │
│   │   ├── layout/
│   │   │   ├── BottomNavigation.tsx
│   │   │   ├── TopBar.tsx
│   │   │   └── MobileLayout.tsx
│   │   │
│   │   ├── dashboard/
│   │   │   ├── ChildSelector.tsx
│   │   │   ├── TodayWidget.tsx
│   │   │   ├── ActivityTimeline.tsx
│   │   │   └── QuickStats.tsx
│   │   │
│   │   ├── attendance/
│   │   │   ├── AttendanceCalendar.tsx
│   │   │   └── AttendanceStats.tsx
│   │   │
│   │   ├── evaluations/
│   │   │   ├── EvaluationCard.tsx
│   │   │   └── ProgressChart.tsx
│   │   │
│   │   ├── media/
│   │   │   ├── MediaGallery.tsx
│   │   │   └── MediaViewer.tsx
│   │   │
│   │   ├── invoices/
│   │   │   ├── InvoiceCard.tsx
│   │   │   └── PaymentButton.tsx
│   │   │
│   │   └── events/
│   │       ├── EventCard.tsx
│   │       └── PermissionForm.tsx
│   │
│   ├── contexts/            # Same as admin
│   │   ├── AuthContext.tsx
│   │   └── ChildContext.tsx
│   │
│   ├── hooks/               # Same as admin + mobile-specific
│   │   ├── useAuth.ts
│   │   ├── useSwipe.ts
│   │   ├── usePullToRefresh.ts
│   │   └── useOffline.ts
│   │
│   ├── pages/
│   │   ├── auth/
│   │   │   ├── Login.tsx
│   │   │   └── ForgotPassword.tsx
│   │   │
│   │   ├── dashboard/
│   │   │   └── Home.tsx
│   │   │
│   │   ├── children/
│   │   │   ├── ChildDashboard.tsx
│   │   │   └── ChildProfile.tsx
│   │   │
│   │   ├── attendance/
│   │   │   └── AttendancePage.tsx
│   │   │
│   │   ├── evaluations/
│   │   │   ├── EvaluationListPage.tsx
│   │   │   └── EvaluationDetailPage.tsx
│   │   │
│   │   ├── media/
│   │   │   └── MediaPage.tsx
│   │   │
│   │   ├── invoices/
│   │   │   ├── InvoiceListPage.tsx
│   │   │   └── PaymentPage.tsx
│   │   │
│   │   ├── events/
│   │   │   ├── EventListPage.tsx
│   │   │   └── EventDetailPage.tsx
│   │   │
│   │   ├── messages/
│   │   │   ├── MessageListPage.tsx
│   │   │   └── MessageDetailPage.tsx
│   │   │
│   │   └── profile/
│   │       ├── ProfilePage.tsx
│   │       └── SettingsPage.tsx
│   │
│   ├── routes/
│   │   ├── index.tsx
│   │   └── PrivateRoute.tsx
│   │
│   ├── types/               # Same as admin
│   │
│   ├── utils/               # Same as admin
│   │
│   ├── App.tsx
│   └── main.tsx
│
├── .env.example
├── .gitignore
├── package.json
├── tailwind.config.js       # Mobile-first configuration
├── tsconfig.json
├── vite.config.ts
└── README.md
```

---

## Key Differences Between Admin & Parent App

### Admin Dashboard
- Desktop-first responsive design
- Complex data tables and forms
- Advanced filtering and reporting
- Multi-role access with permissions
- Comprehensive CRUD operations

### Parent App
- Mobile-first responsive design
- Simple, card-based UI
- Pull-to-refresh functionality
- Bottom navigation
- Read-only with limited actions (payments, permissions)
- PWA support for offline capability
- Swipeable components

---

## Shared Components Strategy

Consider creating a shared component library:

```
packages/
└── ui-components/
    ├── package.json
    └── src/
        ├── Button.tsx
        ├── Card.tsx
        └── ...
```

Both apps can import from this shared library to ensure consistency.

---

**Total Files (Backend)**: 150+
**Total Files (Admin)**: 200+
**Total Files (Parent)**: 100+
**Total Components**: 100+

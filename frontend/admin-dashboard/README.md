# Steps Nursery Management System - Admin Dashboard

Modern, responsive admin dashboard built with React 18, TypeScript, and Tailwind CSS for managing nursery operations.

## Features

- **Authentication & Authorization**: JWT-based authentication with role-based access control (RBAC)
- **Student Management**: Complete CRUD operations for student records, photo uploads, attendance tracking
- **Dashboard Analytics**: Real-time statistics and insights
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Type Safety**: Full TypeScript support for better developer experience
- **Modern UI**: Clean, professional interface with Headless UI components

## Tech Stack

- **React 18.2** - UI library
- **TypeScript 5.3** - Type safety
- **Vite 5.0** - Fast build tool and dev server
- **Tailwind CSS 3.4** - Utility-first CSS framework
- **TanStack Query** - Data fetching and caching
- **React Router v6** - Client-side routing
- **React Hook Form** - Form management
- **Zod** - Schema validation
- **Axios** - HTTP client
- **Headless UI** - Accessible UI components
- **Heroicons** - Beautiful icons

## Getting Started

### Prerequisites

- Node.js 18+ and npm/yarn
- Backend API running on `http://localhost:8000`

### Installation

1. Install dependencies:
```bash
npm install
```

2. Create environment file:
```bash
cp .env.example .env
```

3. Update `.env` with your API URL:
```env
VITE_API_URL=http://localhost:8000/api/v1
VITE_APP_NAME=Steps Nursery Management System
```

4. Start development server:
```bash
npm run dev
```

The application will be available at `http://localhost:5173`

### Build for Production

```bash
npm run build
```

Build files will be in the `dist/` directory.

### Preview Production Build

```bash
npm run preview
```

## Project Structure

```
src/
├── api/                    # API client and services
│   ├── client.ts          # Axios instance with interceptors
│   └── services/          # API service modules
│       ├── authService.ts
│       └── studentService.ts
├── assets/                # Static assets
│   └── styles/           # Global styles
├── components/           # Reusable components
│   ├── common/          # Common UI components
│   │   ├── Button.tsx
│   │   ├── Input.tsx
│   │   └── Card.tsx
│   └── layout/          # Layout components
│       ├── Sidebar.tsx
│       ├── Header.tsx
│       └── MainLayout.tsx
├── contexts/            # React contexts
│   └── AuthContext.tsx  # Authentication state
├── hooks/               # Custom hooks
│   └── useAuth.ts       # Auth hook
├── pages/               # Page components
│   ├── auth/
│   │   └── Login.tsx
│   ├── students/
│   │   └── StudentList.tsx
│   └── Dashboard.tsx
├── routes/              # Routing configuration
│   ├── index.tsx        # Route definitions
│   └── PrivateRoute.tsx # Protected route wrapper
├── types/               # TypeScript types
│   └── index.ts         # Global type definitions
├── utils/               # Utility functions
│   └── cn.ts            # Tailwind class merger
├── App.tsx              # Root component
└── main.tsx             # Application entry point
```

## Authentication

The app uses JWT token-based authentication with Laravel Sanctum:

1. User logs in with email and password
2. Backend returns user data and access token
3. Token is stored in localStorage
4. Token is sent with every API request via Authorization header
5. On 401 response, user is redirected to login

### Demo Credentials

```
Admin:   admin@stepsnursery.com / password
Teacher: teacher@stepsnursery.com / password
Parent:  parent@stepsnursery.com / password
```

## Key Features

### Role-Based Access Control (RBAC)

The app implements granular permission checking:

```typescript
// Check permission
const canCreate = hasPermission('students.create');

// Check role
const isAdmin = hasRole('admin');
const isStaff = hasRole(['admin', 'teacher']);
```

Protected routes automatically check permissions:

```typescript
<PrivateRoute requiredPermission="students.view_any">
  <StudentList />
</PrivateRoute>
```

### API Integration

Services are organized by module:

```typescript
// Auth service
await authService.login({ email, password });
await authService.logout();
const user = await authService.getCurrentUser();

// Student service
const students = await studentService.getStudents({ search: 'John' });
const student = await studentService.getStudent(123);
await studentService.createStudent(data);
await studentService.uploadPhoto(123, file);
```

### State Management

Using TanStack Query for server state:

```typescript
const { data, isLoading, error } = useQuery({
  queryKey: ['students', filters],
  queryFn: () => studentService.getStudents(filters),
});
```

### Form Handling

React Hook Form with Zod validation:

```typescript
const schema = z.object({
  email: z.string().email('Invalid email'),
  password: z.string().min(6, 'Password too short'),
});

const { register, handleSubmit, formState: { errors } } = useForm({
  resolver: zodResolver(schema),
});
```

## Available Routes

- `/login` - Login page
- `/dashboard` - Main dashboard with stats
- `/students` - Student list and management
- `/classes` - Class management (coming soon)
- `/attendance` - Attendance tracking (coming soon)
- `/evaluations` - Student evaluations (coming soon)
- `/finance` - Financial management (coming soon)
- `/reports` - Reports and analytics (coming soon)
- `/settings` - System settings (coming soon)
- `/profile` - User profile (coming soon)

## Development Guidelines

### Component Structure

```typescript
import React from 'react';
import { ComponentProps } from './types';

export const Component: React.FC<ComponentProps> = ({ prop1, prop2 }) => {
  // Hooks
  const [state, setState] = useState();

  // Handlers
  const handleClick = () => {};

  // Render
  return <div>{/* JSX */}</div>;
};
```

### Styling

Use Tailwind utility classes:

```typescript
<div className="flex items-center justify-between p-4 bg-white rounded-lg shadow">
  <h1 className="text-2xl font-bold text-gray-900">Title</h1>
</div>
```

For dynamic classes, use the `cn` utility:

```typescript
import { cn } from '@/utils/cn';

<button className={cn(
  'px-4 py-2 rounded',
  isActive ? 'bg-blue-600' : 'bg-gray-200'
)}>
  Click me
</button>
```

### API Error Handling

Errors are automatically handled by Axios interceptors:
- 401 Unauthorized → Redirect to login
- 403 Forbidden → Show permission error
- Network errors → Log to console

Custom error handling:

```typescript
try {
  await studentService.createStudent(data);
} catch (error: any) {
  const message = error.response?.data?.message || 'An error occurred';
  console.error(message);
}
```

## Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `VITE_API_URL` | Backend API base URL | `http://localhost:8000/api/v1` |
| `VITE_APP_NAME` | Application name | `Steps Nursery Management System` |

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## License

Proprietary - Steps Nursery Management System

## Related Projects

- [Backend API](../backend/) - Laravel 12 REST API
- [Parent App](../parent-app/) - Mobile-first parent portal (coming soon)

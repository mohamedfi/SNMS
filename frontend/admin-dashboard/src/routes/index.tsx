import React from 'react';
import { createBrowserRouter, Navigate } from 'react-router-dom';
import { MainLayout } from '../components/layout/MainLayout';
import { PrivateRoute } from './PrivateRoute';
import { Login } from '../pages/auth/Login';
import { Dashboard } from '../pages/Dashboard';
import { StudentList } from '../pages/students/StudentList';

export const router = createBrowserRouter([
  {
    path: '/login',
    element: <Login />,
  },
  {
    path: '/',
    element: (
      <PrivateRoute>
        <MainLayout />
      </PrivateRoute>
    ),
    children: [
      {
        index: true,
        element: <Navigate to="/dashboard" replace />,
      },
      {
        path: 'dashboard',
        element: <Dashboard />,
      },
      {
        path: 'students',
        element: (
          <PrivateRoute requiredPermission="students.view_any">
            <StudentList />
          </PrivateRoute>
        ),
      },
      // Placeholder routes for other modules
      {
        path: 'classes',
        element: <div className="text-center py-12">Classes page (coming soon)</div>,
      },
      {
        path: 'attendance',
        element: <div className="text-center py-12">Attendance page (coming soon)</div>,
      },
      {
        path: 'evaluations',
        element: <div className="text-center py-12">Evaluations page (coming soon)</div>,
      },
      {
        path: 'finance',
        element: <div className="text-center py-12">Finance page (coming soon)</div>,
      },
      {
        path: 'reports',
        element: <div className="text-center py-12">Reports page (coming soon)</div>,
      },
      {
        path: 'settings',
        element: <div className="text-center py-12">Settings page (coming soon)</div>,
      },
      {
        path: 'profile',
        element: <div className="text-center py-12">Profile page (coming soon)</div>,
      },
    ],
  },
  {
    path: '*',
    element: (
      <div className="flex items-center justify-center min-h-screen">
        <div className="text-center">
          <h1 className="text-4xl font-bold text-gray-900 mb-4">404</h1>
          <p className="text-gray-600 mb-4">Page not found</p>
          <a href="/dashboard" className="text-primary-600 hover:text-primary-700">
            Go to Dashboard
          </a>
        </div>
      </div>
    ),
  },
]);

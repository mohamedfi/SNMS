import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import {
  HomeIcon,
  UserGroupIcon,
  AcademicCapIcon,
  CalendarIcon,
  DocumentTextIcon,
  CurrencyDollarIcon,
  ChartBarIcon,
  Cog6ToothIcon,
} from '@heroicons/react/24/outline';
import { useAuth } from '../../hooks/useAuth';
import { cn } from '../../utils/cn';

interface NavItem {
  name: string;
  href: string;
  icon: React.ComponentType<React.SVGProps<SVGSVGElement>>;
  permission?: string;
}

const navigation: NavItem[] = [
  { name: 'Dashboard', href: '/dashboard', icon: HomeIcon },
  { name: 'Students', href: '/students', icon: UserGroupIcon, permission: 'students.view_any' },
  { name: 'Classes', href: '/classes', icon: AcademicCapIcon, permission: 'classes.view_any' },
  { name: 'Attendance', href: '/attendance', icon: CalendarIcon, permission: 'attendance.students.view_any' },
  { name: 'Evaluations', href: '/evaluations', icon: DocumentTextIcon, permission: 'evaluations.view_any' },
  { name: 'Finance', href: '/finance', icon: CurrencyDollarIcon, permission: 'invoices.view_any' },
  { name: 'Reports', href: '/reports', icon: ChartBarIcon, permission: 'reports.dashboard' },
  { name: 'Settings', href: '/settings', icon: Cog6ToothIcon, permission: 'settings.view' },
];

export const Sidebar: React.FC = () => {
  const location = useLocation();
  const { hasPermission } = useAuth();

  const filteredNavigation = navigation.filter(item => {
    if (!item.permission) return true;
    return hasPermission(item.permission);
  });

  return (
    <div className="flex flex-col w-64 bg-gray-900 min-h-screen">
      {/* Logo */}
      <div className="flex items-center justify-center h-16 px-4 bg-gray-800">
        <h1 className="text-xl font-bold text-white">Steps Nursery</h1>
      </div>

      {/* Navigation */}
      <nav className="flex-1 px-2 py-4 space-y-1">
        {filteredNavigation.map((item) => {
          const isActive = location.pathname.startsWith(item.href);
          return (
            <Link
              key={item.name}
              to={item.href}
              className={cn(
                'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors',
                isActive
                  ? 'bg-gray-800 text-white'
                  : 'text-gray-300 hover:bg-gray-800 hover:text-white'
              )}
            >
              <item.icon className="w-5 h-5 mr-3" />
              {item.name}
            </Link>
          );
        })}
      </nav>

      {/* Footer */}
      <div className="p-4 border-t border-gray-800">
        <p className="text-xs text-gray-500 text-center">
          © 2024 Steps Nursery
        </p>
      </div>
    </div>
  );
};

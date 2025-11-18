import React from 'react';
import {
  UserGroupIcon,
  AcademicCapIcon,
  CalendarIcon,
  CurrencyDollarIcon,
} from '@heroicons/react/24/outline';
import { Card, CardHeader, CardTitle } from '../components/common/Card';

interface StatCardProps {
  title: string;
  value: string | number;
  change?: string;
  icon: React.ComponentType<React.SVGProps<SVGSVGElement>>;
  iconBgColor: string;
  iconColor: string;
}

const StatCard: React.FC<StatCardProps> = ({
  title,
  value,
  change,
  icon: Icon,
  iconBgColor,
  iconColor,
}) => {
  return (
    <Card>
      <div className="flex items-center justify-between">
        <div>
          <p className="text-sm font-medium text-gray-600">{title}</p>
          <p className="text-3xl font-bold text-gray-900 mt-2">{value}</p>
          {change && (
            <p className="text-sm text-green-600 mt-1">{change}</p>
          )}
        </div>
        <div className={`p-3 rounded-full ${iconBgColor}`}>
          <Icon className={`w-8 h-8 ${iconColor}`} />
        </div>
      </div>
    </Card>
  );
};

export const Dashboard: React.FC = () => {
  // Mock data - will be replaced with API calls
  const stats = {
    totalStudents: 245,
    activeClasses: 12,
    todayAttendance: '92%',
    monthlyRevenue: 'EGP 125,000',
  };

  return (
    <div>
      {/* Page Header */}
      <div className="mb-6">
        <h1 className="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p className="text-gray-600 mt-1">
          Overview of your nursery management system
        </p>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <StatCard
          title="Total Students"
          value={stats.totalStudents}
          change="+12 this month"
          icon={UserGroupIcon}
          iconBgColor="bg-blue-100"
          iconColor="text-blue-600"
        />
        <StatCard
          title="Active Classes"
          value={stats.activeClasses}
          icon={AcademicCapIcon}
          iconBgColor="bg-green-100"
          iconColor="text-green-600"
        />
        <StatCard
          title="Today's Attendance"
          value={stats.todayAttendance}
          change="225 of 245 students"
          icon={CalendarIcon}
          iconBgColor="bg-yellow-100"
          iconColor="text-yellow-600"
        />
        <StatCard
          title="Monthly Revenue"
          value={stats.monthlyRevenue}
          change="+8% from last month"
          icon={CurrencyDollarIcon}
          iconBgColor="bg-purple-100"
          iconColor="text-purple-600"
        />
      </div>

      {/* Additional Content */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Recent Activities */}
        <Card>
          <CardHeader>
            <CardTitle>Recent Activities</CardTitle>
          </CardHeader>
          <div className="space-y-4">
            <div className="flex items-start">
              <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></div>
              <div>
                <p className="text-sm font-medium text-gray-900">
                  New student enrolled
                </p>
                <p className="text-xs text-gray-500">Sarah Ahmed - Class A1</p>
                <p className="text-xs text-gray-400 mt-1">2 hours ago</p>
              </div>
            </div>
            <div className="flex items-start">
              <div className="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
              <div>
                <p className="text-sm font-medium text-gray-900">
                  Payment received
                </p>
                <p className="text-xs text-gray-500">Invoice #INV-2024-123</p>
                <p className="text-xs text-gray-400 mt-1">5 hours ago</p>
              </div>
            </div>
            <div className="flex items-start">
              <div className="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3"></div>
              <div>
                <p className="text-sm font-medium text-gray-900">
                  Attendance marked
                </p>
                <p className="text-xs text-gray-500">Class B2 - 18 students present</p>
                <p className="text-xs text-gray-400 mt-1">1 day ago</p>
              </div>
            </div>
          </div>
        </Card>

        {/* Upcoming Events */}
        <Card>
          <CardHeader>
            <CardTitle>Upcoming Events</CardTitle>
          </CardHeader>
          <div className="space-y-4">
            <div className="border-l-4 border-blue-500 pl-4 py-2">
              <p className="text-sm font-medium text-gray-900">
                Parent-Teacher Meeting
              </p>
              <p className="text-xs text-gray-500">Tomorrow at 10:00 AM</p>
            </div>
            <div className="border-l-4 border-green-500 pl-4 py-2">
              <p className="text-sm font-medium text-gray-900">
                Annual Day Celebration
              </p>
              <p className="text-xs text-gray-500">March 15, 2024</p>
            </div>
            <div className="border-l-4 border-purple-500 pl-4 py-2">
              <p className="text-sm font-medium text-gray-900">
                Field Trip - Cairo Zoo
              </p>
              <p className="text-xs text-gray-500">March 20, 2024</p>
            </div>
          </div>
        </Card>
      </div>
    </div>
  );
};

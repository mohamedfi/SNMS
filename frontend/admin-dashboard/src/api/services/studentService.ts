import apiClient from '../client';
import {
  ApiResponse,
  PaginatedResponse,
  Student,
  CreateStudentData,
  AttendanceSummary,
} from '../../types';

export interface StudentFilters {
  search?: string;
  status?: string;
  class_id?: number;
  gender?: string;
  page?: number;
  per_page?: number;
}

export const studentService = {
  /**
   * Get all students with filters
   */
  getStudents: async (filters?: StudentFilters): Promise<PaginatedResponse<Student>> => {
    const response = await apiClient.get<PaginatedResponse<Student>>('/students', {
      params: filters,
    });
    return response.data;
  },

  /**
   * Get single student by ID
   */
  getStudent: async (id: number): Promise<Student> => {
    const response = await apiClient.get<ApiResponse<Student>>(`/students/${id}`);
    return response.data.data!;
  },

  /**
   * Create new student
   */
  createStudent: async (data: CreateStudentData): Promise<Student> => {
    const response = await apiClient.post<ApiResponse<Student>>('/students', data);
    return response.data.data!;
  },

  /**
   * Update student
   */
  updateStudent: async (id: number, data: Partial<CreateStudentData>): Promise<Student> => {
    const response = await apiClient.put<ApiResponse<Student>>(`/students/${id}`, data);
    return response.data.data!;
  },

  /**
   * Delete student
   */
  deleteStudent: async (id: number): Promise<void> => {
    await apiClient.delete(`/students/${id}`);
  },

  /**
   * Upload student photo
   */
  uploadPhoto: async (id: number, photo: File): Promise<Student> => {
    const formData = new FormData();
    formData.append('photo', photo);

    const response = await apiClient.post<ApiResponse<Student>>(
      `/students/${id}/photo`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }
    );
    return response.data.data!;
  },

  /**
   * Get student attendance summary
   */
  getAttendanceSummary: async (id: number): Promise<AttendanceSummary> => {
    const response = await apiClient.get<ApiResponse<AttendanceSummary>>(
      `/students/${id}/attendance-summary`
    );
    return response.data.data!;
  },
};

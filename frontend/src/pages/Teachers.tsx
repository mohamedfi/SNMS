const Teachers = () => {
  return (
    <div className="px-4 py-6 sm:px-0">
      <div className="bg-white shadow rounded-lg p-6">
        <h2 className="text-2xl font-bold text-gray-900 mb-4">Teachers Management</h2>
        <p className="text-gray-600 mb-4">
          Manage teacher profiles, schedules, and assignments.
        </p>

        <div className="mb-4">
          <button className="bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-700">
            Add New Teacher
          </button>
        </div>

        <div className="border-t border-gray-200 pt-4">
          <p className="text-gray-500 text-sm">No teachers found. Click "Add New Teacher" to get started.</p>
        </div>
      </div>
    </div>
  )
}

export default Teachers

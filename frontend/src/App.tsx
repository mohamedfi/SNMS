import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import { ThemeProvider } from './contexts/ThemeContext'
import Layout from './components/Layout'
import Dashboard from './pages/Dashboard'
import Login from './pages/Login'
import Students from './pages/Students'
import Teachers from './pages/Teachers'
import Attendance from './pages/Attendance'
import Admissions from './pages/Admissions'
import Evaluations from './pages/Evaluations'
import Events from './pages/Events'
import HR from './pages/HR'
import Finance from './pages/Finance'
import Inventory from './pages/Inventory'

function App() {
  return (
    <ThemeProvider>
      <Router>
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route path="/" element={<Layout />}>
            <Route index element={<Dashboard />} />
            <Route path="students" element={<Students />} />
            <Route path="admissions" element={<Admissions />} />
            <Route path="teachers" element={<Teachers />} />
            <Route path="attendance" element={<Attendance />} />
            <Route path="evaluations" element={<Evaluations />} />
            <Route path="events" element={<Events />} />
            <Route path="hr" element={<HR />} />
            <Route path="finance" element={<Finance />} />
            <Route path="inventory" element={<Inventory />} />
          </Route>
        </Routes>
      </Router>
    </ThemeProvider>
  )
}

export default App

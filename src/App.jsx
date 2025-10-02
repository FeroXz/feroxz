import { Routes, Route } from 'react-router-dom';
import Layout from './components/Layout.jsx';
import AdminLayout from './components/AdminLayout.jsx';
import ProtectedRoute from './components/ProtectedRoute.jsx';
import Home from './pages/Home.jsx';
import PageView from './pages/PageView.jsx';
import Gallery from './pages/Gallery.jsx';
import GeneticsIndex from './pages/GeneticsIndex.jsx';
import GeneticsSpecies from './pages/GeneticsSpecies.jsx';
import GeneticsCalculator from './pages/GeneticsCalculator.jsx';
import AdminLogin from './pages/AdminLogin.jsx';
import AdminDashboard from './pages/admin/AdminDashboard.jsx';
import AdminPosts from './pages/admin/AdminPosts.jsx';
import AdminPages from './pages/admin/AdminPages.jsx';
import AdminGallery from './pages/admin/AdminGallery.jsx';
import AdminGenetics from './pages/admin/AdminGenetics.jsx';
import NotFound from './pages/NotFound.jsx';

export default function App() {
  return (
    <Routes>
      <Route path="/" element={<Layout />}> 
        <Route index element={<Home />} />
        <Route path="page/:slug" element={<PageView />} />
        <Route path="gallery" element={<Gallery />} />
        <Route path="genetics" element={<GeneticsIndex />} />
        <Route path="genetics/:speciesSlug" element={<GeneticsSpecies />} />
        <Route path="genetics/:speciesSlug/calculator" element={<GeneticsCalculator />} />
        <Route path="admin/login" element={<AdminLogin />} />
        <Route
          path="admin"
          element={(
            <ProtectedRoute>
              <AdminLayout />
            </ProtectedRoute>
          )}
        >
          <Route index element={<AdminDashboard />} />
          <Route path="posts" element={<AdminPosts />} />
          <Route path="pages" element={<AdminPages />} />
          <Route path="gallery" element={<AdminGallery />} />
          <Route path="genetics" element={<AdminGenetics />} />
        </Route>
        <Route path="*" element={<NotFound />} />
      </Route>
    </Routes>
  );
}

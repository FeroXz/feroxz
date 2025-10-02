import { NavLink, Outlet } from 'react-router-dom';
import { useAuth } from '../context/AuthContext.jsx';

const adminNav = [
  { to: '/admin', label: 'Übersicht', end: true },
  { to: '/admin/posts', label: 'Beiträge' },
  { to: '/admin/pages', label: 'Seiten' },
  { to: '/admin/gallery', label: 'Galerie' },
  { to: '/admin/genetics', label: 'Genetik' }
];

export default function AdminLayout() {
  const { logout, username } = useAuth();

  return (
    <div className="admin-shell">
      <aside className="admin-sidebar">
        <div className="admin-sidebar__header">
          <h1>Feroxz Admin</h1>
          <p className="admin-user">Angemeldet als {username}</p>
          <button type="button" className="button button--ghost" onClick={logout}>
            Abmelden
          </button>
        </div>
        <nav className="admin-nav">
          {adminNav.map((item) => (
            <NavLink
              key={item.to}
              to={item.to}
              end={item.end}
              className={({ isActive }) => (isActive ? 'active' : undefined)}
            >
              {item.label}
            </NavLink>
          ))}
        </nav>
      </aside>
      <section className="admin-content">
        <Outlet />
      </section>
    </div>
  );
}

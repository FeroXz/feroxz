import { NavLink, Outlet } from 'react-router-dom';
import { useMemo } from 'react';

const navigation = [
  { to: '/', label: 'Startseite' },
  { to: '/gallery', label: 'Galerie' },
  { to: '/genetics', label: 'Genetik' },
  { to: '/admin/login', label: 'Admin' }
];

export default function Layout() {
  const currentYear = useMemo(() => new Date().getFullYear(), []);

  return (
    <div className="app-shell">
      <header className="app-header">
        <div className="app-header__inner">
          <NavLink to="/" className="brand">
            Feroxz CMS
          </NavLink>
          <nav className="main-nav">
            {navigation.map((item) => (
              <NavLink key={item.to} to={item.to} className={({ isActive }) => (isActive ? 'active' : undefined)}>
                {item.label}
              </NavLink>
            ))}
          </nav>
        </div>
      </header>
      <main className="app-main">
        <Outlet />
      </main>
      <footer className="app-footer">
        <div className="app-footer__inner">
          <p>© {currentYear} Feroxz – React Mini CMS mit Genetik-Rechner</p>
        </div>
      </footer>
    </div>
  );
}

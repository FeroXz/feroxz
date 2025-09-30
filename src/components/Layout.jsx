import { NavLink, Outlet } from 'react-router-dom';
import { ThemeToggle } from './ThemeToggle.jsx';
import { useAppData } from '../context/AppDataContext.jsx';

export function Layout({ theme, onToggleTheme }) {
  const {
    state: { settings }
  } = useAppData();

  return (
    <div style={{ minHeight: '100vh', display: 'flex', flexDirection: 'column' }}>
      <header style={{ padding: '1.5rem 0' }}>
        <div className="container" style={{ display: 'flex', alignItems: 'center', gap: '1.5rem' }}>
          <NavLink to="/" style={{ textDecoration: 'none', flexGrow: 1 }}>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.25rem' }}>
              <span className="tag">FeroxZ</span>
              <strong style={{ fontSize: '1.45rem', letterSpacing: '0.03em' }}>{settings.siteName}</strong>
              <span style={{ color: 'var(--text-muted)', fontSize: '0.95rem' }}>{settings.tagline}</span>
            </div>
          </NavLink>
          <nav style={{ display: 'flex', alignItems: 'center', gap: '0.75rem' }}>
            <NavItem to="/care-guides" label="Pflegeleitfäden" />
            <NavItem to="/genetics" label="Genetik Rechner" />
            <NavItem to="/animals" label="Tierübersicht" />
            <NavItem to="/admin" label="Admin" />
          </nav>
          <ThemeToggle theme={theme} onToggle={onToggleTheme} />
        </div>
      </header>
      <main style={{ flexGrow: 1, paddingBottom: '4rem' }}>
        <Outlet />
      </main>
      <footer style={{ padding: '2rem 0', marginTop: 'auto' }}>
        <div className="container glass-panel" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', gap: '1rem' }}>
          <div>
            <strong>{settings.siteName}</strong>
            <p style={{ margin: '0.25rem 0 0', color: 'var(--text-muted)' }}>{settings.tagline}</p>
          </div>
          <span style={{ fontSize: '0.85rem', color: 'var(--text-muted)' }}>Version {settings.footerVersion}</span>
        </div>
      </footer>
    </div>
  );
}

function NavItem({ to, label }) {
  return (
    <NavLink
      to={to}
      style={({ isActive }) => ({
        padding: '0.4rem 0.95rem',
        borderRadius: '999px',
        border: '1px solid transparent',
        background: isActive ? 'rgba(91, 229, 132, 0.24)' : 'rgba(255,255,255,0.05)',
        color: 'inherit',
        fontWeight: 600,
        letterSpacing: '0.02em'
      })}
    >
      {label}
    </NavLink>
  );
}

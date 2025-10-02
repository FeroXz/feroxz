import { useState } from 'react';
import { Navigate, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext.jsx';

export default function AdminLogin() {
  const { isAuthenticated, login } = useAuth();
  const location = useLocation();
  const [username, setUsername] = useState('admin');
  const [password, setPassword] = useState('12345678');
  const [error, setError] = useState('');

  if (isAuthenticated) {
    const from = location.state?.from?.pathname ?? '/admin';
    return <Navigate to={from} replace />;
  }

  const handleSubmit = (event) => {
    event.preventDefault();
    const result = login(username, password);

    if (!result.success) {
      setError(result.message);
    }
  };

  return (
    <article className="article" style={{ maxWidth: '520px', margin: '0 auto' }}>
      <h1>Adminbereich</h1>
      <p>Melde dich mit den Standardzugängen <code>admin</code> / <code>12345678</code> an.</p>
      <form className="form-grid" onSubmit={handleSubmit}>
        <label>
          Benutzername
          <input value={username} onChange={(event) => setUsername(event.target.value)} autoComplete="username" />
        </label>
        <label>
          Passwort
          <input
            type="password"
            value={password}
            onChange={(event) => setPassword(event.target.value)}
            autoComplete="current-password"
          />
        </label>
        {error && <p style={{ color: '#ef4444' }}>{error}</p>}
        <button type="submit" className="button">
          Anmelden
        </button>
      </form>
    </article>
  );
}

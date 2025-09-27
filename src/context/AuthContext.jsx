import { createContext, useCallback, useContext, useEffect, useMemo, useState } from 'react';

const AuthContext = createContext(null);
const STORAGE_KEY = 'feroxz-cms-authenticated';
const ADMIN_USER = 'admin';
const ADMIN_PASSWORD = '12345678';

export function AuthProvider({ children }) {
  const [isAuthenticated, setIsAuthenticated] = useState(() => {
    if (typeof window === 'undefined') {
      return false;
    }

    return window.sessionStorage.getItem(STORAGE_KEY) === 'true';
  });

  useEffect(() => {
    if (typeof window === 'undefined') {
      return;
    }

    if (isAuthenticated) {
      window.sessionStorage.setItem(STORAGE_KEY, 'true');
    } else {
      window.sessionStorage.removeItem(STORAGE_KEY);
    }
  }, [isAuthenticated]);

  const login = useCallback((username, password) => {
    if (username === ADMIN_USER && password === ADMIN_PASSWORD) {
      setIsAuthenticated(true);
      return { success: true };
    }

    return { success: false, message: 'Ungültige Zugangsdaten' };
  }, []);

  const logout = useCallback(() => {
    setIsAuthenticated(false);
  }, []);

  const value = useMemo(
    () => ({ isAuthenticated, login, logout, username: ADMIN_USER }),
    [isAuthenticated, login, logout]
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  const context = useContext(AuthContext);

  if (!context) {
    throw new Error('useAuth muss innerhalb des AuthProvider verwendet werden');
  }

  return context;
}

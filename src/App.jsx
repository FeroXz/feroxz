import { useEffect, useMemo, useState } from 'react';
import { Route, Routes } from 'react-router-dom';
import { Layout } from './components/Layout.jsx';
import { HomePage } from './pages/HomePage.jsx';
import { CareGuidesPage } from './pages/CareGuidesPage.jsx';
import { AnimalsPage } from './pages/AnimalsPage.jsx';
import { GeneticsCalculatorPage } from './pages/GeneticsCalculatorPage.jsx';
import { AdminPage } from './pages/AdminPage.jsx';
import { NotFoundPage } from './pages/NotFoundPage.jsx';

const THEME_KEY = 'feroxz-theme-preference';

export default function App() {
  const [theme, setTheme] = useState(() => {
    if (typeof window === 'undefined') return 'dark';
    return window.localStorage.getItem(THEME_KEY) || 'dark';
  });

  useEffect(() => {
    document.documentElement.setAttribute('data-theme', theme);
    if (typeof window !== 'undefined') {
      window.localStorage.setItem(THEME_KEY, theme);
    }
  }, [theme]);

  const layoutProps = useMemo(
    () => ({
      theme,
      onToggleTheme: () => setTheme((prev) => (prev === 'dark' ? 'light' : 'dark'))
    }),
    [theme]
  );

  return (
    <Routes>
      <Route element={<Layout {...layoutProps} />}>
        <Route path="/" element={<HomePage />} />
        <Route path="/care-guides" element={<CareGuidesPage />} />
        <Route path="/animals" element={<AnimalsPage />} />
        <Route path="/genetics" element={<GeneticsCalculatorPage />} />
        <Route path="/admin" element={<AdminPage />} />
        <Route path="*" element={<NotFoundPage />} />
      </Route>
    </Routes>
  );
}

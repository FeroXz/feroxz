export function ThemeToggle({ theme, onToggle }) {
  return (
    <button
      type="button"
      onClick={onToggle}
      style={{
        borderRadius: '999px',
        border: '1px solid var(--border)',
        background: 'rgba(91, 229, 132, 0.15)',
        color: 'var(--text)',
        padding: '0.45rem 1rem',
        display: 'inline-flex',
        alignItems: 'center',
        gap: '0.5rem',
        cursor: 'pointer',
        fontWeight: 600,
        letterSpacing: '0.02em'
      }}
    >
      <span>{theme === 'light' ? '🌞' : '🌙'}</span>
      <span>{theme === 'light' ? 'Light' : 'Dark'} Mode</span>
    </button>
  );
}

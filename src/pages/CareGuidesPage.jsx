import { useAppData } from '../context/AppDataContext.jsx';

export function CareGuidesPage() {
  const {
    state: { careGuides }
  } = useAppData();

  return (
    <section className="container" style={{ display: 'grid', gap: '2rem' }}>
      <header className="glass-panel light" style={{ display: 'grid', gap: '0.75rem' }}>
        <span className="tag">Pflegeleitfäden</span>
        <h1 style={{ margin: 0 }}>Wissen für verantwortungsvolle Haltung</h1>
        <p style={{ color: 'var(--text-muted)', margin: 0 }}>
          Alle Inhalte basieren auf aktuellen Haltungsempfehlungen europäischer Terraristikverbände und wurden mit Fokus auf
          praxisnahe Umsetzung zusammengestellt.
        </p>
      </header>
      <div className="grid" style={{ gap: '2rem' }}>
        {careGuides.map((guide) => (
          <article key={guide.id} className="glass-panel" style={{ display: 'grid', gap: '1.25rem' }}>
            <div>
              <span className="tag">{guide.species}</span>
              <h2 style={{ margin: '0.5rem 0 0' }}>{guide.commonName}</h2>
              <p style={{ color: 'var(--text-muted)' }}>{guide.intro}</p>
            </div>
            <div className="grid" style={{ gap: '1.5rem' }}>
              {guide.sections.map((section) => (
                <section key={section.title} style={{ display: 'grid', gap: '0.5rem' }}>
                  <h3 style={{ margin: 0 }}>{section.title}</h3>
                  <p style={{ margin: 0, lineHeight: 1.65, color: 'var(--text-muted)' }}>{section.content}</p>
                </section>
              ))}
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}

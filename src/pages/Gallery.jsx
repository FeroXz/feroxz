import { useMemo } from 'react';
import { useData } from '../context/DataContext.jsx';

export default function Gallery() {
  const { galleryItems } = useData();

  const sortedItems = useMemo(
    () => [...galleryItems].sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt)),
    [galleryItems]
  );

  return (
    <div>
      <section className="section-title">
        <h2>Galerie</h2>
        <p>Einblicke in aktuelle Projekte und Tiere.</p>
      </section>
      <div className="gallery-grid">
        {sortedItems.map((item) => (
          <article key={item.id} className="gallery-card">
            {item.imageUrl && <img src={item.imageUrl} alt={item.title} loading="lazy" />}
            <div className="gallery-card__body">
              <h3>{item.title}</h3>
              <p>{item.description}</p>
              <div className="list-item__meta">
                <span className="badge">{new Date(item.createdAt).toLocaleDateString('de-DE')}</span>
              </div>
            </div>
          </article>
        ))}
      </div>
    </div>
  );
}

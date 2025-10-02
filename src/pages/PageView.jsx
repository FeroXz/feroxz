import { useParams } from 'react-router-dom';
import { useData } from '../context/DataContext.jsx';

export default function PageView() {
  const { slug } = useParams();
  const { pages } = useData();

  const page = pages.find((entry) => entry.slug === slug);

  if (!page) {
    return (
      <article className="article">
        <h1>Seite nicht gefunden</h1>
        <p>Der gewünschte Inhalt existiert nicht (mehr).</p>
      </article>
    );
  }

  return (
    <article className="article">
      <h1>{page.title}</h1>
      <p>{page.content}</p>
    </article>
  );
}

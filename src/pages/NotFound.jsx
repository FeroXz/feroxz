import { Link } from 'react-router-dom';

export default function NotFound() {
  return (
    <article className="article">
      <h1>404 – Seite nicht gefunden</h1>
      <p>Der gewünschte Inhalt existiert nicht. Nutze die Navigation, um fortzufahren.</p>
      <Link className="button" to="/">
        Zur Startseite
      </Link>
    </article>
  );
}

import { createContext, useCallback, useContext, useEffect, useMemo, useState } from 'react';
import { createId } from '../utils/id.js';

const STORAGE_KEY = 'feroxz-cms-data-v1';
const VERSION = 1;

const defaultData = {
  version: VERSION,
  posts: [
    {
      id: 'post-welcome',
      title: 'Willkommen im Feroxz CMS',
      slug: 'willkommen-feroxz-cms',
      excerpt: 'Starte mit dem integrierten Adminbereich, um Inhalte, Galerie und Genetik-Daten zu pflegen.',
      content:
        'Dieses React-basierte Mini-CMS speichert deine Inhalte lokal im Browser. Melde dich über den Adminbereich an, um Beiträge, Seiten, die Galerie und Genetik-Einträge zu verwalten. Alle Änderungen werden automatisch persistiert.',
      publishedAt: '2024-01-12T09:15:00.000Z',
      updatedAt: '2024-01-12T09:15:00.000Z'
    },
    {
      id: 'post-exhibit',
      title: 'Neue Bartagamen im Schaufenster',
      slug: 'neue-bartagamen',
      excerpt: 'Unsere Ausstellung wurde um weitere Farbmorphen ergänzt – inklusive Hypo, Translucent und Leatherback.',
      content:
        'Die aktuelle Ausstellung zeigt eine breite Auswahl an *Pogona vitticeps* Morphen. Neben Klassikern wie Hypo und Translucent findest du auch seltenere Linien. Jedes Tier ist mit Herkunft, Genetik und Pflegetipps versehen.',
      publishedAt: '2024-02-01T10:00:00.000Z',
      updatedAt: '2024-02-01T10:00:00.000Z'
    },
    {
      id: 'post-genetics',
      title: 'Genetik-Rechner jetzt verfügbar',
      slug: 'genetik-rechner-verfuegbar',
      excerpt: 'Berechne potenzielle Nachzuchten für Bartagamen und Hakennattern direkt im Browser.',
      content:
        'Der neue Genetik-Bereich unterstützt aktuell *Pogona vitticeps* und *Heterodon nasicus*. Wähle Gene, kombiniere Eltern und erhalte Wahrscheinlichkeiten für sichtbare Merkmale. Weitere Arten können über das Backend ergänzt werden.',
      publishedAt: '2024-03-05T18:45:00.000Z',
      updatedAt: '2024-03-05T18:45:00.000Z'
    }
  ],
  pages: [
    {
      id: 'page-about',
      title: 'Über uns',
      slug: 'about',
      content:
        'Feroxz konzentriert sich auf die Zucht gesunder Reptilienlinien mit nachvollziehbarer Genetik. Unser Team teilt sein Wissen durch Workshops, Artikel und persönliche Beratung.'
    },
    {
      id: 'page-care',
      title: 'Pflegeleitfaden',
      slug: 'pflegeleitfaden',
      content:
        'Ein artgerechtes Terrarium beginnt mit ausreichender Beleuchtung, Temperaturzonen und hochwertiger Ernährung. Unsere Pflegetipps werden regelmäßig aktualisiert und basieren auf jahrelanger Erfahrung in der Terraristik.'
    }
  ],
  galleryItems: [
    {
      id: 'gallery-red-sandfire',
      title: 'Red Sandfire',
      description: 'Satte Rotfärbung kombiniert mit klarem Rückenmuster.',
      imageUrl: 'https://images.unsplash.com/photo-1581888227599-779811939961?auto=format&fit=crop&w=800&q=80',
      createdAt: '2024-01-20T14:00:00.000Z'
    },
    {
      id: 'gallery-snow-het',
      title: 'Snow Het Albino',
      description: 'Jungtier mit sichtbarer Hypo-Zeichnung und Albino-Trägerstatus.',
      imageUrl: 'https://images.unsplash.com/photo-1618826411640-7af7403d7561?auto=format&fit=crop&w=800&q=80',
      createdAt: '2024-02-12T09:30:00.000Z'
    },
    {
      id: 'gallery-hognose',
      title: 'Heterodon nasicus – Anaconda',
      description: 'Co-dominante Linie mit typischer Zeichnung entlang des Rückens.',
      imageUrl: 'https://images.unsplash.com/photo-1529470859839-9016f6ab3c1c?auto=format&fit=crop&w=800&q=80',
      createdAt: '2024-02-25T16:10:00.000Z'
    }
  ],
  species: [
    {
      id: 'species-pogona',
      slug: 'pogona-vitticeps',
      latinName: 'Pogona vitticeps',
      commonName: 'Bartagame',
      description:
        'Die Bartagame zählt zu den beliebtesten Terrarientieren. Dank zahlreicher Morphen lässt sich eine Vielzahl an Farbschlägen züchten. Ein warmer Sonnenplatz, UVB-Licht sowie abwechslungsreiche Ernährung sind essenziell.',
      habitat:
        'Trockene Busch- und Steppengebiete im Osten Australiens. Tagsüber aktiv und sonnenliebend.',
      careNotes:
        'Temperaturtagsbereich 28–32 °C mit lokalem Hotspot bis 42 °C, Nachtabsenkung auf 20–22 °C. UVB-Versorgung, abwechslungsreiche Greens & Insekten.',
      genes: [
        {
          id: 'gene-pogona-albino',
          name: 'Albino',
          inheritance: 'recessive',
          description: 'Rezessive Mutation ohne Melanin. Tiere zeigen eine helle, gelblich-rosa Grundfärbung mit roten Augen.',
          visuals: {
            homozygousDominant: 'Normal',
            heterozygous: 'Het Albino',
            homozygousRecessive: 'Albino'
          }
        },
        {
          id: 'gene-pogona-hypo',
          name: 'Hypomelanistic',
          inheritance: 'recessive',
          description: 'Reduzierter Melaninanteil sorgt für pastellfarbene Tiere mit klaren Nägeln.',
          visuals: {
            homozygousDominant: 'Normal',
            heterozygous: 'Het Hypo',
            homozygousRecessive: 'Hypo'
          }
        },
        {
          id: 'gene-pogona-translucent',
          name: 'Translucent',
          inheritance: 'co-dominant',
          description: 'Teiltransparentes Schuppenkleid, dunkle Augen und verstärkte Blautöne im Bauchbereich.',
          visuals: {
            homozygousDominant: 'Super Translucent',
            heterozygous: 'Translucent',
            homozygousRecessive: 'Normal'
          }
        }
      ]
    },
    {
      id: 'species-heterodon',
      slug: 'heterodon-nasicus',
      latinName: 'Heterodon nasicus',
      commonName: 'Westliche Hakennatter',
      description:
        'Bekannt für ihre upturned Schnauze und ein breites Spektrum an Farb- und Zeichnungsmutationen. Robuste Art, die trockene Grasländer bevorzugt.',
      habitat: 'Trockene Prärien Nordamerikas mit sandigen Böden für die charakteristische Grabaktivität.',
      careNotes:
        'Tagsüber 26–29 °C, Sonnenplatz bis 34 °C, Nachtabsenkung auf 22 °C. Substrat zum Eingraben sowie abwechslungsreiche Nagetierkost.',
      genes: [
        {
          id: 'gene-heterodon-albino',
          name: 'Albino',
          inheritance: 'recessive',
          description: 'Rezessive Mutation mit pink-gelber Zeichnung und roten Augen.',
          visuals: {
            homozygousDominant: 'Normal',
            heterozygous: 'Het Albino',
            homozygousRecessive: 'Albino'
          }
        },
        {
          id: 'gene-heterodon-anaconda',
          name: 'Anaconda',
          inheritance: 'co-dominant',
          description: 'Co-dominante Mutation mit reduzierter Fleckung. Super-Form („Superconda“) ist nahezu zeichnungslos.',
          visuals: {
            homozygousDominant: 'Superconda',
            heterozygous: 'Anaconda',
            homozygousRecessive: 'Normal'
          }
        },
        {
          id: 'gene-heterodon-axanthic',
          name: 'Axanthic',
          inheritance: 'recessive',
          description: 'Fehlende gelbe Pigmente führen zu einem kühlen, kontrastreichen Erscheinungsbild.',
          visuals: {
            homozygousDominant: 'Normal',
            heterozygous: 'Het Axanthic',
            homozygousRecessive: 'Axanthic'
          }
        }
      ]
    }
  ]
};

function cloneDefaultData() {
  return JSON.parse(JSON.stringify(defaultData));
}

function slugify(value) {
  return value
    .toString()
    .normalize('NFD')
    .replace(/[^\w\s-]/g, '')
    .replace(/[\u0300-\u036f]/g, '')
    .trim()
    .toLowerCase()
    .replace(/[-\s]+/g, '-') || 'eintrag';
}

const DataContext = createContext(null);

export function DataProvider({ children }) {
  const [data, setData] = useState(() => {
    if (typeof window === 'undefined') {
      return cloneDefaultData();
    }

    const stored = window.localStorage.getItem(STORAGE_KEY);

    if (!stored) {
      return cloneDefaultData();
    }

    try {
      const parsed = JSON.parse(stored);
      if (parsed.version === VERSION) {
        return parsed;
      }
    } catch (error) {
      console.warn('Konnte gespeicherte Daten nicht laden, starte mit Standardwerten.', error);
    }

    return cloneDefaultData();
  });

  useEffect(() => {
    if (typeof window === 'undefined') {
      return;
    }

    window.localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
  }, [data]);

  const resetData = useCallback(() => {
    setData(cloneDefaultData());
  }, []);

  const createPost = useCallback((payload) => {
    const now = new Date().toISOString();
    setData((prev) => ({
      ...prev,
      posts: [
        ...prev.posts,
        {
          id: createId('post'),
          title: payload.title,
          slug: payload.slug ? slugify(payload.slug) : slugify(payload.title),
          excerpt: payload.excerpt ?? '',
          content: payload.content ?? '',
          publishedAt: payload.publishedAt ?? now,
          updatedAt: now
        }
      ]
    }));
  }, []);

  const updatePost = useCallback((id, updates) => {
    const now = new Date().toISOString();
    setData((prev) => ({
      ...prev,
      posts: prev.posts.map((post) =>
        post.id === id
          ? {
              ...post,
              ...updates,
              slug: updates.slug ? slugify(updates.slug) : updates.title ? slugify(updates.title) : post.slug,
              updatedAt: now
            }
          : post
      )
    }));
  }, []);

  const deletePost = useCallback((id) => {
    setData((prev) => ({
      ...prev,
      posts: prev.posts.filter((post) => post.id !== id)
    }));
  }, []);

  const createPage = useCallback((payload) => {
    setData((prev) => ({
      ...prev,
      pages: [
        ...prev.pages,
        {
          id: createId('page'),
          title: payload.title,
          slug: payload.slug ? slugify(payload.slug) : slugify(payload.title),
          content: payload.content ?? ''
        }
      ]
    }));
  }, []);

  const updatePage = useCallback((id, updates) => {
    setData((prev) => ({
      ...prev,
      pages: prev.pages.map((page) =>
        page.id === id
          ? {
              ...page,
              ...updates,
              slug: updates.slug ? slugify(updates.slug) : updates.title ? slugify(updates.title) : page.slug
            }
          : page
      )
    }));
  }, []);

  const deletePage = useCallback((id) => {
    setData((prev) => ({
      ...prev,
      pages: prev.pages.filter((page) => page.id !== id)
    }));
  }, []);

  const createGalleryItem = useCallback((payload) => {
    const now = new Date().toISOString();
    setData((prev) => ({
      ...prev,
      galleryItems: [
        ...prev.galleryItems,
        {
          id: createId('gallery'),
          title: payload.title,
          description: payload.description ?? '',
          imageUrl: payload.imageUrl ?? '',
          createdAt: payload.createdAt ?? now
        }
      ]
    }));
  }, []);

  const updateGalleryItem = useCallback((id, updates) => {
    setData((prev) => ({
      ...prev,
      galleryItems: prev.galleryItems.map((item) =>
        item.id === id
          ? {
              ...item,
              ...updates
            }
          : item
      )
    }));
  }, []);

  const deleteGalleryItem = useCallback((id) => {
    setData((prev) => ({
      ...prev,
      galleryItems: prev.galleryItems.filter((item) => item.id !== id)
    }));
  }, []);

  const updateSpecies = useCallback((slug, updates) => {
    setData((prev) => ({
      ...prev,
      species: prev.species.map((entry) =>
        entry.slug === slug
          ? {
              ...entry,
              ...updates
            }
          : entry
      )
    }));
  }, []);

  const createGene = useCallback((speciesSlug, gene) => {
    const now = new Date().toISOString();
    setData((prev) => ({
      ...prev,
      species: prev.species.map((entry) => {
        if (entry.slug !== speciesSlug) {
          return entry;
        }

        return {
          ...entry,
          genes: [
            ...entry.genes,
            {
              id: createId('gene'),
              name: gene.name,
              inheritance: gene.inheritance,
              description: gene.description ?? '',
              visuals: {
                homozygousDominant: gene.visuals?.homozygousDominant ?? 'Normal',
                heterozygous: gene.visuals?.heterozygous ?? gene.name,
                homozygousRecessive: gene.visuals?.homozygousRecessive ?? `Super ${gene.name}`
              },
              createdAt: now,
              updatedAt: now
            }
          ]
        };
      })
    }));
  }, []);

  const updateGene = useCallback((speciesSlug, geneId, updates) => {
    const now = new Date().toISOString();
    setData((prev) => ({
      ...prev,
      species: prev.species.map((entry) => {
        if (entry.slug !== speciesSlug) {
          return entry;
        }

        return {
          ...entry,
          genes: entry.genes.map((gene) =>
            gene.id === geneId
              ? {
                  ...gene,
                  ...updates,
                  visuals: {
                    homozygousDominant:
                      updates.visuals?.homozygousDominant ?? gene.visuals.homozygousDominant,
                    heterozygous: updates.visuals?.heterozygous ?? gene.visuals.heterozygous,
                    homozygousRecessive:
                      updates.visuals?.homozygousRecessive ?? gene.visuals.homozygousRecessive
                  },
                  updatedAt: now
                }
              : gene
          )
        };
      })
    }));
  }, []);

  const deleteGene = useCallback((speciesSlug, geneId) => {
    setData((prev) => ({
      ...prev,
      species: prev.species.map((entry) =>
        entry.slug === speciesSlug
          ? {
              ...entry,
              genes: entry.genes.filter((gene) => gene.id !== geneId)
            }
          : entry
      )
    }));
  }, []);

  const value = useMemo(
    () => ({
      posts: data.posts,
      pages: data.pages,
      galleryItems: data.galleryItems,
      species: data.species,
      createPost,
      updatePost,
      deletePost,
      createPage,
      updatePage,
      deletePage,
      createGalleryItem,
      updateGalleryItem,
      deleteGalleryItem,
      updateSpecies,
      createGene,
      updateGene,
      deleteGene,
      resetData
    }),
    [
      data,
      createPost,
      updatePost,
      deletePost,
      createPage,
      updatePage,
      deletePage,
      createGalleryItem,
      updateGalleryItem,
      deleteGalleryItem,
      updateSpecies,
      createGene,
      updateGene,
      deleteGene,
      resetData
    ]
  );

  return <DataContext.Provider value={value}>{children}</DataContext.Provider>;
}

export function useData() {
  const context = useContext(DataContext);

  if (!context) {
    throw new Error('useData muss innerhalb des DataProvider verwendet werden');
  }

  return context;
}

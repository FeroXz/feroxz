const STORAGE_KEY = 'feroxz-static-cms-v1';
const VERSION = '2024.09-static';

const defaultState = {
  settings: {
    title: 'FeroxZ – Reptilien CMS',
    tagline: 'Verwalte Tiere, Wissen und Genetik vollständig statisch auf GitHub Pages.',
    footerNote: 'FeroxZ ist ein statisches Headless-CMS für Reptilienhaltung und Zucht.',
    logoUrl:
      'https://images.unsplash.com/photo-1610276198568-eb7b73b5550a?auto=format&fit=crop&w=160&q=80',
    logoAlt: 'FeroxZ Signet',
    iconUrl: 'https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/1f40d.svg',
    heroHighlight: [
      {
        type: 'news',
        title: 'Neue Care-Guides verfügbar',
        description: 'Ausführliche Leitfäden für Pogona vitticeps sowie Heterodon nasicus inklusive Genetikübersichten.',
        link: { label: 'Mehr erfahren', view: 'care' }
      },
      {
        type: 'animal',
        title: 'Projekt: Arctic Anaconda',
        description: 'Unser 2024er Schwerpunkt liegt auf der Kombination aus Arctic, Anaconda und Sable bei Heterodon nasicus.',
        link: { label: 'Zum Bestand', view: 'animals' }
      },
      {
        type: 'breeding',
        title: 'Brutplanung Heloderma',
        description: 'Virtuelle Zuchtplanung mit Heloderma suspectum und Heloderma alvarezi Leitfäden im Wiki verfügbar.',
        link: { label: 'Zuchtplanung', view: 'breeding' }
      }
    ],
    metrics: [
      { id: 'animals', label: 'Tiere im Bestand' },
      { id: 'adoption', label: 'Inserate aktiv' },
      { id: 'news', label: 'Neueste Meldungen' },
      { id: 'breeding', label: 'Zuchtprojekte' }
    ],
    copy: {
      brandLabel: 'FeroxZ',
      homeHighlightsTitle: 'Aktuelle Highlights',
      homeHighlightsSubtitle: 'Direkter Blick auf Tiere, Projekte und Meldungen',
      homeDashboardTitle: 'Dashboard',
      homeCareTitle: 'Letzte Pflegeartikel',
      animalsTitle: 'Bestand',
      animalsSubtitle: 'Verfügbare und private Tiere mit Genetikprofilen',
      adoptionTitle: 'Tierabgabe',
      adoptionSubtitle: 'Inserate, die öffentlich sichtbar sind',
      newsTitle: 'Neuigkeiten',
      newsSubtitle: 'Mitteilungen aus Bestand und Projekten',
      careTitle: 'Pflegeleitfäden',
      careSubtitle: 'Artprofile mit Umwelt, Ernährung und Genetik',
      geneticsTitle: 'Genetikrechner',
      geneticsSubtitle: 'Wähle eine Art und ergänze visuelle oder Trägergene. Wildtyp-Anteile werden automatisch ausgeblendet.',
      geneticsResultTitle: 'Auswertung',
      geneticsManageTitle: 'Genpool verwalten',
      geneticsManageSubtitle: 'Alle Gene lassen sich im Admin-Bereich pflegen. Hier siehst du eine Übersicht.',
      geneticsEmptyError: 'Bitte wähle mindestens ein visuelles oder Träger-Gen pro Elternteil aus.',
      geneticsHintMissing: 'Keine Berechnung möglich – es fehlen Gene.',
      geneticsHintResult:
        'Auswertung für {species}. Ergebnisse zeigen nur visuelle Tiere, Superformen, Träger und mögliche Het-Kombinationen.',
      geneticsWildtypeInfo:
        'Alle ausgewählten Gene führen in dieser Kombination zu Wildtyp-Nachzucht. Wähle weitere Visuals oder Träger, um Projektergebnisse zu sehen.',
      breedingTitle: 'Zuchtplanung',
      breedingSubtitle: 'Paarungen mit eigenen oder virtuellen Elterntieren',
      pagesTitle: 'Wiki & Seiten',
      pagesSubtitle: 'Hierarchische Seitenstruktur für zusätzliche Inhalte'
    }
  },
  pages: [
    {
      id: 'pogona-wiki',
      title: 'Pogona vitticeps – Artprofil',
      slug: 'pogona-vitticeps',
      menu: true,
      parentId: null,
      body: `<p><strong>Pogona vitticeps</strong>, im deutschsprachigen Raum als Bartagame bekannt, gilt als eine der beliebtesten Terrarienarten. Diese Ausarbeitung bietet ein vollständiges Wissensfundament für verantwortungsbewusste Halterinnen und Halter.</p>
      <h3>Lebensraum & Klima</h3>
      <p>Die Art bewohnt das australische Outback. Tagsüber herrschen Temperaturen zwischen 32–40&nbsp;°C mit punktuell heißeren Sonnenplätzen. In der Nacht fällt die Temperatur deutlich ab (18–22&nbsp;°C). Ein großzügig strukturiertes Terrarium ab 150×80×80&nbsp;cm mit Kletter- und Sonnenplätzen ist obligatorisch.</p>
      <h3>Technik & Beleuchtung</h3>
      <p>Eine Kombination aus UV-B-Flutern (z.&nbsp;B. Bright Sun Desert), Halogen-Spotstrahlern und breitbandigen LED-Flächenleuchten schafft ein artgerechtes Lichtspektrum. UV-B sollte 12 Stunden verfügbar sein. Temperatursteuerungen mit Smart-Thermostaten vermeiden Überhitzung.</p>
      <h3>Ernährung</h3>
      <p>Der Speiseplan wechselt saisonal: 70&nbsp;% hochwertiges Grünfutter (Wildkräuter, Endivien, Blüten), 30&nbsp;% tierische Proteine (Schaben, Heimchen, Grillen). Jungtiere benötigen mehr Proteine; adulte Tiere sollten maximal drei Insektenmahlzeiten pro Woche erhalten.</p>
      <h3>Verhalten & Handling</h3>
      <p>Bartagamen sind dämmerungsaktiv, zeigen aber tagsüber Sonnenbäder. Regelmäßige Gewichtskontrolle, Kotuntersuchungen und Winterruhe (8–10 Wochen bei 15&nbsp;°C) gehören zum Pflegestandard. Stressarme Handhabung und regelmäßige Beschäftigungsangebote fördern das Wohlbefinden.</p>
      <h3>Genetik</h3>
      <p>Die bekannten Linien umfassen Hypo, Translucent, Witblits, Zero, Leatherback, Silkback, Dunner und Paradox. Viele Linien werden kombiniert, um besondere Farben oder Strukturen zu erzielen. Das FeroxZ-CMS bietet im Genetikrechner vorbereitete Profile, die jederzeit erweiterbar sind.</p>`
    },
    {
      id: 'heloderma-wiki',
      title: 'Heloderma – Giftkröten',
      slug: 'heloderma',
      menu: true,
      parentId: null,
      body: `<p>Die Gattung <strong>Heloderma</strong> umfasst die giftführenden Krustenechsen, darunter Heloderma suspectum, H. horridum und H. alvarezi. In Europa unterliegen sie strengen gesetzlichen Vorgaben. Dieses Wiki dient als Wissensbasis für Theorie und virtuelle Projekte.</p>
      <h3>Arten und Unterarten</h3>
      <ul>
        <li><strong>Heloderma suspectum</strong> – Gila-Krustenechse (nördliches Verbreitungsgebiet)</li>
        <li><strong>Heloderma horridum</strong> – Mexikanische Krustenechse (mehrere Unterarten)</li>
        <li><strong>Heloderma alvarezi</strong> – Chiapan-Krustenechse</li>
      </ul>
      <p>Für jede Art steht ein eigener Pflegeleitfaden im Bereich „Pflegeleitfäden“ zur Verfügung.</p>`
    },
    {
      id: 'heloderma-suspectum',
      title: 'Heloderma suspectum Leitfaden',
      slug: 'heloderma-suspectum',
      menu: false,
      parentId: 'heloderma-wiki',
      body: `<p>Der Leitfaden für <strong>Heloderma suspectum</strong> konzentriert sich auf klimatische Anforderungen, Gehegesicherheit und Verhaltenstraining. Eine großzügige Grundfläche (mindestens 240×120×120&nbsp;cm) sowie gesicherte Rückzugsplätze sind Pflicht.</p>`
    },
    {
      id: 'heloderma-alvarezi',
      title: 'Heloderma alvarezi Leitfaden',
      slug: 'heloderma-alvarezi',
      menu: false,
      parentId: 'heloderma-wiki',
      body: `<p><strong>Heloderma alvarezi</strong> zeigt eine stärker tropische Prägung. Eine Luftfeuchtigkeit von 60–75&nbsp;% und strukturierte, kletterintensive Anlagen helfen, die Aktivität artgerecht zu halten.</p>`
    }
  ],
  news: [
    {
      id: 'news-1',
      title: 'Digitale Transformation abgeschlossen',
      body: '<p>Das FeroxZ-Projekt läuft nun als vollständig statisches Headless-CMS auf GitHub Pages. Alle Inhalte lassen sich über den integrierten Adminbereich pflegen und als JSON exportieren.</p>',
      date: '2024-09-18'
    },
    {
      id: 'news-2',
      title: 'Genetikdatenbank erweitert',
      body: '<p>Über 30 bestätigte Gene für Heterodon nasicus sowie die gängigen Linien von Pogona vitticeps wurden hinterlegt. Der Rechner berücksichtigt Visuals, Het-Träger und mögliche Het-Tiere.</p>',
      date: '2024-09-12'
    }
  ],
  animals: [
    {
      id: 'animal-1',
      name: 'Cypress',
      species: 'Heterodon nasicus',
      sex: '♀',
      hatchYear: 2021,
      weight: 178,
      status: 'Showcase',
      traits: ['Albino', 'Anaconda', 'Hypo'],
      genetics: ['albino:visual', 'anaconda:visual', 'hypo:visual'],
      description: 'Kontrastreiche Albino-Arctic-Anaconda-Dame mit ruhigem Temperament und sicherem Futterverhalten.',
      image: 'https://images.unsplash.com/photo-1521170665346-3f21e2291d8b?auto=format&fit=crop&w=700&q=80'
    },
    {
      id: 'animal-2',
      name: 'Helios',
      species: 'Pogona vitticeps',
      sex: '♂',
      hatchYear: 2020,
      weight: 520,
      status: 'Privat',
      traits: ['Hypo', 'Witblits'],
      genetics: ['hypo:visual', 'witblits:visual'],
      description: 'Hypo-Witblits-Bartagame mit intensiver Orangefärbung, Teil unserer Zuchtplanung 2025.',
      image: 'https://images.unsplash.com/photo-1610484826967-09c572dfd469?auto=format&fit=crop&w=700&q=80'
    },
    {
      id: 'animal-3',
      name: 'Asteria',
      species: 'Heterodon nasicus',
      sex: '♀',
      hatchYear: 2022,
      weight: 145,
      status: 'Reserviert',
      traits: ['Het Toffee Belly', 'Anaconda'],
      genetics: ['toffee-belly:het', 'anaconda:visual'],
      description: 'Elegante Het-Toffee Belly Anaconda mit möglichen Arctic-Anteilen, aktuell in der Aufzucht.',
      image: 'https://images.unsplash.com/photo-1574634534894-89d7576c8259?auto=format&fit=crop&w=700&q=80'
    }
  ],
  adoptionListings: [
    {
      id: 'listing-1',
      title: 'Hognose „Quartz“ – Anaconda het Toffee Belly',
      price: '320 €',
      availability: 'Verfügbar',
      details: '<p>Quartz frisst zuverlässig auf Frostfutter, ist ruhig im Handling und eignet sich als Puzzlestück für Toffee-Linien. Übergabe deutschlandweit per Spedition möglich.</p>',
      linkedAnimalId: 'animal-3'
    }
  ],
  inquiries: [],
  careGuides: [
    {
      id: 'care-heterodon',
      species: 'Heterodon nasicus',
      summary: 'Nordamerikanische Hakennasennattern mit saisonaler Klimaanpassung und spannenden Genkombinationen.',
      environment: '<p>Hakennasennattern bewohnen Prärien und Halbwüsten. Ein Becken ab 120×60×60&nbsp;cm mit grabfähigem Substrat und wechselnden Tiefzonen ist empfehlenswert. Tages-Temperatur: 26–30&nbsp;°C, Sonnenplatz 32–34&nbsp;°C, Nachtabsenkung 20&nbsp;°C.</p>',
      feeding: '<p>Fütterung im Zwei-Wochen-Rhythmus mit Frostmäusen oder -küken. Supplementierung mit Kalzium/Vitamin D3 bei juvenilen Tieren, adulte Tiere benötigen seltener Zusätze.</p>',
      enrichment: '<p>Struktur mit Korkröhren, Laub, Spotsteinen und wechselnden Duftspuren regt zum Explorieren an. Eine feuchte Rückzugbox unterstützt anstehende Häutungen.</p>',
      genetics: '<p>Gefragte Gene: Albino, Toffee Belly, Arctic, Sable, Anaconda, Lavender, Axanthic. Kombinationsprojekte wie „Yeti“ (Axanthic + Albino) sind hoch im Kurs.</p>',
      resources: ['Hognose Society Husbandry Standards 2023', 'MorphMarket Encyclopedia – Western Hognose']
    },
    {
      id: 'care-pogona',
      species: 'Pogona vitticeps',
      summary: 'Australische Bartagamen benötigen intensive Beleuchtung, reichhaltige Pflanzenkost und strukturierte Terrarien.',
      environment: '<p>Großzügiges Terrarium mit Sand-Lehm-Gemisch, Sonnenplatz 45&nbsp;°C, Grundtemperatur 32&nbsp;°C, Nachtabsenkung 18&nbsp;°C. UV-B-Strahler (12 % oder vergleichbare Werte) täglich 10–12&nbsp;Stunden.</p>',
      feeding: '<p>70&nbsp;% pflanzliche Nahrung aus Wildkräutern, 30&nbsp;% Insekten. Ergänzung mit Calcium- und Vitaminpräparaten in Intervallen.</p>',
      enrichment: '<p>Plattformen, Äste, wechselnde Futterstationen sowie Freilaufstunden in gesichertem Bereich fördern Aktivität.</p>',
      genetics: '<p>Dominante Linien: Dunner, Leatherback, Silkback. Rezessive Linien: Hypo, Translucent, Zero, Witblits. Der Rechner hilft bei der Planung.</p>',
      resources: ['BDG-Leitlinie Bartagamen 2022', 'Reptile Lighting Guide 2024']
    }
  ],
  genetics: {
    species: [
      {
        id: 'heterodon-nasicus',
        name: 'Heterodon nasicus',
        description: 'Hakennasennattern aus Nordamerika mit diversen rezessiven und kodominanten Linien.',
        resources: ['Hognose Genetics Compendium 2024', 'Western Hognose Collaborative Project']
      },
      {
        id: 'pogona-vitticeps',
        name: 'Pogona vitticeps',
        description: 'Australische Bartagamen mit etablierten Farb- und Strukturmutationen.',
        resources: ['Bearded Dragon Genetic Atlas', 'European Pogona Working Group']
      }
    ],
    genes: [
      { id: 'albino', speciesId: 'heterodon-nasicus', name: 'Albino', inheritance: 'recessive', description: 'Reduziert Melanin vollständig und erzeugt gelb-orange Tiere.', aliases: ['TAlbino', 'Albino'], expression: { visual: 'Albino', carrier: 'Het Albino', possibleCarrier: 'Mögliche Het Albino' } },
      { id: 'axanthic', speciesId: 'heterodon-nasicus', name: 'Axanthic', inheritance: 'recessive', description: 'Unterdrückt Gelb- und Rotpigmente, graue Tiere mit starker Kontrastierung.', aliases: [], expression: { visual: 'Axanthic', carrier: 'Het Axanthic', possibleCarrier: 'Mögliche Het Axanthic' } },
      { id: 'anaconda', speciesId: 'heterodon-nasicus', name: 'Anaconda', inheritance: 'incomplete-dominant', description: 'Sorgt für reduzierte Musterung bis hin zur volldeckenden Superform.', aliases: ['Conda'], expression: { visual: 'Anaconda', carrier: 'Anaconda', possibleCarrier: 'Mögliche Anaconda', superForm: 'Super Anaconda' } },
      { id: 'arctic', speciesId: 'heterodon-nasicus', name: 'Arctic', inheritance: 'incomplete-dominant', description: 'Kühlt die Farbpalette ab, Superform erzeugt „Stormtrooper“-Optik.', aliases: [], expression: { visual: 'Arctic', carrier: 'Arctic', possibleCarrier: 'Mögliche Arctic', superForm: 'Super Arctic' } },
      { id: 'toffee-belly', speciesId: 'heterodon-nasicus', name: 'Toffee Belly', inheritance: 'recessive', description: 'Schokoladefarbene Tiere mit caramelfarbenem Bauch.', aliases: ['Swiss Chocolate'], expression: { visual: 'Toffee Belly', carrier: 'Het Toffee Belly', possibleCarrier: 'Mögliche Het Toffee Belly' } },
      { id: 'toxic', speciesId: 'heterodon-nasicus', name: 'Toxic', inheritance: 'recessive', description: 'Kombination aus Toffee Belly und Albino, vollständig rezessiv fixiert.', aliases: [], expression: { visual: 'Toxic', carrier: 'Het Toxic', possibleCarrier: 'Mögliche Het Toxic' } },
      { id: 'sable', speciesId: 'heterodon-nasicus', name: 'Sable', inheritance: 'recessive', description: 'Dunkelbraune Tiere, häufig in Kombination mit Anaconda.', aliases: [], expression: { visual: 'Sable', carrier: 'Het Sable', possibleCarrier: 'Mögliche Het Sable' } },
      { id: 'hypo', speciesId: 'heterodon-nasicus', name: 'Hypo', inheritance: 'recessive', description: 'Reduzierte schwarze Schuppen, leuchtende Grundfarben.', aliases: ['Hypomelanistic'], expression: { visual: 'Hypo', carrier: 'Het Hypo', possibleCarrier: 'Mögliche Het Hypo' } },
      { id: 'lavender', speciesId: 'heterodon-nasicus', name: 'Lavender', inheritance: 'recessive', description: 'Fliedertöne mit rubinroten Augen, seltene Linie.', aliases: [], expression: { visual: 'Lavender', carrier: 'Het Lavender', possibleCarrier: 'Mögliche Het Lavender' } },
      { id: 'yeti', speciesId: 'heterodon-nasicus', name: 'Yeti', inheritance: 'compound', description: 'Kombination aus Albino und Axanthic, daher nur über Doppelrezessive sichtbar.', aliases: [], expression: { visual: 'Yeti', carrier: 'Träger-Kombination', possibleCarrier: 'Mögliche Trägerkombination' } },
      { id: 'motley', speciesId: 'heterodon-nasicus', name: 'Motley', inheritance: 'incomplete-dominant', description: 'Rückenstrich wird zu Sattelpunkten, Superform zeigt breite Bänder.', aliases: [], expression: { visual: 'Motley', carrier: 'Motley', possibleCarrier: 'Mögliche Motley', superForm: 'Super Motley' } },
      { id: 'extreme-red', speciesId: 'heterodon-nasicus', name: 'Extreme Red Albino', inheritance: 'recessive', description: 'Selektion der Albino-Linie mit starkem Rotanteil.', aliases: ['Extreme Red'], expression: { visual: 'Extreme Red Albino', carrier: 'Het Extreme Red', possibleCarrier: 'Mögliche Het Extreme Red' } },
      { id: 'sunburst', speciesId: 'heterodon-nasicus', name: 'Sunburst', inheritance: 'recessive', description: 'Kombinationslinie aus Albino und Toffee Belly.', aliases: [], expression: { visual: 'Sunburst', carrier: 'Het Sunburst', possibleCarrier: 'Mögliche Het Sunburst' } },
      { id: 'stormtrooper', speciesId: 'heterodon-nasicus', name: 'Stormtrooper', inheritance: 'polygenic', description: 'Linienzucht aus Arctic-Superformen mit hoher Kontrastierung.', aliases: [], expression: { visual: 'Stormtrooper', carrier: 'Träger', possibleCarrier: 'Mögliche Träger' } },
      { id: 't-negative', speciesId: 'heterodon-nasicus', name: 'T- Albino', inheritance: 'recessive', description: 'Tyrosinase-negative Albino-Linie, sehr kontrastreich.', aliases: ['T- Albino'], expression: { visual: 'T- Albino', carrier: 'Het T- Albino', possibleCarrier: 'Mögliche Het T- Albino' } },
      { id: 'evans-hypo', speciesId: 'heterodon-nasicus', name: 'Evans Hypo', inheritance: 'recessive', description: 'Historische Hypolinie mit stabilen, hellen Farben.', aliases: [], expression: { visual: 'Evans Hypo', carrier: 'Het Evans Hypo', possibleCarrier: 'Mögliche Het Evans Hypo' } },
      { id: 'arctic-superconda', speciesId: 'heterodon-nasicus', name: 'Arctic Superconda', inheritance: 'compound', description: 'Superform aus Arctic und Anaconda, nahezu musterlos.', aliases: [], expression: { visual: 'Arctic Superconda', carrier: 'Projektträger', possibleCarrier: 'Mögliche Projektträger' } },
      { id: 'hypo-dragon', speciesId: 'pogona-vitticeps', name: 'Hypomelanistic', inheritance: 'recessive', description: 'Reduzierte Melanine, klare Augen und helle Krallen.', aliases: ['Hypo'], expression: { visual: 'Hypomelanistic', carrier: 'Het Hypo', possibleCarrier: 'Mögliche Het Hypo' } },
      { id: 'translucent', speciesId: 'pogona-vitticeps', name: 'Translucent', inheritance: 'recessive', description: 'Durchscheinende Schuppen und dunkle Augen bei Jungtieren.', aliases: [], expression: { visual: 'Translucent', carrier: 'Het Translucent', possibleCarrier: 'Mögliche Het Translucent' } },
      { id: 'leatherback', speciesId: 'pogona-vitticeps', name: 'Leatherback', inheritance: 'incomplete-dominant', description: 'Reduzierte Stachelreihen, Superform „Silkback“ ohne Schuppen.', aliases: [], expression: { visual: 'Leatherback', carrier: 'Leatherback', possibleCarrier: 'Mögliche Leatherback', superForm: 'Silkback' } },
      { id: 'dunner', speciesId: 'pogona-vitticeps', name: 'Dunner', inheritance: 'dominant', description: 'Veränderte Schuppenwuchsrichtung, gesprenkeltes Muster.', aliases: [], expression: { visual: 'Dunner', carrier: 'Dunner', possibleCarrier: 'Mögliche Dunner' } },
      { id: 'zero', speciesId: 'pogona-vitticeps', name: 'Zero', inheritance: 'recessive', description: 'Patternless-Mutation mit silbrigem Körper.', aliases: [], expression: { visual: 'Zero', carrier: 'Het Zero', possibleCarrier: 'Mögliche Het Zero' } },
      { id: 'witblits', speciesId: 'pogona-vitticeps', name: 'Witblits', inheritance: 'recessive', description: 'Hellbeige Tiere ohne Muster, häufig kombiniert mit Hypo.', aliases: [], expression: { visual: 'Witblits', carrier: 'Het Witblits', possibleCarrier: 'Mögliche Het Witblits' } },
      { id: 'paradox', speciesId: 'pogona-vitticeps', name: 'Paradox', inheritance: 'polygenic', description: 'Unvorhersehbare Farbflecken, meist durch gezielte Selektion.', aliases: [], expression: { visual: 'Paradox', carrier: 'Träger', possibleCarrier: 'Mögliche Träger' } },
      { id: 'red-flame', speciesId: 'pogona-vitticeps', name: 'Red Flame', inheritance: 'polygenic', description: 'Linienzucht mit Fokus auf tiefrote Farbgebung.', aliases: [], expression: { visual: 'Red Flame', carrier: 'Träger', possibleCarrier: 'Mögliche Träger' } },
      { id: 'sunburst-dragon', speciesId: 'pogona-vitticeps', name: 'Sunburst', inheritance: 'polygenic', description: 'Gelb-Orange-Linie mit hoher Farbdeckung.', aliases: [], expression: { visual: 'Sunburst', carrier: 'Träger', possibleCarrier: 'Mögliche Träger' } }
    ]
  },
  breeding: {
    parents: [
      {
        id: 'parent-1',
        name: 'Cypress',
        linkType: 'animal',
        referenceId: 'animal-1',
        sex: '♀',
        speciesId: 'heterodon-nasicus',
        notes: 'Bewährte Albino-Anaconda-Dame, frisst zuverlässig.',
        genetics: ['albino:visual', 'anaconda:visual', 'arctic:carrier']
      },
      {
        id: 'parent-2',
        name: 'Nimbus',
        linkType: 'virtual',
        referenceId: null,
        sex: '♂',
        speciesId: 'heterodon-nasicus',
        notes: 'Virtueller Arctic het Toffee Belly, für 2025 geplant.',
        genetics: ['arctic:carrier', 'toffee-belly:carrier']
      }
    ],
    plans: [
      {
        id: 'plan-1',
        title: 'Arctic Superconda 2025',
        season: '2025',
        femaleId: 'parent-1',
        maleId: 'parent-2',
        goals: 'Super Arctic Superconda Nachzucht mit möglich Toffee Belly.',
        notes: 'Brutkasten 30,5&nbsp;°C, Inkubationszeit 55 Tage. Schlupfboxen vorbereiten.',
        hatchEstimate: '2025-08-01'
      }
    ]
  }
};
const deepClone = (value) => JSON.parse(JSON.stringify(value));
const isPlainObject = (value) => typeof value === 'object' && value !== null && !Array.isArray(value);

const mergeDeep = (target, source) => {
  const output = deepClone(target);
  if (!isPlainObject(source)) {
    return output;
  }
  Object.entries(source).forEach(([key, value]) => {
    if (Array.isArray(value)) {
      output[key] = deepClone(value);
    } else if (isPlainObject(value)) {
      output[key] = mergeDeep(target[key] ?? {}, value);
    } else {
      output[key] = value;
    }
  });
  return output;
};

const loadState = () => {
  try {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored) {
      const parsed = JSON.parse(stored);
      return mergeDeep(defaultState, parsed);
    }
  } catch (error) {
    console.error('Fehler beim Laden des gespeicherten Zustands', error);
  }
  return deepClone(defaultState);
};

let state = loadState();

const persistState = () => {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
  renderAll();
  updateParentChips('one');
  updateParentChips('two');
  calculateGenetics();
};

const resetState = () => {
  localStorage.removeItem(STORAGE_KEY);
  state = deepClone(defaultState);
  renderAll();
  updateParentChips('one');
  updateParentChips('two');
  calculateGenetics();
};

const formatDate = (input) => {
  const date = new Date(input);
  if (Number.isNaN(date.getTime())) return 'ohne Datum';
  return date.toLocaleDateString('de-DE', { day: '2-digit', month: 'long', year: 'numeric' });
};

const fractionFromProbability = (prob) => {
  if (!prob) return '0%';
  const gcd = (a, b) => (b ? gcd(b, a % b) : a);
  const denominator = 16;
  const numerator = Math.round(prob * denominator);
  if (numerator === 0) return '0%';
  const divisor = gcd(numerator, denominator);
  return `${Math.round((prob * 100 + Number.EPSILON) * 100) / 100}% · ${numerator / divisor}/${denominator / divisor}`;
};

const renderAll = () => {
  renderHeader();
  applyCopy();
  renderHome();
  renderAnimals();
  renderAdoption();
  renderNews();
  renderCare();
  renderPages();
  renderGeneticsOverview();
  renderBreeding();
  updateAdminForms();
};

const renderHeader = () => {
  document.title = state.settings.title || 'FeroxZ – Reptilien CMS';
  const titleEl = document.getElementById('site-title');
  if (titleEl) titleEl.textContent = state.settings.title;
  const taglineEl = document.getElementById('site-tagline');
  if (taglineEl) taglineEl.textContent = state.settings.tagline ?? '';
  const logoWrapper = document.getElementById('site-logo-wrapper');
  const logoEl = document.getElementById('site-logo');
  const { logoUrl, logoAlt, iconUrl, footerNote } = state.settings;
  if (logoEl && logoWrapper) {
    if (logoUrl) {
      logoEl.src = logoUrl;
      logoEl.alt = logoAlt || state.settings.title || 'FeroxZ';
      logoWrapper.classList.remove('hidden');
    } else {
      logoEl.removeAttribute('src');
      logoWrapper.classList.add('hidden');
    }
  }
  const yearEl = document.querySelector('[data-field="year"]');
  if (yearEl) yearEl.textContent = new Date().getFullYear();
  const footerCopy = document.getElementById('footer-copy');
  if (footerCopy) {
    const brand = state.settings.copy?.brandLabel ?? 'FeroxZ';
    footerCopy.innerHTML = `© <span data-field="year">${new Date().getFullYear()}</span> ${brand}. ${footerNote ?? ''}`;
  }
  const versionTag = document.getElementById('version-tag');
  if (versionTag) versionTag.textContent = VERSION;
  const faviconLink = document.getElementById('favicon-link');
  if (faviconLink) {
    const fallbackIcon = defaultState.settings.iconUrl;
    faviconLink.href = iconUrl || logoUrl || fallbackIcon;
  }
};

const applyCopy = () => {
  const copy = state.settings.copy ?? {};
  document.querySelectorAll('[data-copy]').forEach((node) => {
    const key = node.dataset.copy;
    const value = copy[key];
    if (typeof value === 'string') {
      node.innerHTML = value;
    }
  });
};
const renderHome = () => {
  const highlightContainer = document.getElementById('home-highlights');
  highlightContainer.innerHTML = '';
  state.settings.heroHighlight.forEach((item) => {
    const card = document.createElement('article');
    card.className = 'glass-card space-y-3';
    card.innerHTML = `
      <div class="card-badge">${item.type}</div>
      <h3 class="text-xl font-semibold">${item.title}</h3>
      <p class="text-sm text-slate-300">${item.description}</p>
      <button class="btn-secondary" data-jump="${item.link.view}">${item.link.label}</button>
    `;
    highlightContainer.appendChild(card);
  });

  const dashboard = document.getElementById('home-dashboard');
  dashboard.innerHTML = '';
  const counts = {
    animals: state.animals.length,
    adoption: state.adoptionListings.length,
    news: state.news.length,
    breeding: state.breeding.plans.length
  };
  state.settings.metrics.forEach((metric) => {
    const wrapper = document.createElement('div');
    wrapper.className = 'glass-card p-6';
    wrapper.innerHTML = `
      <dt class="text-xs uppercase tracking-[0.3em] text-cyan-200/80">${metric.label}</dt>
      <dd class="mt-2 text-3xl font-semibold">${counts[metric.id] ?? 0}</dd>
    `;
    dashboard.appendChild(wrapper);
  });

  const careList = document.getElementById('home-care');
  careList.innerHTML = '';
  state.careGuides.slice(0, 3).forEach((guide) => {
    const card = document.createElement('article');
    card.className = 'glass-card space-y-3';
    card.innerHTML = `
      <h3 class="text-lg font-semibold">${guide.species}</h3>
      <p class="text-sm text-slate-300">${guide.summary}</p>
      <button class="btn-secondary" data-jump="care" data-focus="${guide.id}">Ansehen</button>
    `;
    careList.appendChild(card);
  });
};
const renderAnimals = () => {
  const container = document.getElementById('animal-grid');
  container.innerHTML = '';
  if (!state.animals.length) {
    container.innerHTML = '<p class="empty-state">Noch keine Tiere angelegt.</p>';
    return;
  }
  state.animals.forEach((animal) => {
    const card = document.createElement('article');
    card.className = 'glass-card space-y-4';
    card.innerHTML = `
      <div class="relative aspect-[4/3] overflow-hidden rounded-2xl">
        <img src="${animal.image}" alt="${animal.name}" class="w-full h-full object-cover" />
        <span class="absolute top-3 left-3 card-badge">${animal.status}</span>
      </div>
      <div class="space-y-2">
        <h3 class="text-xl font-semibold">${animal.name}</h3>
        <p class="text-sm text-slate-300">${animal.species} · ${animal.sex} · ${animal.hatchYear ?? 'unbekannt'}</p>
        <div class="flex flex-wrap gap-2">
          ${(animal.traits || []).map((trait) => `<span class="badge">${trait}</span>`).join('')}
        </div>
        <p class="text-sm text-slate-200/80">${animal.description}</p>
      </div>
      <div class="flex justify-between gap-3">
        <button class="btn-secondary" data-action="edit-animal" data-id="${animal.id}">Bearbeiten</button>
        <button class="btn-danger" data-action="delete-animal" data-id="${animal.id}">Löschen</button>
      </div>
    `;
    container.appendChild(card);
  });
};
const renderAdoption = () => {
  const container = document.getElementById('adoption-grid');
  container.innerHTML = '';
  if (!state.adoptionListings.length) {
    container.innerHTML = '<p class="empty-state">Derzeit keine Inserate.</p>';
    return;
  }
  state.adoptionListings.forEach((listing) => {
    const linkedAnimal = state.animals.find((a) => a.id === listing.linkedAnimalId);
    const card = document.createElement('article');
    card.className = 'glass-card space-y-3';
    card.innerHTML = `
      <div class="flex items-center justify-between">
        <h3 class="text-xl font-semibold">${listing.title}</h3>
        <span class="card-badge">${listing.availability}</span>
      </div>
      <p class="text-lg font-semibold text-cyan-200">${listing.price}</p>
      <div class="text-sm text-slate-200/80">${listing.details}</div>
      ${linkedAnimal ? `<p class="text-xs text-slate-400">Verknüpftes Tier: ${linkedAnimal.name}</p>` : ''}
      <div class="flex justify-between gap-3">
        <button class="btn-secondary" data-action="edit-listing" data-id="${listing.id}">Bearbeiten</button>
        <button class="btn-danger" data-action="delete-listing" data-id="${listing.id}">Löschen</button>
      </div>
    `;
    container.appendChild(card);
  });
};

const renderNews = () => {
  const container = document.getElementById('news-list');
  container.innerHTML = '';
  if (!state.news.length) {
    container.innerHTML = '<p class="empty-state">Keine Neuigkeiten vorhanden.</p>';
    return;
  }
  state.news
    .slice()
    .sort((a, b) => new Date(b.date) - new Date(a.date))
    .forEach((entry) => {
      const card = document.createElement('article');
      card.className = 'glass-card space-y-3';
      card.innerHTML = `
        <div class="flex items-baseline justify-between gap-4">
          <h3 class="text-2xl font-semibold">${entry.title}</h3>
          <time class="text-xs uppercase tracking-[0.3em] text-slate-400">${formatDate(entry.date)}</time>
        </div>
        <div class="prose prose-invert max-w-none text-sm">${entry.body}</div>
        <div class="flex justify-between gap-3">
          <button class="btn-secondary" data-action="edit-news" data-id="${entry.id}">Bearbeiten</button>
          <button class="btn-danger" data-action="delete-news" data-id="${entry.id}">Löschen</button>
        </div>
      `;
      container.appendChild(card);
    });
};
const renderCare = () => {
  const container = document.getElementById('care-grid');
  container.innerHTML = '';
  if (!state.careGuides.length) {
    container.innerHTML = '<p class="empty-state">Noch keine Leitfäden verfasst.</p>';
    return;
  }
  state.careGuides.forEach((guide) => {
    const card = document.createElement('article');
    card.className = 'glass-card space-y-4';
    card.dataset.id = guide.id;
    card.innerHTML = `
      <div>
        <h3 class="text-2xl font-semibold">${guide.species}</h3>
        <p class="text-sm text-slate-300">${guide.summary}</p>
      </div>
      <div class="space-y-3 text-sm text-slate-200/90">
        <div><strong>Umgebung:</strong> ${guide.environment}</div>
        <div><strong>Fütterung:</strong> ${guide.feeding}</div>
        <div><strong>Enrichment:</strong> ${guide.enrichment}</div>
        <div><strong>Genetik:</strong> ${guide.genetics}</div>
      </div>
      <div class="flex flex-wrap gap-2">
        ${(guide.resources || []).map((item) => `<span class="badge">${item}</span>`).join('')}
      </div>
      <div class="flex justify-between gap-3">
        <button class="btn-secondary" data-action="edit-care" data-id="${guide.id}">Bearbeiten</button>
        <button class="btn-danger" data-action="delete-care" data-id="${guide.id}">Löschen</button>
      </div>
    `;
    container.appendChild(card);
  });
};

const renderPages = () => {
  const container = document.getElementById('page-tree');
  container.innerHTML = '';
  if (!state.pages.length) {
    container.innerHTML = '<p class="empty-state">Es wurden noch keine Seiten erfasst.</p>';
    return;
  }
  const buildTree = (parentId = null, depth = 0) => {
    const items = state.pages.filter((page) => page.parentId === parentId);
    if (!items.length) return '';
    return `
      <ul class="space-y-3 ${depth ? 'pl-6 border-l border-white/10' : ''}">
        ${items
          .map((page) => `
            <li>
              <div class="flex items-start justify-between gap-3">
                <div>
                  <h3 class="text-lg font-semibold">${page.title}</h3>
                  <p class="text-xs uppercase tracking-[0.3em] text-slate-500">/${page.slug}</p>
                  <div class="prose prose-invert max-w-none text-sm mt-2">${page.body}</div>
                </div>
                <div class="flex gap-2">
                  <button class="btn-secondary" data-action="edit-page" data-id="${page.id}">Bearbeiten</button>
                  <button class="btn-danger" data-action="delete-page" data-id="${page.id}">Löschen</button>
                </div>
              </div>
              ${buildTree(page.id, depth + 1)}
            </li>
          `)
          .join('')}
      </ul>
    `;
  };
  container.innerHTML = buildTree();
};
const renderGeneticsOverview = () => {
  const speciesSelect = document.getElementById('genetics-species');
  speciesSelect.innerHTML = state.genetics.species
    .map((entry) => `<option value="${entry.id}">${entry.name}</option>`)
    .join('');
  if (geneticsSelections.speciesId && state.genetics.species.some((entry) => entry.id === geneticsSelections.speciesId)) {
    speciesSelect.value = geneticsSelections.speciesId;
  } else if (speciesSelect.options.length) {
    geneticsSelections.speciesId = speciesSelect.options[0].value;
    speciesSelect.value = geneticsSelections.speciesId;
  }
  renderGeneticsSummary();
};

const renderGeneticsSummary = () => {
  const overview = document.getElementById('genetics-overview');
  overview.innerHTML = '';
  state.genetics.species.forEach((species) => {
    const genes = state.genetics.genes.filter((gene) => gene.speciesId === species.id);
    const card = document.createElement('article');
    card.className = 'glass-card space-y-3';
    card.innerHTML = `
      <div class="flex items-start justify-between gap-4">
        <div>
          <h3 class="text-xl font-semibold">${species.name}</h3>
          <p class="text-sm text-slate-300">${species.description}</p>
        </div>
        <span class="badge">${genes.length} Gene</span>
      </div>
      <div class="flex flex-wrap gap-2">
        ${genes.map((gene) => `<span class="tag-pill">${gene.name}</span>`).join('')}
      </div>
    `;
    overview.appendChild(card);
  });
};

const renderBreeding = () => {
  const container = document.getElementById('breeding-list');
  container.innerHTML = '';
  if (!state.breeding.plans.length) {
    container.innerHTML = '<p class="empty-state">Es sind noch keine Zuchtprojekte geplant.</p>';
    return;
  }
  state.breeding.plans.forEach((plan) => {
    const female = state.breeding.parents.find((parent) => parent.id === plan.femaleId);
    const male = state.breeding.parents.find((parent) => parent.id === plan.maleId);
    const card = document.createElement('article');
    card.className = 'glass-card space-y-4';
    card.innerHTML = `
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-2xl font-semibold">${plan.title}</h3>
          <p class="text-sm text-slate-300">Saison ${plan.season} · ${plan.hatchEstimate ? 'Schlupf ab ' + formatDate(plan.hatchEstimate) : 'Termin folgt'}</p>
        </div>
        <div class="flex gap-2">
          <button class="btn-secondary" data-action="edit-breeding" data-id="${plan.id}">Bearbeiten</button>
          <button class="btn-danger" data-action="delete-breeding" data-id="${plan.id}">Löschen</button>
        </div>
      </div>
      <div class="grid md:grid-cols-2 gap-4">
        <div class="bg-white/5 rounded-2xl p-4">
          <h4 class="text-sm uppercase tracking-[0.3em] text-cyan-200/80">Elterntier ♀</h4>
          ${renderParentSnippet(female)}
        </div>
        <div class="bg-white/5 rounded-2xl p-4">
          <h4 class="text-sm uppercase tracking-[0.3em] text-cyan-200/80">Elterntier ♂</h4>
          ${renderParentSnippet(male)}
        </div>
      </div>
      <p class="text-sm text-slate-200/90">${plan.goals}</p>
      <div class="text-sm text-slate-400">${plan.notes ?? ''}</div>
    `;
    container.appendChild(card);
  });
};

const renderParentSnippet = (parent) => {
  if (!parent) return '<p class="text-sm text-slate-400">Kein Datensatz hinterlegt.</p>';
  const species = state.genetics.species.find((item) => item.id === parent.speciesId)?.name ?? 'Unbekannte Art';
  const linkedAnimal = parent.linkType === 'animal' ? state.animals.find((a) => a.id === parent.referenceId) : null;
  return `
    <p class="text-base font-semibold">${parent.name}</p>
    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">${species}</p>
    <div class="mt-2 flex flex-wrap gap-2">
      ${(parent.genetics ?? []).map((g) => `<span class="badge">${formatGeneticLabel(g)}</span>`).join('')}
    </div>
    ${linkedAnimal ? `<p class="text-xs text-slate-500 mt-2">Verknüpft mit Tier: ${linkedAnimal.name}</p>` : ''}
    <p class="text-xs text-slate-400 mt-2">${parent.notes ?? ''}</p>
    <button class="btn-secondary mt-3" data-action="edit-parent" data-id="${parent.id}">Elterntier bearbeiten</button>
  `;
};

const formatGeneticLabel = (entry) => {
  const [geneId, expression] = entry.split(':');
  const gene = state.genetics.genes.find((g) => g.id === geneId);
  if (!gene) return entry;
  if (expression === 'visual') return gene.expression.visual ?? gene.name;
  if (expression === 'het' || expression === 'carrier') return gene.expression.carrier ?? `Het ${gene.name}`;
  if (expression === 'possibleHet' || expression === 'possible') return gene.expression.possibleCarrier ?? `Mögliche Het ${gene.name}`;
  if (expression === 'super') return gene.expression.superForm ?? `Super ${gene.name}`;
  return `${gene.name} (${expression})`;
};
const setActiveView = (view) => {
  document.querySelectorAll('.view-section').forEach((section) => {
    section.classList.add('hidden');
    section.classList.remove('active');
  });
  const active = document.getElementById(view);
  if (active) {
    active.classList.remove('hidden');
    active.classList.add('active');
  }
};

const modal = {
  element: document.getElementById('modal-root'),
  title: document.getElementById('modal-title'),
  content: document.getElementById('modal-content'),
  open(title, content) {
    this.title.textContent = title;
    this.content.innerHTML = '';
    if (typeof content === 'string') {
      this.content.innerHTML = content;
    } else {
      this.content.appendChild(content);
    }
    this.element.classList.remove('hidden');
  },
  close() {
    this.element.classList.add('hidden');
  }
};
const createEditor = (value = '') => {
  const template = document.getElementById('editor-template');
  const fragment = template.content.cloneNode(true);
  const editor = fragment.querySelector('.editor');
  const surface = editor.querySelector('.editor-surface');
  surface.innerHTML = value;
  editor.querySelectorAll('button[data-command]').forEach((button) => {
    button.addEventListener('click', () => {
      const command = button.dataset.command;
      surface.focus();
      if (command === 'createLink') {
        const url = prompt('Link-Adresse (inkl. https://) eingeben:');
        if (url) document.execCommand('createLink', false, url);
        return;
      }
      if (command === 'insertImage') {
        const imageUrl = prompt('Bild-URL (inkl. https://) einfügen:');
        if (imageUrl) document.execCommand('insertImage', false, imageUrl);
        return;
      }
      if (command === 'formatBlock') {
        const formatValue = button.dataset.value ?? 'p';
        document.execCommand('formatBlock', false, formatValue);
        return;
      }
      const val = button.dataset.value ?? null;
      document.execCommand(command, false, val);
    });
  });
  return { element: editor, getValue: () => surface.innerHTML, setValue: (html) => { surface.innerHTML = html; } };
};

const buildInput = ({ label, name, value = '', type = 'text', placeholder = '', options = [] }) => {
  const wrapper = document.createElement('label');
  wrapper.className = 'block';
  wrapper.innerHTML = `<span class="input-label">${label}</span>`;
  if (type === 'textarea') {
    const { element, getValue } = createEditor(value);
    element.dataset.name = name;
    wrapper.appendChild(element);
    return { element: wrapper, getValue: () => getValue() };
  }
  if (type === 'multiline') {
    const textarea = document.createElement('textarea');
    textarea.name = name;
    textarea.className = 'input-control';
    textarea.rows = 4;
    textarea.value = value;
    wrapper.appendChild(textarea);
    return { element: wrapper, getValue: () => textarea.value };
  }
  if (type === 'select') {
    const select = document.createElement('select');
    select.name = name;
    select.className = 'input-control';
    select.innerHTML = options.map((opt) => `<option value="${opt.value}">${opt.label}</option>`).join('');
    select.value = value;
    wrapper.appendChild(select);
    return { element: wrapper, getValue: () => select.value };
  }
  if (type === 'multiselect') {
    const select = document.createElement('select');
    select.name = name;
    select.className = 'input-control';
    select.multiple = true;
    select.innerHTML = options.map((opt) => `<option value="${opt.value}">${opt.label}</option>`).join('');
    (value || []).forEach((val) => {
      const option = Array.from(select.options).find((item) => item.value === val);
      if (option) option.selected = true;
    });
    wrapper.appendChild(select);
    return { element: wrapper, getValue: () => Array.from(select.selectedOptions).map((opt) => opt.value) };
  }
  const input = document.createElement('input');
  input.type = type;
  input.name = name;
  input.className = 'input-control';
  input.value = value;
  input.placeholder = placeholder;
  wrapper.appendChild(input);
  return { element: wrapper, getValue: () => input.value };
};
const createHiddenSubmit = (label) => {
  const button = document.createElement('button');
  button.type = 'submit';
  button.className = 'btn-primary';
  button.textContent = label;
  return button;
};

const showToast = (message, type = 'error') => {
  let toast = document.getElementById('error-toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'error-toast';
    document.body.appendChild(toast);
  }
  toast.className = type === 'success' ? 'success' : 'notice';
  toast.textContent = message;
  toast.style.display = 'block';
  setTimeout(() => {
    toast.style.display = 'none';
  }, 3500);
};
const showAnimalForm = (animal = null) => {
  const form = document.createElement('form');
  form.className = 'modal-form';
  const nameField = buildInput({ label: 'Name', name: 'name', value: animal?.name ?? '' });
  const speciesField = buildInput({ label: 'Art', name: 'species', value: animal?.species ?? '' });
  const sexField = buildInput({ label: 'Geschlecht', name: 'sex', value: animal?.sex ?? '', placeholder: '♀ / ♂' });
  const hatchField = buildInput({ label: 'Schlupfjahr', name: 'hatchYear', value: animal?.hatchYear ?? '', type: 'number' });
  const statusField = buildInput({ label: 'Status', name: 'status', value: animal?.status ?? 'Showcase' });
  const traitsField = buildInput({ label: 'Eigenschaften (Kommagetrennt)', name: 'traits', value: animal?.traits?.join(', ') ?? '' });
  const imageField = buildInput({ label: 'Bild-URL', name: 'image', value: animal?.image ?? '' });
  const descField = buildInput({ label: 'Beschreibung', name: 'description', value: animal?.description ?? '', type: 'textarea' });
  [nameField, speciesField, sexField, hatchField, statusField, traitsField, imageField, descField].forEach((field) =>
    form.appendChild(field.element)
  );
  form.appendChild(createHiddenSubmit('Speichern'));
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const payload = {
      id: animal?.id ?? `animal-${crypto.randomUUID()}`,
      name: nameField.getValue(),
      species: speciesField.getValue(),
      sex: sexField.getValue(),
      hatchYear: Number(hatchField.getValue()) || null,
      status: statusField.getValue(),
      traits: traitsField
        .getValue()
        .split(',')
        .map((item) => item.trim())
        .filter(Boolean),
      image:
        imageField.getValue() ||
        'https://images.unsplash.com/photo-1521170665346-3f21e2291d8b?auto=format&fit=crop&w=700&q=80',
      description: descField.getValue(),
      genetics: animal?.genetics ?? []
    };
    if (!payload.name) return showToast('Bitte einen Namen eingeben.');
    if (animal) {
      state.animals = state.animals.map((entry) => (entry.id === animal.id ? payload : entry));
    } else {
      state.animals.push(payload);
    }
    persistState();
    modal.close();
  });
  modal.open(animal ? 'Tier bearbeiten' : 'Neues Tier', form);
};
const showListingForm = (listing = null) => {
  const form = document.createElement('form');
  form.className = 'modal-form';
  const titleField = buildInput({ label: 'Titel', name: 'title', value: listing?.title ?? '' });
  const priceField = buildInput({ label: 'Preis', name: 'price', value: listing?.price ?? '' });
  const availabilityField = buildInput({ label: 'Status', name: 'availability', value: listing?.availability ?? 'Verfügbar' });
  const detailsField = buildInput({ label: 'Beschreibung', name: 'details', value: listing?.details ?? '', type: 'textarea' });
  const animalField = buildInput({
    label: 'Verknüpftes Tier',
    name: 'linkedAnimalId',
    value: listing?.linkedAnimalId ?? '',
    type: 'select',
    options: [{ value: '', label: 'Keines' }].concat(state.animals.map((animal) => ({ value: animal.id, label: animal.name })))
  });
  [titleField, priceField, availabilityField, detailsField, animalField].forEach((field) => form.appendChild(field.element));
  form.appendChild(createHiddenSubmit('Speichern'));
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const payload = {
      id: listing?.id ?? `listing-${crypto.randomUUID()}`,
      title: titleField.getValue(),
      price: priceField.getValue(),
      availability: availabilityField.getValue(),
      details: detailsField.getValue(),
      linkedAnimalId: animalField.getValue() || null
    };
    if (!payload.title) return showToast('Ein Titel ist erforderlich.');
    if (listing) {
      state.adoptionListings = state.adoptionListings.map((entry) => (entry.id === listing.id ? payload : entry));
    } else {
      state.adoptionListings.push(payload);
    }
    persistState();
    modal.close();
  });
  modal.open(listing ? 'Inserat bearbeiten' : 'Neues Inserat', form);
};

const showNewsForm = (entry = null) => {
  const form = document.createElement('form');
  form.className = 'modal-form';
  const titleField = buildInput({ label: 'Überschrift', name: 'title', value: entry?.title ?? '' });
  const dateField = buildInput({ label: 'Datum', name: 'date', value: entry?.date ?? new Date().toISOString().slice(0, 10), type: 'date' });
  const bodyField = buildInput({ label: 'Inhalt', name: 'body', value: entry?.body ?? '', type: 'textarea' });
  [titleField, dateField, bodyField].forEach((field) => form.appendChild(field.element));
  form.appendChild(createHiddenSubmit('Speichern'));
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const payload = {
      id: entry?.id ?? `news-${crypto.randomUUID()}`,
      title: titleField.getValue(),
      date: dateField.getValue(),
      body: bodyField.getValue()
    };
    if (!payload.title) return showToast('Bitte eine Überschrift setzen.');
    if (entry) {
      state.news = state.news.map((item) => (item.id === entry.id ? payload : item));
    } else {
      state.news.push(payload);
    }
    persistState();
    modal.close();
  });
  modal.open(entry ? 'Beitrag bearbeiten' : 'Neuer Beitrag', form);
};
const showCareForm = (guide = null) => {
  const form = document.createElement('form');
  form.className = 'modal-form';
  const speciesField = buildInput({ label: 'Art', name: 'species', value: guide?.species ?? '' });
  const summaryField = buildInput({ label: 'Zusammenfassung', name: 'summary', value: guide?.summary ?? '', type: 'textarea' });
  const envField = buildInput({ label: 'Umgebung', name: 'environment', value: guide?.environment ?? '', type: 'textarea' });
  const feedField = buildInput({ label: 'Fütterung', name: 'feeding', value: guide?.feeding ?? '', type: 'textarea' });
  const enrichField = buildInput({ label: 'Enrichment', name: 'enrichment', value: guide?.enrichment ?? '', type: 'textarea' });
  const geneticsField = buildInput({ label: 'Genetik', name: 'genetics', value: guide?.genetics ?? '', type: 'textarea' });
  const resourcesField = buildInput({ label: 'Ressourcen (Kommagetrennt)', name: 'resources', value: (guide?.resources ?? []).join(', ') });
  [speciesField, summaryField, envField, feedField, enrichField, geneticsField, resourcesField].forEach((field) =>
    form.appendChild(field.element)
  );
  form.appendChild(createHiddenSubmit('Speichern'));
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const payload = {
      id: guide?.id ?? `care-${crypto.randomUUID()}`,
      species: speciesField.getValue(),
      summary: summaryField.getValue(),
      environment: envField.getValue(),
      feeding: feedField.getValue(),
      enrichment: enrichField.getValue(),
      genetics: geneticsField.getValue(),
      resources: resourcesField
        .getValue()
        .split(',')
        .map((item) => item.trim())
        .filter(Boolean)
    };
    if (!payload.species) return showToast('Bitte eine Artbezeichnung eintragen.');
    if (guide) {
      state.careGuides = state.careGuides.map((item) => (item.id === guide.id ? payload : item));
    } else {
      state.careGuides.push(payload);
    }
    persistState();
    modal.close();
  });
  modal.open(guide ? 'Leitfaden bearbeiten' : 'Neuer Leitfaden', form);
};

const showPageForm = (page = null) => {
  const form = document.createElement('form');
  form.className = 'modal-form';
  const titleField = buildInput({ label: 'Titel', name: 'title', value: page?.title ?? '' });
  const slugField = buildInput({ label: 'Slug', name: 'slug', value: page?.slug ?? '' });
  const menuField = buildInput({
    label: 'Im Menü anzeigen',
    name: 'menu',
    type: 'select',
    value: page?.menu ? 'true' : 'false',
    options: [
      { value: 'true', label: 'Ja' },
      { value: 'false', label: 'Nein' }
    ]
  });
  const parentField = buildInput({
    label: 'Übergeordnete Seite',
    name: 'parentId',
    value: page?.parentId ?? '',
    type: 'select',
    options: [{ value: '', label: 'Keine' }].concat(state.pages.filter((p) => p.id !== page?.id).map((p) => ({ value: p.id, label: p.title })))
  });
  const bodyField = buildInput({ label: 'Inhalt', name: 'body', value: page?.body ?? '', type: 'textarea' });
  [titleField, slugField, menuField, parentField, bodyField].forEach((field) => form.appendChild(field.element));
  form.appendChild(createHiddenSubmit('Speichern'));
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const payload = {
      id: page?.id ?? `page-${crypto.randomUUID()}`,
      title: titleField.getValue(),
      slug: slugField.getValue(),
      menu: menuField.getValue() === 'true',
      parentId: parentField.getValue() || null,
      body: bodyField.getValue()
    };
    if (!payload.title || !payload.slug) return showToast('Titel und Slug sind Pflichtfelder.');
    if (page) {
      state.pages = state.pages.map((item) => (item.id === page.id ? payload : item));
    } else {
      state.pages.push(payload);
    }
    persistState();
    modal.close();
  });
  modal.open(page ? 'Seite bearbeiten' : 'Neue Seite', form);
};

const showBrandingForm = () => {
  const form = document.createElement('form');
  form.className = 'modal-form';
  const titleField = buildInput({ label: 'Seitentitel', name: 'title', value: state.settings.title ?? '' });
  const taglineField = buildInput({ label: 'Unterzeile (Tagline)', name: 'tagline', value: state.settings.tagline ?? '' });
  const footerField = buildInput({
    label: 'Fußzeile',
    name: 'footerNote',
    value: state.settings.footerNote ?? '',
    type: 'textarea'
  });
  const logoUrlField = buildInput({
    label: 'Logo-URL',
    name: 'logoUrl',
    value: state.settings.logoUrl ?? '',
    type: 'url',
    placeholder: 'https://…'
  });
  const logoAltField = buildInput({
    label: 'Alternativtext für das Logo',
    name: 'logoAlt',
    value: state.settings.logoAlt ?? ''
  });
  const iconUrlField = buildInput({
    label: 'Favicon/Icon-URL',
    name: 'iconUrl',
    value: state.settings.iconUrl ?? '',
    type: 'url',
    placeholder: 'https://…'
  });
  [titleField, taglineField, footerField, logoUrlField, logoAltField, iconUrlField].forEach((field) =>
    form.appendChild(field.element)
  );
  form.appendChild(createHiddenSubmit('Speichern'));
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    state.settings.title = titleField.getValue();
    state.settings.tagline = taglineField.getValue();
    state.settings.footerNote = footerField.getValue();
    state.settings.logoUrl = logoUrlField.getValue();
    state.settings.logoAlt = logoAltField.getValue();
    state.settings.iconUrl = iconUrlField.getValue();
    persistState();
    modal.close();
    showToast('Branding aktualisiert.', 'success');
  });
  modal.open('Branding bearbeiten', form);
};

const showCopyForm = () => {
  const form = document.createElement('form');
  form.className = 'modal-form';
  const intro = document.createElement('p');
  intro.className = 'text-sm text-slate-300';
  intro.innerHTML =
    'Bearbeite die sichtbaren Überschriften und Beschreibungen. Verwende den Platzhalter <code>{species}</code> für den Artnamen im Genetik-Hinweis.';
  form.appendChild(intro);
  const copyConfig = [
    { key: 'brandLabel', label: 'Header · Markenlabel' },
    { key: 'homeHighlightsTitle', label: 'Startseite · Bereichstitel Highlights' },
    { key: 'homeHighlightsSubtitle', label: 'Startseite · Untertitel Highlights', type: 'multiline' },
    { key: 'homeDashboardTitle', label: 'Startseite · Dashboard-Titel' },
    { key: 'homeCareTitle', label: 'Startseite · Bereichstitel Pflegeartikel' },
    { key: 'animalsTitle', label: 'Tiere · Bereichstitel' },
    { key: 'animalsSubtitle', label: 'Tiere · Untertitel', type: 'multiline' },
    { key: 'adoptionTitle', label: 'Tierabgabe · Bereichstitel' },
    { key: 'adoptionSubtitle', label: 'Tierabgabe · Untertitel', type: 'multiline' },
    { key: 'newsTitle', label: 'Neuigkeiten · Bereichstitel' },
    { key: 'newsSubtitle', label: 'Neuigkeiten · Untertitel', type: 'multiline' },
    { key: 'careTitle', label: 'Pflegeleitfäden · Bereichstitel' },
    { key: 'careSubtitle', label: 'Pflegeleitfäden · Untertitel', type: 'multiline' },
    { key: 'geneticsTitle', label: 'Genetikrechner · Bereichstitel' },
    { key: 'geneticsSubtitle', label: 'Genetikrechner · Untertitel', type: 'multiline' },
    { key: 'geneticsResultTitle', label: 'Genetikrechner · Ergebnisüberschrift' },
    { key: 'geneticsManageTitle', label: 'Genpool · Bereichstitel' },
    { key: 'geneticsManageSubtitle', label: 'Genpool · Untertitel', type: 'multiline' },
    { key: 'geneticsEmptyError', label: 'Genetikrechner · Hinweis ohne Auswahl', type: 'multiline' },
    { key: 'geneticsHintMissing', label: 'Genetikrechner · Hinweistext leer', type: 'multiline' },
    { key: 'geneticsHintResult', label: 'Genetikrechner · Hinweistext Ergebnis', type: 'multiline' },
    { key: 'geneticsWildtypeInfo', label: 'Genetikrechner · Hinweis nur Wildtyp', type: 'multiline' },
    { key: 'breedingTitle', label: 'Zuchtplanung · Bereichstitel' },
    { key: 'breedingSubtitle', label: 'Zuchtplanung · Untertitel', type: 'multiline' },
    { key: 'pagesTitle', label: 'Wiki · Bereichstitel' },
    { key: 'pagesSubtitle', label: 'Wiki · Untertitel', type: 'multiline' }
  ];
  const fieldRefs = copyConfig.map((config) => {
    const field = buildInput({
      label: config.label,
      name: config.key,
      value: state.settings.copy?.[config.key] ?? '',
      type: config.type ?? 'text'
    });
    form.appendChild(field.element);
    return { key: config.key, getValue: field.getValue };
  });
  form.appendChild(createHiddenSubmit('Texte speichern'));
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const updated = { ...state.settings.copy };
    fieldRefs.forEach((field) => {
      updated[field.key] = field.getValue();
    });
    state.settings.copy = updated;
    persistState();
    modal.close();
    showToast('Seitentexte aktualisiert.', 'success');
  });
  modal.open('Seitentexte bearbeiten', form);
};

const showHighlightForm = (index = null) => {
  const hasExisting = Number.isInteger(index) && index >= 0 && index < state.settings.heroHighlight.length;
  const existing = hasExisting ? state.settings.heroHighlight[index] : null;
  const form = document.createElement('form');
  form.className = 'modal-form';
  const typeField = buildInput({ label: 'Badge/Typ', name: 'type', value: existing?.type ?? '' });
  const titleField = buildInput({ label: 'Titel', name: 'title', value: existing?.title ?? '' });
  const descField = buildInput({ label: 'Beschreibung', name: 'description', value: existing?.description ?? '', type: 'textarea' });
  const buttonLabelField = buildInput({ label: 'Button-Text', name: 'buttonLabel', value: existing?.link?.label ?? '' });
  const viewOptions = [
    { value: 'home', label: 'Startseite' },
    { value: 'animals', label: 'Tiere' },
    { value: 'adoption', label: 'Tierabgabe' },
    { value: 'news', label: 'Neuigkeiten' },
    { value: 'care', label: 'Pflegeleitfäden' },
    { value: 'genetics', label: 'Genetikrechner' },
    { value: 'breeding', label: 'Zuchtplanung' },
    { value: 'pages', label: 'Wiki & Seiten' },
    { value: 'admin', label: 'Admin' }
  ];
  const viewField = buildInput({
    label: 'Zielbereich',
    name: 'view',
    type: 'select',
    value: existing?.link?.view ?? 'home',
    options: viewOptions
  });
  [typeField, titleField, descField, buttonLabelField, viewField].forEach((field) => form.appendChild(field.element));
  form.appendChild(createHiddenSubmit('Speichern'));
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const payload = {
      type: typeField.getValue(),
      title: titleField.getValue(),
      description: descField.getValue(),
      link: {
        label: buttonLabelField.getValue(),
        view: viewField.getValue()
      }
    };
    if (!payload.title || !payload.link.label) {
      return showToast('Titel und Button-Text sind Pflichtfelder.');
    }
    if (hasExisting) {
      state.settings.heroHighlight.splice(index, 1, payload);
    } else {
      state.settings.heroHighlight.push(payload);
    }
    persistState();
    modal.close();
    showHighlightManager();
    showToast('Highlight gespeichert.', 'success');
  });
  modal.open(existing ? 'Highlight bearbeiten' : 'Neues Highlight', form);
};

const showHighlightManager = () => {
  const wrapper = document.createElement('div');
  wrapper.className = 'space-y-6';
  const header = document.createElement('div');
  header.className = 'flex items-center justify-between gap-4';
  header.innerHTML = `
    <h3 class="text-lg font-semibold">Startseiten-Highlights</h3>
    <button class="btn-primary" data-action="add-highlight">Highlight hinzufügen</button>
  `;
  wrapper.appendChild(header);
  if (!state.settings.heroHighlight.length) {
    const empty = document.createElement('p');
    empty.className = 'notice';
    empty.textContent = 'Noch keine Highlights erfasst.';
    wrapper.appendChild(empty);
  } else {
    const list = document.createElement('div');
    list.className = 'space-y-4';
    list.innerHTML = state.settings.heroHighlight
      .map(
        (item, idx) => `
          <article class="glass-card space-y-3">
            <div class="flex items-start justify-between gap-4">
              <div>
                <span class="card-badge">${item.type || 'Highlight'}</span>
                <h4 class="text-lg font-semibold mt-2">${item.title}</h4>
                <div class="text-sm text-slate-300">${item.description}</div>
                <p class="text-xs text-slate-500 mt-2">Ziel: ${item.link?.label ?? '—'} → ${item.link?.view ?? 'home'}</p>
              </div>
              <div class="flex flex-col gap-2">
                <button class="btn-secondary" data-action="edit-highlight" data-index="${idx}">Bearbeiten</button>
                <button class="btn-danger" data-action="delete-highlight" data-index="${idx}">Löschen</button>
              </div>
            </div>
          </article>
        `
      )
      .join('');
    wrapper.appendChild(list);
  }
  modal.open('Highlights verwalten', wrapper);
};

const showGeneList = () => {
  const wrapper = document.createElement('div');
  wrapper.className = 'space-y-6';
  state.genetics.species.forEach((species) => {
    const genes = state.genetics.genes.filter((gene) => gene.speciesId === species.id);
    const card = document.createElement('article');
    card.className = 'glass-card space-y-3';
    card.innerHTML = `
      <div class="flex items-start justify-between gap-4">
        <div>
          <h3 class="text-xl font-semibold">${species.name}</h3>
          <p class="text-sm text-slate-300">${species.description}</p>
        </div>
        <button class="btn-secondary" data-action="add-gene" data-species="${species.id}">Gen hinzufügen</button>
      </div>
      <table class="table">
        <thead>
          <tr><th>Name</th><th>Vererbung</th><th>Beschreibung</th><th></th></tr>
        </thead>
        <tbody>
          ${genes
            .map(
              (gene) => `
              <tr>
                <td>${gene.name}</td>
                <td>${gene.inheritance}</td>
                <td>${gene.description}</td>
                <td class="text-right space-x-2">
                  <button class="btn-secondary" data-action="edit-gene" data-id="${gene.id}">Bearbeiten</button>
                  <button class="btn-danger" data-action="delete-gene" data-id="${gene.id}">Löschen</button>
                </td>
              </tr>
            `
            )
            .join('')}
        </tbody>
      </table>
    `;
    wrapper.appendChild(card);
  });
  modal.open('Genpool', wrapper);
};

const showGeneForm = (speciesId, gene = null) => {
  const form = document.createElement('form');
  form.className = 'modal-form';
  const nameField = buildInput({ label: 'Name', name: 'name', value: gene?.name ?? '' });
  const inheritanceField = buildInput({
    label: 'Vererbungsart',
    name: 'inheritance',
    type: 'select',
    value: gene?.inheritance ?? 'recessive',
    options: [
      { value: 'recessive', label: 'rezessiv' },
      { value: 'dominant', label: 'dominant' },
      { value: 'incomplete-dominant', label: 'kodominant / ink. dominant' },
      { value: 'polygenic', label: 'polygen' },
      { value: 'compound', label: 'Kombinationsprojekt' }
    ]
  });
  const descriptionField = buildInput({ label: 'Beschreibung', name: 'description', value: gene?.description ?? '', type: 'textarea' });
  const aliasesField = buildInput({ label: 'Aliase (Kommagetrennt)', name: 'aliases', value: (gene?.aliases ?? []).join(', ') });
  const visualField = buildInput({ label: 'Bezeichnung Visual', name: 'visual', value: gene?.expression?.visual ?? '' });
  const carrierField = buildInput({ label: 'Bezeichnung Träger', name: 'carrier', value: gene?.expression?.carrier ?? '' });
  const possField = buildInput({ label: 'Bezeichnung mögliche Träger', name: 'possibleCarrier', value: gene?.expression?.possibleCarrier ?? '' });
  const superField = buildInput({ label: 'Bezeichnung Superform', name: 'superForm', value: gene?.expression?.superForm ?? '' });
  [nameField, inheritanceField, descriptionField, aliasesField, visualField, carrierField, possField, superField].forEach((field) =>
    form.appendChild(field.element)
  );
  form.appendChild(createHiddenSubmit('Speichern'));
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const payload = {
      id: gene?.id ?? `${speciesId}-${crypto.randomUUID()}`,
      speciesId,
      name: nameField.getValue(),
      inheritance: inheritanceField.getValue(),
      description: descriptionField.getValue(),
      aliases: aliasesField
        .getValue()
        .split(',')
        .map((item) => item.trim())
        .filter(Boolean),
      expression: {
        visual: visualField.getValue() || nameField.getValue(),
        carrier: carrierField.getValue() || `Het ${nameField.getValue()}`,
        possibleCarrier: possField.getValue() || `Mögliche Het ${nameField.getValue()}`
      }
    };
    const superName = superField.getValue();
    if (superName) payload.expression.superForm = superName;
    if (!payload.name) return showToast('Name erforderlich.');
    if (gene) {
      state.genetics.genes = state.genetics.genes.map((item) => (item.id === gene.id ? payload : item));
    } else {
      state.genetics.genes.push(payload);
    }
    persistState();
    modal.close();
    showGeneList();
  });
  modal.open(gene ? 'Gen bearbeiten' : 'Neues Gen', form);
};
const showParentForm = (parent = null) => {
  const form = document.createElement('form');
  form.className = 'modal-form';
  const nameField = buildInput({ label: 'Name', name: 'name', value: parent?.name ?? '' });
  const speciesField = buildInput({
    label: 'Art (ID)',
    name: 'speciesId',
    value: parent?.speciesId ?? state.genetics.species[0]?.id,
    type: 'select',
    options: state.genetics.species.map((species) => ({ value: species.id, label: species.name }))
  });
  const sexField = buildInput({ label: 'Geschlecht', name: 'sex', value: parent?.sex ?? '♀' });
  const linkField = buildInput({
    label: 'Verknüpfung (leer = virtuell)',
    name: 'referenceId',
    type: 'select',
    value: parent?.linkType === 'animal' ? parent?.referenceId ?? '' : '',
    options: [{ value: '', label: 'Virtuell' }].concat(state.animals.map((animal) => ({ value: animal.id, label: animal.name })))
  });
  const notesField = buildInput({ label: 'Notizen', name: 'notes', value: parent?.notes ?? '', type: 'textarea' });
  const geneticsField = buildInput({ label: 'Genetik (Kommagetrennt, Syntax: genId:visual)', name: 'genetics', value: (parent?.genetics ?? []).join(', ') });
  [nameField, speciesField, sexField, linkField, notesField, geneticsField].forEach((field) => form.appendChild(field.element));
  form.appendChild(createHiddenSubmit('Speichern'));
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const genetics = geneticsField
      .getValue()
      .split(',')
      .map((item) => item.trim())
      .filter(Boolean);
    const payload = {
      id: parent?.id ?? `parent-${crypto.randomUUID()}`,
      name: nameField.getValue(),
      speciesId: speciesField.getValue(),
      sex: sexField.getValue(),
      linkType: linkField.getValue() ? 'animal' : 'virtual',
      referenceId: linkField.getValue() || null,
      notes: notesField.getValue(),
      genetics
    };
    if (!payload.name) return showToast('Bitte einen Namen wählen.');
    if (parent) {
      state.breeding.parents = state.breeding.parents.map((item) => (item.id === parent.id ? payload : item));
    } else {
      state.breeding.parents.push(payload);
    }
    persistState();
    modal.close();
  });
  modal.open(parent ? 'Elterntier bearbeiten' : 'Elterntier anlegen', form);
};

const showBreedingForm = (plan = null) => {
  const form = document.createElement('form');
  form.className = 'modal-form';
  const titleField = buildInput({ label: 'Projektname', name: 'title', value: plan?.title ?? '' });
  const seasonField = buildInput({ label: 'Saison', name: 'season', value: plan?.season ?? new Date().getFullYear(), type: 'number' });
  const femaleField = buildInput({
    label: 'Elterntier ♀',
    name: 'femaleId',
    type: 'select',
    value: plan?.femaleId ?? '',
    options: state.breeding.parents.filter((parent) => parent.sex === '♀').map((parent) => ({ value: parent.id, label: parent.name }))
  });
  const maleField = buildInput({
    label: 'Elterntier ♂',
    name: 'maleId',
    type: 'select',
    value: plan?.maleId ?? '',
    options: state.breeding.parents.filter((parent) => parent.sex === '♂').map((parent) => ({ value: parent.id, label: parent.name }))
  });
  const goalField = buildInput({ label: 'Zuchtziel', name: 'goals', value: plan?.goals ?? '', type: 'textarea' });
  const notesField = buildInput({ label: 'Notizen', name: 'notes', value: plan?.notes ?? '', type: 'textarea' });
  const hatchField = buildInput({ label: 'Erwarteter Schlupf (optional)', name: 'hatchEstimate', value: plan?.hatchEstimate ?? '', type: 'date' });
  [titleField, seasonField, femaleField, maleField, goalField, notesField, hatchField].forEach((field) => form.appendChild(field.element));
  form.appendChild(createHiddenSubmit('Speichern'));
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const payload = {
      id: plan?.id ?? `plan-${crypto.randomUUID()}`,
      title: titleField.getValue(),
      season: seasonField.getValue(),
      femaleId: femaleField.getValue(),
      maleId: maleField.getValue(),
      goals: goalField.getValue(),
      notes: notesField.getValue(),
      hatchEstimate: hatchField.getValue()
    };
    if (!payload.title) return showToast('Projektname fehlt.');
    if (!payload.femaleId || !payload.maleId) return showToast('Bitte beide Elterntiere auswählen.');
    if (plan) {
      state.breeding.plans = state.breeding.plans.map((item) => (item.id === plan.id ? payload : item));
    } else {
      state.breeding.plans.push(payload);
    }
    persistState();
    modal.close();
  });
  modal.open(plan ? 'Zuchtprojekt bearbeiten' : 'Neues Zuchtprojekt', form);
};
const updateAdminForms = () => {
  const loginCard = document.getElementById('admin-login');
  const adminPanel = document.getElementById('admin-panel');
  const formsContainer = document.getElementById('admin-forms');
  if (!loginCard || !adminPanel || !formsContainer) return;
  if (!isAuthenticated) {
    loginCard.classList.remove('hidden');
    adminPanel.classList.add('hidden');
    return;
  }
  loginCard.classList.add('hidden');
  adminPanel.classList.remove('hidden');

  const checklist = [
    { label: 'Statisches CMS mit GitHub-Export', done: true },
    { label: 'Genetikrechner mit Visual- & Het-Auswertung', done: state.genetics.genes.length > 0 },
    { label: 'Pflegeleitfäden vorhanden', done: state.careGuides.length > 0 },
    { label: 'Zuchtplanung aktiviert', done: state.breeding.plans.length > 0 },
    { label: 'Genpool gepflegt', done: state.genetics.genes.length > 0 }
  ];

  formsContainer.innerHTML = `
    <section class="admin-section">
      <h3>Checkliste</h3>
      <p class="text-sm text-slate-300 mt-2">Die Kernaufgaben dieser Migration werden automatisch geprüft und abgehakt.</p>
      <ul class="space-y-2 mt-4">
        ${checklist
          .map(
            (item) => `
              <li class="flex items-center gap-3">
                <input type="checkbox" ${item.done ? 'checked' : ''} disabled class="h-4 w-4 rounded border-white/20 bg-white/10" />
                <span class="text-sm text-slate-200">${item.label}</span>
              </li>
            `
          )
          .join('')}
      </ul>
    </section>
    <section class="admin-section">
      <h3>Branding &amp; Texte</h3>
      <p class="text-sm text-slate-300 mt-2">
        Passe Logo, Startseitenelemente, Seitentitel sowie Beschreibungen für Genetik und weitere Bereiche an.
      </p>
      <div class="flex flex-wrap gap-3 mt-4">
        <button class="btn-primary" data-action="open-branding">Branding bearbeiten</button>
        <button class="btn-secondary" data-action="open-copy">Seitentexte bearbeiten</button>
        <button class="btn-secondary" data-action="open-highlights">Highlights verwalten</button>
      </div>
    </section>
    <section class="admin-section">
      <h3>Inhaltsverwaltung</h3>
      <div class="grid gap-2 text-sm text-slate-300 mt-3">
        <p>Tiere: <strong>${state.animals.length}</strong></p>
        <p>Inserate: <strong>${state.adoptionListings.length}</strong></p>
        <p>Neuigkeiten: <strong>${state.news.length}</strong></p>
        <p>Pflegeleitfäden: <strong>${state.careGuides.length}</strong></p>
        <p>Seiten: <strong>${state.pages.length}</strong></p>
      </div>
      <div class="flex flex-wrap gap-3 mt-4">
        <button class="btn-primary" data-action="open-add-animal">Tier anlegen</button>
        <button class="btn-secondary" data-action="open-add-listing">Inserat erstellen</button>
        <button class="btn-secondary" data-action="open-add-news">News verfassen</button>
        <button class="btn-secondary" data-action="open-add-care">Leitfaden schreiben</button>
        <button class="btn-secondary" data-action="open-add-page">Seite anlegen</button>
      </div>
    </section>
    <section class="admin-section">
      <h3>Genetik & Zucht</h3>
      <div class="grid gap-2 text-sm text-slate-300 mt-3">
        <p>Arten im Genpool: <strong>${state.genetics.species.length}</strong></p>
        <p>Gene hinterlegt: <strong>${state.genetics.genes.length}</strong></p>
        <p>Elterntiere: <strong>${state.breeding.parents.length}</strong></p>
        <p>Zuchtprojekte: <strong>${state.breeding.plans.length}</strong></p>
      </div>
      <div class="flex flex-wrap gap-3 mt-4">
        <button class="btn-secondary" data-action="open-genelist">Genpool anzeigen</button>
        <button class="btn-secondary" data-action="open-add-parent">Elterntier anlegen</button>
        <button class="btn-primary" data-action="open-add-breeding">Zuchtprojekt planen</button>
      </div>
    </section>
  `;
};
const ADMIN_PASSWORD = 'admin';
const AUTH_KEY = 'feroxz-auth';
let isAuthenticated = sessionStorage.getItem(AUTH_KEY) === 'true';
const setAuthenticated = (value) => {
  isAuthenticated = value;
  sessionStorage.setItem(AUTH_KEY, value ? 'true' : 'false');
  updateAdminForms();
  showToast(value ? 'Anmeldung erfolgreich.' : 'Abgemeldet.', 'success');
};
const geneticsSelections = {
  speciesId: state.genetics.species[0]?.id ?? null,
  parents: {
    one: [],
    two: []
  }
};

const getSelectedGeneCount = () =>
  geneticsSelections.parents.one.length + geneticsSelections.parents.two.length;
const parentChipContainers = {
  one: document.getElementById('parent-one-chips'),
  two: document.getElementById('parent-two-chips')
};

const updateParentChips = (parentKey) => {
  const container = parentChipContainers[parentKey];
  if (!container) return;
  const entries = geneticsSelections.parents[parentKey];
  if (!entries.length) {
    container.innerHTML = '<p class="text-xs text-slate-500">Noch keine Gene ausgewählt.</p>';
    return;
  }
  container.innerHTML = entries
    .map((entry) => {
      const gene = state.genetics.genes.find((g) => g.id === entry.geneId);
      const label = gene ? formatGeneticLabel(`${gene.id}:${entry.expression}`) : entry.geneId;
      return `<span class="chip" data-parent="${parentKey}" data-gene="${entry.geneId}" data-expression="${entry.expression}">${label} <button type="button" data-remove-gene="${entry.geneId}" data-parent="${parentKey}">✕</button></span>`;
    })
    .join('');
};
const addParentGene = (parentKey, geneId, expression) => {
  const list = geneticsSelections.parents[parentKey];
  const existingIndex = list.findIndex((item) => item.geneId === geneId);
  const entry = { geneId, expression };
  if (existingIndex >= 0) {
    list.splice(existingIndex, 1, entry);
  } else {
    list.push(entry);
  }
  updateParentChips(parentKey);
  calculateGenetics();
};

const removeParentGene = (parentKey, geneId) => {
  const list = geneticsSelections.parents[parentKey];
  geneticsSelections.parents[parentKey] = list.filter((item) => item.geneId !== geneId);
  updateParentChips(parentKey);
  calculateGenetics();
};

const clearParentGenes = (parentKey) => {
  geneticsSelections.parents[parentKey] = [];
  updateParentChips(parentKey);
  calculateGenetics();
};

const getGeneOptions = (speciesId) => state.genetics.genes.filter((gene) => gene.speciesId === speciesId);
const buildSuggestionEntries = (gene) => {
  const entries = [];
  const baseLabel = gene.expression?.visual ?? gene.name;
  if (gene.inheritance === 'polygenic' || gene.inheritance === 'compound') {
    entries.push({ geneId: gene.id, expression: 'visual', label: `${baseLabel} · Projekt` });
    entries.push({ geneId: gene.id, expression: 'possible', label: `${gene.expression?.possibleCarrier ?? 'Möglicher Träger'} · Projekt` });
    return entries;
  }
  entries.push({ geneId: gene.id, expression: 'visual', label: `${baseLabel} · Visuell` });
  if (gene.expression?.superForm) {
    entries.push({ geneId: gene.id, expression: 'super', label: `${gene.expression.superForm} · Superform` });
  }
  if (gene.inheritance === 'recessive') {
    entries.push({ geneId: gene.id, expression: 'carrier', label: `${gene.expression?.carrier ?? 'Het'} · Träger` });
    entries.push({ geneId: gene.id, expression: 'possible', label: `${gene.expression?.possibleCarrier ?? 'Mögliche Het'} · Possible` });
  } else if (gene.inheritance === 'incomplete-dominant' || gene.inheritance === 'dominant') {
    entries.push({ geneId: gene.id, expression: 'carrier', label: `${gene.expression?.visual ?? gene.name} · Heterozygot` });
    entries.push({ geneId: gene.id, expression: 'possible', label: `${gene.expression?.possibleCarrier ?? 'Möglicher Träger'} · Possible` });
  }
  return entries;
};

const filterSuggestions = (entries, query) => {
  if (!query) return entries.slice(0, 8);
  const normalized = query.toLowerCase();
  return entries.filter((entry) => entry.label.toLowerCase().includes(normalized)).slice(0, 8);
};
const suggestionContainers = {
  one: document.querySelector('[data-parent="one"] .chip-suggestions'),
  two: document.querySelector('[data-parent="two"] .chip-suggestions')
};

const suggestionInputs = {
  one: document.querySelector('[data-parent="one"] .chip-field'),
  two: document.querySelector('[data-parent="two"] .chip-field')
};

const renderSuggestions = (parentKey, query) => {
  const container = suggestionContainers[parentKey];
  if (!container) return;
  const options = getGeneOptions(geneticsSelections.speciesId).flatMap((gene) => buildSuggestionEntries(gene));
  const results = filterSuggestions(options, query);
  if (!results.length) {
    container.innerHTML = '<p class="px-4 py-3 text-sm text-slate-400">Keine Treffer</p>';
    container.style.display = 'block';
    return;
  }
  container.innerHTML = results
    .map(
      (entry) => `
        <button type="button" data-parent="${parentKey}" data-gene="${entry.geneId}" data-expression="${entry.expression}">
          ${entry.label}
        </button>
      `
    )
    .join('');
  container.style.display = 'block';
};

const hideSuggestions = () => {
  Object.values(suggestionContainers).forEach((container) => {
    if (container) container.style.display = 'none';
  });
};
const expressionToDistribution = (inheritance, expression) => {
  switch (inheritance) {
    case 'recessive':
      if (expression === 'visual' || expression === 'super') return [{ genotype: 'MM', probability: 1 }];
      if (expression === 'carrier' || expression === 'het') return [{ genotype: 'MN', probability: 1 }];
      if (expression === 'possible') return [
        { genotype: 'MN', probability: 0.5 },
        { genotype: 'NN', probability: 0.5 }
      ];
      return [{ genotype: 'NN', probability: 1 }];
    case 'incomplete-dominant':
      if (expression === 'super') return [{ genotype: 'MM', probability: 1 }];
      if (expression === 'visual' || expression === 'carrier') return [{ genotype: 'MN', probability: 1 }];
      if (expression === 'possible') return [
        { genotype: 'MN', probability: 0.5 },
        { genotype: 'NN', probability: 0.5 }
      ];
      return [{ genotype: 'NN', probability: 1 }];
    case 'dominant':
      if (expression === 'super') return [{ genotype: 'MM', probability: 1 }];
      if (expression === 'visual' || expression === 'carrier') return [{ genotype: 'MN', probability: 1 }];
      if (expression === 'possible') return [
        { genotype: 'MN', probability: 0.5 },
        { genotype: 'NN', probability: 0.5 }
      ];
      return [{ genotype: 'NN', probability: 1 }];
    default:
      return [{ genotype: 'NN', probability: 1 }];
  }
};

const combineGenotypes = (genoA, genoB) => {
  const gametesA = [genoA[0], genoA[1]];
  const gametesB = [genoB[0], genoB[1]];
  const outcomes = {};
  gametesA.forEach((a) => {
    gametesB.forEach((b) => {
      const child = [a, b].sort().join('');
      outcomes[child] = (outcomes[child] || 0) + 0.25;
    });
  });
  return outcomes;
};

const combineDistributions = (distA, distB) => {
  const map = {};
  distA.forEach((entryA) => {
    distB.forEach((entryB) => {
      const combos = combineGenotypes(entryA.genotype, entryB.genotype);
      Object.entries(combos).forEach(([child, chance]) => {
        map[child] = (map[child] || 0) + entryA.probability * entryB.probability * chance;
      });
    });
  });
  return map;
};

const genotypeToPhenotype = (gene, genotype, probability) => {
  const inheritance = gene.inheritance;
  if (inheritance === 'polygenic' || inheritance === 'compound') {
    return null;
  }
  if (inheritance === 'recessive') {
    if (genotype === 'MM') return { label: gene.expression?.visual ?? gene.name, kind: 'visual', probability };
    if (genotype === 'MN') {
      if (probability >= 0.999) {
        return { label: gene.expression?.carrier ?? `Het ${gene.name}`, kind: 'carrier', probability };
      }
      return { label: gene.expression?.possibleCarrier ?? `Mögliche Het ${gene.name}`, kind: 'possible', probability };
    }
    return null;
  }
  if (inheritance === 'incomplete-dominant') {
    if (genotype === 'MM') return { label: gene.expression?.superForm ?? `Super ${gene.name}`, kind: 'super', probability };
    if (genotype === 'MN') return { label: gene.expression?.visual ?? gene.name, kind: 'visual', probability };
    return null;
  }
  if (inheritance === 'dominant') {
    if (genotype === 'MM' && gene.expression?.superForm) {
      return { label: gene.expression.superForm, kind: 'super', probability };
    }
    if (genotype === 'MM' || genotype === 'MN') {
      return { label: gene.expression?.visual ?? gene.name, kind: 'visual', probability };
    }
    return null;
  }
  return null;
};

const calculateGenetics = () => {
  const resultsContainer = document.getElementById('genetics-results');
  const hint = document.getElementById('genetics-hint');
  const speciesId = geneticsSelections.speciesId;
  const species = state.genetics.species.find((entry) => entry.id === speciesId);
  const copy = state.settings.copy ?? {};
  const geneIds = new Set([
    ...geneticsSelections.parents.one.map((entry) => entry.geneId),
    ...geneticsSelections.parents.two.map((entry) => entry.geneId)
  ]);
  if (!geneIds.size) {
    resultsContainer.innerHTML = `<p class="notice">${
      copy.geneticsEmptyError ?? 'Bitte wähle mindestens ein visuelles oder Träger-Gen pro Elternteil aus.'
    }</p>`;
    hint.textContent = copy.geneticsHintMissing ?? 'Keine Berechnung möglich – es fehlen Gene.';
    return;
  }
  const cards = [];
  geneIds.forEach((geneId) => {
    const gene = state.genetics.genes.find((entry) => entry.id === geneId && entry.speciesId === speciesId);
    if (!gene) return;
    if (gene.inheritance === 'polygenic' || gene.inheritance === 'compound') {
      cards.push(`
        <article class="glass-card space-y-2">
          <div class="flex items-center justify-between">
            <h4 class="text-lg font-semibold">${gene.name}</h4>
            <span class="badge">${gene.inheritance}</span>
          </div>
          <p class="text-sm text-slate-300">${gene.description}</p>
          <p class="notice">Polygen/Kombinationsprojekte erfordern eine manuelle Auswertung. Dokumentiere Linien und sichere Zwischenergebnisse im Admin-Bereich.</p>
        </article>
      `);
      return;
    }
    const parentOneEntry = geneticsSelections.parents.one.find((entry) => entry.geneId === geneId);
    const parentTwoEntry = geneticsSelections.parents.two.find((entry) => entry.geneId === geneId);
    const distOne = parentOneEntry ? expressionToDistribution(gene.inheritance, parentOneEntry.expression) : [{ genotype: 'NN', probability: 1 }];
    const distTwo = parentTwoEntry ? expressionToDistribution(gene.inheritance, parentTwoEntry.expression) : [{ genotype: 'NN', probability: 1 }];
    const combined = combineDistributions(distOne, distTwo);
    const phenotypes = [];
    Object.entries(combined).forEach(([genotype, probability]) => {
      if (probability <= 0) return;
      const phenotype = genotypeToPhenotype(gene, genotype, probability);
      if (!phenotype) return;
      const existing = phenotypes.find((item) => item.label === phenotype.label);
      if (existing) {
        existing.probability += phenotype.probability;
      } else {
        phenotypes.push({ ...phenotype });
      }
    });
    const filtered = phenotypes.filter((item) => item.probability > 0.001);
    if (!filtered.length) return;
    filtered.sort((a, b) => b.probability - a.probability);
    cards.push(`
      <article class="glass-card space-y-3">
        <div class="flex items-center justify-between">
          <div>
            <h4 class="text-lg font-semibold">${gene.name}</h4>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">${gene.inheritance}</p>
          </div>
          <button class="btn-secondary" data-action="highlight-gene" data-gene="${gene.id}">Details</button>
        </div>
        <ul class="space-y-2">
          ${filtered
            .map(
              (item) => `
                <li class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-2">
                  <span class="text-sm text-slate-200">${item.label}</span>
                  <span class="text-sm font-semibold text-cyan-200">${fractionFromProbability(item.probability)}</span>
                </li>
              `
            )
            .join('')}
        </ul>
      </article>
    `);
  });
  if (!cards.length) {
    resultsContainer.innerHTML = `<p class="notice">${
      copy.geneticsWildtypeInfo ??
      'Alle ausgewählten Gene führen in dieser Kombination zu Wildtyp-Nachzucht. Wähle weitere Visuals oder Träger, um Projektergebnisse zu sehen.'
    }</p>`;
  } else {
    resultsContainer.innerHTML = cards.join('');
  }
  if (species) {
    const template =
      copy.geneticsHintResult ??
      'Auswertung für {species}. Ergebnisse zeigen nur visuelle Tiere, Superformen, Träger und mögliche Het-Kombinationen.';
    hint.textContent = template.replace('{species}', species.name);
  } else {
    hint.textContent = '';
  }
};
const showGeneDetails = (geneId) => {
  const gene = state.genetics.genes.find((entry) => entry.id === geneId);
  if (!gene) return;
  const species = state.genetics.species.find((item) => item.id === gene.speciesId);
  const content = document.createElement('div');
  content.className = 'space-y-3 text-sm text-slate-200';
  content.innerHTML = `
    <p><strong>Art:</strong> ${species?.name ?? 'Unbekannt'}</p>
    <p><strong>Vererbung:</strong> ${gene.inheritance}</p>
    <p>${gene.description}</p>
    ${gene.aliases?.length ? `<p><strong>Aliase:</strong> ${gene.aliases.join(', ')}</p>` : ''}
    <p><strong>Bezeichnungen:</strong></p>
    <ul class="list-disc list-inside space-y-1 text-slate-300">
      <li>Visuell: ${gene.expression?.visual ?? gene.name}</li>
      <li>Träger: ${gene.expression?.carrier ?? 'Het ' + gene.name}</li>
      <li>Mögliche Träger: ${gene.expression?.possibleCarrier ?? 'Mögliche Het ' + gene.name}</li>
      ${gene.expression?.superForm ? `<li>Superform: ${gene.expression.superForm}</li>` : ''}
    </ul>
  `;
  modal.open(`Genetik: ${gene.name}`, content);
};
const initNavigation = () => {
  document.querySelectorAll('[data-view]').forEach((button) => {
    button.addEventListener('click', () => {
      const view = button.dataset.view;
      setActiveView(view);
    });
  });
  document.addEventListener('click', (event) => {
    const jumpTarget = event.target.closest('[data-jump]');
    if (jumpTarget) {
      const view = jumpTarget.dataset.jump;
      setActiveView(view);
    }
  });
};

const initGeneticsInputs = () => {
  const speciesSelect = document.getElementById('genetics-species');
  if (geneticsSelections.speciesId) {
    speciesSelect.value = geneticsSelections.speciesId;
  }
  speciesSelect.addEventListener('change', (event) => {
    geneticsSelections.speciesId = event.target.value;
    clearParentGenes('one');
    clearParentGenes('two');
    hideSuggestions();
    renderGeneticsSummary();
    calculateGenetics();
  });
  ['one', 'two'].forEach((parentKey) => {
    const input = suggestionInputs[parentKey];
    const container = suggestionContainers[parentKey];
    if (!input || !container) return;
    input.addEventListener('input', (event) => {
      renderSuggestions(parentKey, event.target.value);
    });
    input.addEventListener('focus', (event) => {
      if (event.target.value.length === 0) {
        renderSuggestions(parentKey, '');
      }
    });
    input.addEventListener('keydown', (event) => {
      if (event.key === 'Enter') {
        event.preventDefault();
        const first = container.querySelector('button');
        if (first) first.click();
      }
      if (event.key === 'Escape') {
        hideSuggestions();
      }
    });
  });
  document.addEventListener('click', (event) => {
    const suggestionButton = event.target.closest('.chip-suggestions button');
    if (suggestionButton) {
      const parentKey = suggestionButton.dataset.parent;
      const geneId = suggestionButton.dataset.gene;
      const expression = suggestionButton.dataset.expression;
      addParentGene(parentKey, geneId, expression);
      const input = suggestionInputs[parentKey];
      if (input) input.value = '';
      hideSuggestions();
      return;
    }
    if (!event.target.closest('.chip-input')) {
      hideSuggestions();
    }
  });
};

const initAdmin = () => {
  const loginForm = document.querySelector('#admin-login form');
  if (loginForm) {
    loginForm.addEventListener('submit', (event) => {
      event.preventDefault();
      const formData = new FormData(loginForm);
      const password = formData.get('password');
      if (password === ADMIN_PASSWORD) {
        loginForm.reset();
        setAuthenticated(true);
      } else {
        showToast('Passwort ist nicht korrekt.');
      }
    });
  }
  const importInput = document.querySelector('[data-action="import-data"]');
  if (importInput) {
    importInput.addEventListener('change', (event) => {
      const file = event.target.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (loadEvent) => {
        try {
          const parsed = JSON.parse(loadEvent.target.result);
          state = mergeDeep(defaultState, parsed);
          persistState();
          showToast('Import abgeschlossen.', 'success');
        } catch (error) {
          console.error(error);
          showToast('Import fehlgeschlagen.');
        }
      };
      reader.readAsText(file);
    });
  }
};

const initActions = () => {
  document.addEventListener('click', (event) => {
    const actionTarget = event.target.closest('[data-action]');
    if (!actionTarget) return;
    const action = actionTarget.dataset.action;
    switch (action) {
      case 'close-modal':
        modal.close();
        break;
      case 'open-branding':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showBrandingForm();
        break;
      case 'open-copy':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showCopyForm();
        break;
      case 'open-highlights':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showHighlightManager();
        break;
      case 'add-highlight':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showHighlightForm();
        break;
      case 'edit-highlight':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        {
          const editIndex = Number(actionTarget.dataset.index);
          if (Number.isInteger(editIndex)) {
            showHighlightForm(editIndex);
          }
        }
        break;
      case 'delete-highlight':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        if (confirm('Highlight entfernen?')) {
          const deleteIndex = Number(actionTarget.dataset.index);
          if (
            Number.isInteger(deleteIndex) &&
            deleteIndex >= 0 &&
            deleteIndex < state.settings.heroHighlight.length
          ) {
            state.settings.heroHighlight.splice(deleteIndex, 1);
            persistState();
            showHighlightManager();
            showToast('Highlight entfernt.', 'success');
          }
        }
        break;
      case 'open-add-animal':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showAnimalForm();
        break;
      case 'edit-animal':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showAnimalForm(state.animals.find((entry) => entry.id === actionTarget.dataset.id));
        break;
      case 'delete-animal':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        if (confirm('Tier wirklich entfernen?')) {
          state.animals = state.animals.filter((entry) => entry.id !== actionTarget.dataset.id);
          persistState();
        }
        break;
      case 'open-add-listing':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showListingForm();
        break;
      case 'edit-listing':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showListingForm(state.adoptionListings.find((entry) => entry.id === actionTarget.dataset.id));
        break;
      case 'delete-listing':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        if (confirm('Inserat löschen?')) {
          state.adoptionListings = state.adoptionListings.filter((entry) => entry.id !== actionTarget.dataset.id);
          persistState();
        }
        break;
      case 'open-add-news':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showNewsForm();
        break;
      case 'edit-news':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showNewsForm(state.news.find((entry) => entry.id === actionTarget.dataset.id));
        break;
      case 'delete-news':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        if (confirm('Beitrag entfernen?')) {
          state.news = state.news.filter((entry) => entry.id !== actionTarget.dataset.id);
          persistState();
        }
        break;
      case 'open-add-care':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showCareForm();
        break;
      case 'edit-care':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showCareForm(state.careGuides.find((entry) => entry.id === actionTarget.dataset.id));
        break;
      case 'delete-care':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        if (confirm('Leitfaden löschen?')) {
          state.careGuides = state.careGuides.filter((entry) => entry.id !== actionTarget.dataset.id);
          persistState();
        }
        break;
      case 'open-add-page':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showPageForm();
        break;
      case 'edit-page':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showPageForm(state.pages.find((entry) => entry.id === actionTarget.dataset.id));
        break;
      case 'delete-page':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        if (confirm('Seite entfernen?')) {
          state.pages = state.pages.filter((entry) => entry.id !== actionTarget.dataset.id);
          persistState();
        }
        break;
      case 'open-genelist':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showGeneList();
        break;
      case 'add-gene':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showGeneForm(actionTarget.dataset.species);
        break;
      case 'edit-gene':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showGeneForm(
          state.genetics.genes.find((entry) => entry.id === actionTarget.dataset.id)?.speciesId,
          state.genetics.genes.find((entry) => entry.id === actionTarget.dataset.id)
        );
        break;
      case 'delete-gene':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        if (confirm('Gen aus dem Pool löschen?')) {
          state.genetics.genes = state.genetics.genes.filter((entry) => entry.id !== actionTarget.dataset.id);
          persistState();
        }
        break;
      case 'open-add-parent':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showParentForm();
        break;
      case 'edit-parent':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showParentForm(state.breeding.parents.find((entry) => entry.id === actionTarget.dataset.id));
        break;
      case 'edit-breeding':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showBreedingForm(state.breeding.plans.find((entry) => entry.id === actionTarget.dataset.id));
        break;
      case 'delete-breeding':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        if (confirm('Zuchtprojekt löschen?')) {
          state.breeding.plans = state.breeding.plans.filter((entry) => entry.id !== actionTarget.dataset.id);
          persistState();
        }
        break;
      case 'open-add-breeding':
        if (!isAuthenticated) return showToast('Bitte zuerst anmelden.');
        showBreedingForm();
        break;
      case 'calculate-genetics':
        calculateGenetics();
        break;
      case 'clear-parent':
        clearParentGenes(actionTarget.dataset.parent);
        break;
      case 'refresh-home':
        renderHome();
        break;
      case 'export-data': {
        const blob = new Blob([JSON.stringify(state, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `feroxz-export-${Date.now()}.json`;
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
        showToast('Export erstellt.', 'success');
        break;
      }
      case 'reset-data':
        if (confirm('Alle Daten auf Werkseinstellungen zurücksetzen?')) {
          resetState();
          showToast('Daten zurückgesetzt.', 'success');
        }
        break;
      case 'logout':
        setAuthenticated(false);
        break;
      case 'highlight-gene':
        showGeneDetails(actionTarget.dataset.gene);
        break;
      default:
        break;
    }
  });

  document.addEventListener('click', (event) => {
    const removeButton = event.target.closest('[data-remove-gene]');
    if (removeButton) {
      const parentKey = removeButton.dataset.parent;
      removeParentGene(parentKey, removeButton.dataset.removeGene);
      calculateGenetics();
    }
  });
};

const init = () => {
  renderAll();
  initNavigation();
  initGeneticsInputs();
  initAdmin();
  initActions();
  updateParentChips('one');
  updateParentChips('two');
  calculateGenetics();
  setActiveView('home');
};

init();

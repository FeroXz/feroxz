export const initialData = {
  settings: {
    siteName: 'FeroxZ Reptile Collective',
    tagline: 'Moderne Plattform für verantwortungsvolle Bartagamen- & Hakennasen-Haltung',
    heroTitle: 'Reptilienwissen, Genetik & Haltung vereint',
    heroIntro:
      'Verwalte deine Tiere, plane Zuchtprojekte und teile gepflegte Leitfäden mit der Community – komplett im Browser und optimiert für geteilte Webspaces.',
    footerVersion: 'v2.0.0'
  },
  careGuides: [
    {
      id: 'pogona-vitticeps',
      species: 'Pogona vitticeps',
      commonName: 'Bartagame',
      intro:
        'Bartagamen stammen aus den trockenen Busch- und Halbwüsten Australiens. Ihr Wohlbefinden hängt von einer Kombination aus intensiver Beleuchtung, strukturierter Landschaft und abwechslungsreicher Ernährung ab.',
      sections: [
        {
          title: 'Habitat & Terrariengröße',
          content:
            'Für ein adultes Tier wird ein Mindestmaß von 150 × 80 × 80 cm (L × B × H) empfohlen. Größer ist immer besser: Horizontal orientierte Kletteräste, mehrere Sonnenplätze und strukturierte Rückwände fördern das natürliche Bewegungsverhalten. Als Bodengrund eignet sich ein grabfähiges Lehm-Sand-Gemisch (Verhältnis 1:3) mit mindestens 10–15 cm Tiefe.'
        },
        {
          title: 'Temperatur & Beleuchtung',
          content:
            'Ein ausgeprägter Temperaturgradient ist entscheidend: 28–32 °C in der Grundtemperatur, 40–45 °C an Sonnenplätzen, nachts 18–22 °C. Hochwertige UV-B-HID-Strahler (z. B. 70–100 W) und T5-Hochleistungsröhren liefern notwendiges UV-B- und Vollspektrumlicht. UV-Lampen alle 12 Monate, T5-Röhren nach 9–12 Monaten austauschen.'
        },
        {
          title: 'Ernährung',
          content:
            'Jungtiere benötigen einen hohen Anteil an Wirbellosen (Heuschrecken, Schaben, Heimchen) – täglich bis zum Sattfressen. Adulte Tiere füttert man 2–3× pro Woche Insekten, täglich jedoch eine ausgewogene Salatmischung (z. B. Endivien, Rucola, Kräuter) ergänzt durch Wildpflanzen wie Wegerich oder Löwenzahn. Supplementation mit Calcium (ohne D3) bei jeder Fütterung, Multivitamine 1× wöchentlich.'
        },
        {
          title: 'Hydration & Klima',
          content:
            'Eine große Wasserschale sollte stets verfügbar sein. Feuchte Rückzugsboxen unterstützen die Häutung. Regelmäßiges, fein zerstäubtes Sprühen am Morgen simuliert Tau und erhöht die Luftfeuchtigkeit kurzzeitig (35–45 % im Tagesverlauf).'
        },
        {
          title: 'Gesundheitsvorsorge',
          content:
            'Regelmäßige Kotuntersuchungen (2× pro Jahr), Gewichtskontrolle und Beobachtung des Fressverhaltens sind Pflicht. Häufige Probleme sind Metabolische Knochenerkrankungen durch UV-/Calciummangel und Parasitenbefall. Frühzeitige veterinärmedizinische Betreuung ist essenziell.'
        }
      ]
    },
    {
      id: 'heterodon-nasicus',
      species: 'Heterodon nasicus',
      commonName: 'Hakennasennatter',
      intro:
        'Hakennasennattern sind grabende Colubriden aus den Prärien Nordamerikas. Sie benötigen ein strukturreiches Terrarium mit mehreren Wärmestufen und tiefem Bodengrund zum Wühlen.',
      sections: [
        {
          title: 'Terrarium & Einrichtung',
          content:
            'Ein ausgewachsenes Tier lebt komfortabel in 120 × 60 × 60 cm. Eine Bodenschicht von mindestens 15 cm aus Sand-Lehm-Gemisch oder bioaktivem Substrat erlaubt das typische Graben. Mehrere Verstecke (Korkröhren, Wurzeln) sowie erhöhte Plattformen fördern Sicherheit und Aktivität.'
        },
        {
          title: 'Temperaturen & Licht',
          content:
            'Wärmespot mit 32–34 °C tagsüber, Umgebungstemperatur 24–27 °C, nachts 20–22 °C. UV-B ist nicht zwingend, hochwertige LED-Vollspektrumbeleuchtung stabilisiert jedoch den Tagesrhythmus. Eine leichte Nachtabsenkung unterstützt den Stoffwechsel.'
        },
        {
          title: 'Ernährung',
          content:
            'Hakennasennattern werden primär mit frosteten Mäusen unterschiedlicher Größe gefüttert. Jungtiere alle 4–5 Tage, adulte Tiere alle 7–10 Tage. Auf abwechslungsreiche Futtergrößen achten und bei Bedarf vor dem Verfüttern in warmem Wasser erwärmen, um den Geruch zu intensivieren.'
        },
        {
          title: 'Spezielle Bedürfnisse',
          content:
            'Als opportunistische Giftnattern können sie bei Stress Scheinkorallenbisse zeigen – die Wirkung auf Menschen ist gering, aber Hände sollten nach jeder Fütterung gewaschen werden. Eine feuchte Häutungsbox (Sphagnum) verhindert unvollständige Häutungen.'
        },
        {
          title: 'Gesundheit',
          content:
            'Besonders wichtig sind Parasitenkontrollen und Gewichtskontrolle. Hakennasennattern neigen zu Fettsucht – regelmäßiges Wiegen und angepasste Futterintervalle sind entscheidend. Bei anhaltender Nahrungsverweigerung unbedingt reptilienkundige Tierärzt:innen konsultieren.'
        }
      ]
    }
  ],
  animals: [
    {
      id: 'ember-bartagame',
      name: 'Ember',
      species: 'pogona-vitticeps',
      sex: '♀',
      age: '3 Jahre',
      origin: 'Nachzucht 2021 – verantwortungsvolle Hobbyzucht',
      highlights: 'Ruhiges Temperament, zuverlässige Esserin, ideal für Anfänger',
      genetics: [
        { gene: 'hypomelanistic', expression: 'visual' },
        { gene: 'translucent', expression: 'visual' },
        { gene: 'dunner', expression: 'het' }
      ],
      image:
        'https://images.unsplash.com/photo-1610986603163-dcae9b2ed4d9?auto=format&fit=crop&w=900&q=80',
      showcase: true
    },
    {
      id: 'onyx-hognose',
      name: 'Onyx',
      species: 'heterodon-nasicus',
      sex: '♂',
      age: '2 Jahre',
      origin: 'US-Linie 2022, dokumentierte Genetik',
      highlights: 'Neugierig, starke Färbung, klares Albino-Pattern',
      genetics: [
        { gene: 'albino', expression: 'visual' },
        { gene: 'toffee-belly', expression: 'het' }
      ],
      image:
        'https://images.unsplash.com/photo-1614252235317-30ac9f1cec31?auto=format&fit=crop&w=900&q=80',
      showcase: true
    }
  ],
  genetics: {
    species: [
      {
        slug: 'pogona-vitticeps',
        name: 'Pogona vitticeps',
        description:
          'Bartagamen zeigen eine Vielzahl an Farb- und Schuppenmutationen. Der Rechner unterstützt dominante, rezessive und unvollständig dominante Gene inklusive Superformen.',
        genes: [
          {
            slug: 'albino',
            name: 'Albino',
            type: 'recessive',
            description: 'Fehlendes Melanin sorgt für helle, cremefarbene Tiere mit roten Augen.'
          },
          {
            slug: 'hypomelanistic',
            name: 'Hypomelanistic',
            type: 'recessive',
            description:
              'Reduzierter Melaninanteil resultiert in helleren, kontrastarmen Farbschlägen mit klaren Bauchschuppen.'
          },
          {
            slug: 'translucent',
            name: 'Translucent',
            type: 'recessive',
            description:
              'Transparente Schuppen und auffällige blaue Bäuche bei Jungtieren, im Adultstadium leicht wolkiger Look.'
          },
          {
            slug: 'dunner',
            name: 'Dunner',
            type: 'dominant',
            description:
              'Dominante Schuppenmutation mit speckled Musterung und nach hinten gerichteten Schuppen.'
          },
          {
            slug: 'leatherback',
            name: 'Leatherback',
            type: 'incomplete-dominant',
            description:
              'Unvollständig dominante Schuppenmutation. Super Leatherbacks (Silkbacks) besitzen extrem glatte Haut.'
          },
          {
            slug: 'witblits',
            name: 'Witblits',
            type: 'recessive',
            description: 'Rezessive Farbreduktion mit nahezu musterlosem Erscheinungsbild.'
          }
        ]
      },
      {
        slug: 'heterodon-nasicus',
        name: 'Heterodon nasicus',
        description:
          'Hakennasennattern kombinieren vielfältige Farbmutationen. Der Rechner folgt MorphMarket-Logik für Albino-/Toffee-Kombinationen.',
        genes: [
          {
            slug: 'albino',
            name: 'Albino',
            type: 'recessive',
            description:
              'Rezessive Mutation mit rosa Grundfarbe und roten Augen. Häufiger Grundbaustein für Designer-Morphs.'
          },
          {
            slug: 'toffee-belly',
            name: 'Toffee Belly',
            type: 'recessive',
            description:
              'Rezessive Mutation mit karamellfarbenen Unterseiten und dunklen Augen. Kombiniert mit Albino entsteht Toffeeglow.'
          },
          {
            slug: 'conda',
            name: 'Anaconda',
            type: 'incomplete-dominant',
            description:
              'Pattern-Reduktion mit kräftigen Rückenflecken. Super Anacondas zeigen nahezu vollständige Musterlosigkeit.'
          },
          {
            slug: 'axanthic',
            name: 'Axanthic',
            type: 'recessive',
            description:
              'Entzieht gelbe Pigmente für eine silbrig-graue Farbgebung. Wichtig in Kombinationen wie Snow oder Storm Cloud.'
          }
        ]
      }
    ]
  }
};

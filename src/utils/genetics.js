function getAlleleSets(type, expression) {
  const dominant = expression === 'visual' || expression === 'super';
  switch (type) {
    case 'recessive': {
      switch (expression) {
        case 'visual':
          return [{ alleles: ['r', 'r'], weight: 1 }];
        case 'het':
          return [{ alleles: ['R', 'r'], weight: 1 }];
        case 'possibleHet':
          return [
            { alleles: ['R', 'r'], weight: 0.5 },
            { alleles: ['R', 'R'], weight: 0.5 }
          ];
        default:
          return [{ alleles: ['R', 'R'], weight: 1 }];
      }
    }
    case 'incomplete-dominant': {
      if (expression === 'super') {
        return [{ alleles: ['D', 'D'], weight: 1 }];
      }
      if (expression === 'visual') {
        return [{ alleles: ['D', 'd'], weight: 1 }];
      }
      if (expression === 'possibleHet') {
        return [
          { alleles: ['D', 'd'], weight: 0.5 },
          { alleles: ['d', 'd'], weight: 0.5 }
        ];
      }
      return [{ alleles: ['d', 'd'], weight: 1 }];
    }
    case 'dominant': {
      if (dominant) {
        return [{ alleles: ['D', 'd'], weight: 1 }];
      }
      return [{ alleles: ['d', 'd'], weight: 1 }];
    }
    default:
      return [{ alleles: ['A', 'A'], weight: 1 }];
  }
}

function getGametes(alleles) {
  const [first, second] = alleles;
  if (first === second) {
    return [{ allele: first, probability: 1 }];
  }
  return [
    { allele: first, probability: 0.5 },
    { allele: second, probability: 0.5 }
  ];
}

function normalizeGenotype(a, b) {
  return [a, b].sort().join('');
}

function interpretOutcome(type, genotype) {
  switch (type) {
    case 'recessive':
      if (genotype === 'rr') return 'visual';
      if (genotype === 'Rr') return 'het';
      return 'normal';
    case 'incomplete-dominant':
      if (genotype === 'DD') return 'super';
      if (genotype === 'Dd') return 'visual';
      return 'normal';
    case 'dominant':
      if (genotype === 'DD' || genotype === 'Dd') return 'visual';
      return 'normal';
    default:
      return 'normal';
  }
}

function describeOutcome(gene, outcome) {
  switch (gene.type) {
    case 'recessive':
      if (outcome === 'visual') return gene.name;
      if (outcome === 'het') return `Het ${gene.name}`;
      return null;
    case 'incomplete-dominant':
      if (outcome === 'super') return `Super ${gene.name}`;
      if (outcome === 'visual') return gene.name;
      return null;
    case 'dominant':
      if (outcome === 'visual') return gene.name;
      return null;
    default:
      return null;
  }
}

export function computeGeneProbabilities(gene, parentAExpression, parentBExpression) {
  const parentAAlleles = getAlleleSets(gene.type, parentAExpression);
  const parentBAlleles = getAlleleSets(gene.type, parentBExpression);
  const distribution = {};

  parentAAlleles.forEach((setA) => {
    const gametesA = getGametes(setA.alleles);
    parentBAlleles.forEach((setB) => {
      const gametesB = getGametes(setB.alleles);
      gametesA.forEach((gameteA) => {
        gametesB.forEach((gameteB) => {
          const genotype = normalizeGenotype(gameteA.allele, gameteB.allele);
          const phenotype = interpretOutcome(gene.type, genotype);
          const probability = setA.weight * setB.weight * gameteA.probability * gameteB.probability;
          distribution[phenotype] = (distribution[phenotype] || 0) + probability;
        });
      });
    });
  });

  return distribution;
}

export function mergeOutcomes(genes, parentA, parentB) {
  const perGene = genes.map((gene) => {
    const parentEntryA = parentA.find((entry) => entry.gene === gene.slug);
    const parentEntryB = parentB.find((entry) => entry.gene === gene.slug);
    const expressionA = parentEntryA?.expression || 'normal';
    const expressionB = parentEntryB?.expression || 'normal';
    const probabilities = computeGeneProbabilities(gene, expressionA, expressionB);
    return {
      gene,
      expressionA,
      expressionB,
      probabilities
    };
  });

  const combined = perGene.reduce(
    (acc, entry) => {
      const options = Object.entries(entry.probabilities);
      if (!options.length) {
        return acc;
      }
      const results = [];
      acc.forEach((variant) => {
        options.forEach(([phenotype, probability]) => {
          const descriptor = describeOutcome(entry.gene, phenotype);
          results.push({
            probability: variant.probability * probability,
            descriptors: descriptor ? [...variant.descriptors, descriptor] : [...variant.descriptors]
          });
        });
      });
      return results;
    },
    [{ probability: 1, descriptors: [] }]
  );

  const normalized = combined
    .map((item) => ({
      ...item,
      probability: Math.round(item.probability * 1000) / 10,
      descriptors: item.descriptors.sort()
    }))
    .filter((item) => item.probability > 0.05);

  const aggregated = {};
  normalized.forEach((item) => {
    const key = item.descriptors.join(' / ') || 'Normal';
    aggregated[key] = (aggregated[key] || 0) + item.probability;
  });

  const merged = Object.entries(aggregated)
    .map(([label, probability]) => ({ label, probability: Math.min(100, Math.round(probability * 10) / 10) }))
    .sort((a, b) => b.probability - a.probability);

  return { perGene, merged };
}

export function parseGeneticsFromAnimal(animal) {
  return animal?.genetics?.map((entry) => ({ gene: entry.gene, expression: entry.expression })) || [];
}

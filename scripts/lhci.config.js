module.exports = {
  ci: {
    collect: {
      numberOfRuns: 1,
      url: [
        'http://localhost:8080/',
        'http://localhost:8080/index.php?route=adoption',
        'http://localhost:8080/index.php?route=care-guide'
      ],
    },
    upload: {
      target: 'filesystem',
      outputDir: 'reports/lighthouse'
    },
    assert: {
      assertions: {
        'categories:performance': ['error', {minScore: 0.9}],
        'categories:accessibility': ['error', {minScore: 0.95}],
        'categories:seo': ['error', {minScore: 0.95}],
        'categories:best-practices': ['error', {minScore: 0.95}]
      }
    }
  }
};

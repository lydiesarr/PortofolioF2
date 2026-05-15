<?php
require 'fonctions.php';

$projets = [
  [
    'num'          => '01',
    'titre'        => 'TechEdu',
    'description'  => 'Projet de groupe pour la création d\'une école de formation technologique dédiée à l\'excellence académique et professionnelle en Afrique.',
    'technologies' => ['HTML', 'CSS', 'Groupe'],
  ],
  [
    'num'          => '02',
    'titre'        => 'ESTM',
    'description'  => 'Projet solo axé sur la maîtrise des balises HTML. Site de promotion de mon école pour lui donner plus de visibilité auprès des collèges et lycées.',
    'technologies' => ['HTML', 'CSS', 'Solo'],
  ],
  [
    'num'          => '03',
    'titre'        => 'Projet Fédérations',
    'description'  => 'Projet entrepreneurial combinant administration réseau et développement pour résoudre des problématiques complexes de flux de données.',
    'technologies' => ['HTML', 'CSS', 'JavaScript', 'PHP'],
  ],
  [
    'num'          => '04',
    'titre'        => 'Site Soins Capillaires',
    'description'  => 'Conçu de A à Z comme terrain d\'entraînement. Transformer une thématique esthétique en une interface fluide.',
    'technologies' => ['HTML', 'CSS', 'JavaScript'],
  ],
  [
    'num'          => '05',
    'titre'        => 'Site Gourmandise',
    'description'  => 'Solution métier complète pour un commerce local. Focus sur l\'architecture d\'information et la fluidité de navigation.',
    'technologies' => ['HTML', 'CSS', 'JavaScript'],
  ],
  [
    'num'          => '06',
    'titre'        => 'Club des Jeunes Filles',
    'description'  => 'Association qui défend les droits de la femme et lutte contre les violences faites aux femmes et aux filles.',
    'technologies' => ['Groupe', 'Association'],
  ],
  [
    'num'          => '07',
    'titre'        => 'Porte-parole',
    'description'  => 'Élue porte-parole du gouvernement scolaire. Développement de la confiance en soi et de la prise de parole en public.',
    'technologies' => ['Groupe', 'École', 'Gouvernement'],
  ],
];

$mot_cle = nettoyer($_GET['q'] ?? '');
$resultats = [];

if ($mot_cle !== '') {
  foreach ($projets as $projet) {
    if (stripos($projet['titre'], $mot_cle) !== false ||
        stripos($projet['description'], $mot_cle) !== false ||
        in_array(strtolower($mot_cle), array_map('strtolower', $projet['technologies']))) {
      $resultats[] = $projet;
    }
  }
} else {
  $resultats = $projets;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portfolio · Projets</title>
  <link rel="stylesheet" href="style.css"/>
  <script src="theme.js"></script>
</head>
<body>

  <?php require 'composants/navigation.php'; ?>

  <div class="page" style="justify-content:flex-start;">
    <div class="section-label fade-up">Projets</div>
    <h2 class="fade-up d1">Ce que j'ai réalisé</h2>

    <div class="projects-grid fade-up d2">
      <?php foreach ($projets as $projet) : ?>
      <div class="project-card">
        <div class="project-num"><?= htmlspecialchars($projet['num']) ?></div>
        <h3><?= htmlspecialchars($projet['titre']) ?></h3>
        <p><?= htmlspecialchars($projet['description']) ?></p>
        <div class="project-techs">
          <?php foreach ($projet['technologies'] as $tech) : ?>
            <span class="tech"><?= htmlspecialchars($tech) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div style="max-width:1100px; margin:0 auto; padding:0 60px 80px;">
    <div class="section-label fade-up">Recherche</div>
    <h2 class="fade-up d1" style="font-size:clamp(1.5rem, 3vw, 2.2rem); margin-bottom:16px;">Parcourir les projets</h2>

    <form method="GET" action="projets.php" class="fade-up d2" style="margin-bottom:28px; display:flex; gap:12px; max-width:500px;">
      <div class="form-group" style="flex:1; margin:0;">
        <input
          type="text"
          name="q"
          placeholder="Rechercher un projet..."
          value="<?= htmlspecialchars($mot_cle) ?>"
        />
      </div>
      <button type="submit" class="btn" style="white-space:nowrap;">Rechercher</button>
      <?php if ($mot_cle !== '') : ?>
        <a href="projets.php" class="btn btn-outline" style="white-space:nowrap;">Tout voir</a>
      <?php endif; ?>
    </form>

    <div class="search-tags fade-up d2">
      <a href="projets.php" class="search-tag">Tous</a>
      <a href="projets.php?q=HTML" class="search-tag">HTML</a>
      <a href="projets.php?q=CSS" class="search-tag">CSS</a>
      <a href="projets.php?q=JavaScript" class="search-tag">JavaScript</a>
      <a href="projets.php?q=PHP" class="search-tag">PHP</a>
      <a href="projets.php?q=Solo" class="search-tag">Solo</a>
      <a href="projets.php?q=Groupe" class="search-tag">Groupe</a>
      <a href="projets.php?q=Association" class="search-tag">Association</a>
    </div>
    <?php if ($mot_cle !== '') : ?>
      <p style="color:var(--muted); margin-bottom:20px;">
        <?= count($resultats) ?> résultat(s) pour "<strong><?= htmlspecialchars($mot_cle) ?></strong>"
      </p>
    <?php endif; ?>

    <?php if (!empty($resultats)) : ?>
      <?php foreach ($resultats as $projet) : ?>
      <div class="projet-result fade-up">
        <div class="projet-num-small"><?= htmlspecialchars($projet['num']) ?> — <?= implode(' · ', array_map('htmlspecialchars', $projet['technologies'])) ?></div>
        <h3><?= htmlspecialchars($projet['titre']) ?></h3>
        <p><?= htmlspecialchars($projet['description']) ?></p>
      </div>
      <?php endforeach; ?>
    <?php else : ?>
      <p style="color:var(--muted); padding:24px 0;">Aucun projet ne correspond à ta recherche.</p>
    <?php endif; ?>
  </div>

  <?php require 'composants/pied-de-page.php'; ?>

</body>
</html>

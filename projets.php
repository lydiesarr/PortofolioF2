<?php
require_once 'config/connexion.php';
require_once 'fonctions.php';
enregistrer_visite($pdo, 'projets');

$mot_cle = trim($_GET['q'] ?? '');

if ($mot_cle !== '') {
    $stmt = $pdo->prepare(
        'SELECT * FROM projets WHERE titre LIKE :q OR description LIKE :q ORDER BY date_creation DESC'
    );
    $stmt->execute([':q' => '%' . $mot_cle . '%']);
} else {
    $stmt = $pdo->query('SELECT * FROM projets ORDER BY date_creation DESC');
}
$projets   = $stmt->fetchAll();
$resultats = $projets;
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
      <?php foreach ($projets as $i => $projet) : ?>
      <div class="project-card">
        <?php if (!empty($projet['image'])) : ?>
          <img src="images/projets/<?= htmlspecialchars($projet['image']) ?>" alt="<?= htmlspecialchars($projet['titre']) ?>" style="width:100%;border-radius:8px;margin-bottom:12px;object-fit:cover;max-height:160px;"/>
        <?php endif; ?>
        <div class="project-num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
        <h3><?= htmlspecialchars($projet['titre']) ?></h3>
        <p><?= htmlspecialchars($projet['description']) ?></p>
        <div class="project-techs">
          <?php foreach (explode(',', $projet['technologies']) as $tech) : ?>
            <span class="tech"><?= htmlspecialchars(trim($tech)) ?></span>
          <?php endforeach; ?>
        </div>
        <?php if (!empty($projet['lien'])) : ?>
          <a href="<?= htmlspecialchars($projet['lien']) ?>" target="_blank" rel="noopener" class="btn btn-outline" style="margin-top:12px;font-size:.85rem;">Voir le projet</a>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
      <?php if (empty($projets)) : ?>
        <p style="color:var(--muted);">Aucun projet à afficher pour l'instant.</p>
      <?php endif; ?>
    </div>
  </div>

  <div style="max-width:1100px; margin:0 auto; padding:0 60px 80px;">
    <div class="section-label fade-up">Recherche</div>
    <h2 class="fade-up d1" style="font-size:clamp(1.5rem, 3vw, 2.2rem); margin-bottom:16px;">Parcourir les projets</h2>

    <form method="GET" action="projets.php" class="fade-up d2" style="margin-bottom:28px; display:flex; gap:12px; max-width:500px;">
      <div class="form-group" style="flex:1; margin:0;">
        <input type="text" name="q" placeholder="Rechercher un projet..." value="<?= htmlspecialchars($mot_cle) ?>"/>
      </div>
      <button type="submit" class="btn" style="white-space:nowrap;">Rechercher</button>
      <?php if ($mot_cle !== '') : ?>
        <a href="projets.php" class="btn btn-outline" style="white-space:nowrap;">Tout voir</a>
      <?php endif; ?>
    </form>

    <?php if ($mot_cle !== '') : ?>
      <p style="color:var(--muted); margin-bottom:20px;">
        <?= count($resultats) ?> résultat(s) pour "<strong><?= htmlspecialchars($mot_cle) ?></strong>"
      </p>
    <?php endif; ?>

    <?php if (!empty($resultats)) : ?>
      <?php foreach ($resultats as $projet) : ?>
      <div class="projet-result fade-up">
        <div class="projet-num-small"><?= htmlspecialchars($projet['technologies']) ?></div>
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

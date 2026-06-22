<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifier_session_admin();

$projets = $pdo->query('SELECT * FROM projets ORDER BY date_creation DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Projets</title>
  <link rel="stylesheet" href="/portfolio/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Projets</h1>
      <a href="/portfolio/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <div class="actions">
        <a href="creer.php" class="btn btn-primary">+ Nouveau projet</a>
      </div>
      <div class="card">
        <table>
          <thead>
            <tr><th>Titre</th><th>Technologies</th><th>Date</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach ($projets as $p) : ?>
            <tr>
              <td><?= htmlspecialchars($p['titre']) ?></td>
              <td><?= htmlspecialchars($p['technologies']) ?></td>
              <td><?= htmlspecialchars($p['date_creation']) ?></td>
              <td style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="modifier.php?id=<?= (int)$p['id'] ?>" class="btn btn-secondary btn-sm">Modifier</a>
                <a href="supprimer.php?id=<?= (int)$p['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($projets)) : ?>
              <tr><td colspan="4" style="color:#888;">Aucun projet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>

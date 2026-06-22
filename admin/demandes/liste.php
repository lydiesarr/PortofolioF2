<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifier_session_admin();

$demandes = $pdo->query('SELECT * FROM demandes_projet ORDER BY date_demande DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Demandes de projet</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Demandes de projet</h1>
      <a href="<?= BASE_URL ?>/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <div class="card">
        <table>
          <thead><tr><th>Nom</th><th>Email</th><th>Type</th><th>Budget</th><th>Date</th><th>Statut</th><th>Action</th></tr></thead>
          <tbody>
            <?php foreach ($demandes as $d) : ?>
            <tr class="<?= $d['lu'] == 0 ? 'unread' : '' ?>">
              <td><?= htmlspecialchars($d['nom']) ?></td>
              <td><?= htmlspecialchars($d['email']) ?></td>
              <td><?= htmlspecialchars($d['type_projet']) ?></td>
              <td><?= htmlspecialchars($d['budget'] ?? '—') ?></td>
              <td><?= htmlspecialchars($d['date_demande']) ?></td>
              <td><?= $d['lu'] ? 'Lu' : '<strong>Non lu</strong>' ?></td>
              <td><a href="voir.php?id=<?= (int)$d['id'] ?>" class="btn btn-secondary btn-sm">Voir</a></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($demandes)) : ?>
              <tr><td colspan="7" style="color:#888;">Aucune demande.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>

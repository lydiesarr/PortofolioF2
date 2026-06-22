<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifier_session_admin();

$messages = $pdo->query('SELECT * FROM messages_contact ORDER BY date_envoi DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Messages</title>
  <link rel="stylesheet" href="/PortofolioF2/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Messages de contact</h1>
      <a href="/PortofolioF2/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <div class="card">
        <table>
          <thead><tr><th>Nom</th><th>Email</th><th>Date</th><th>Statut</th><th>Action</th></tr></thead>
          <tbody>
            <?php foreach ($messages as $m) : ?>
            <tr class="<?= $m['lu'] == 0 ? 'unread' : '' ?>">
              <td><?= htmlspecialchars($m['nom']) ?></td>
              <td><?= htmlspecialchars($m['email']) ?></td>
              <td><?= htmlspecialchars($m['date_envoi']) ?></td>
              <td><?= $m['lu'] ? 'Lu' : '<strong>Non lu</strong>' ?></td>
              <td><a href="voir.php?id=<?= (int)$m['id'] ?>" class="btn btn-secondary btn-sm">Voir</a></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($messages)) : ?>
              <tr><td colspan="5" style="color:#888;">Aucun message.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>

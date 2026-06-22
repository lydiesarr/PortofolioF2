<?php
require_once '../config/connexion.php';
require_once '../fonctions.php';
verifier_session_admin();

$nb_projets  = $pdo->query('SELECT COUNT(*) FROM projets')->fetchColumn();
$nb_msg_nlus = $pdo->query('SELECT COUNT(*) FROM messages_contact WHERE lu = 0')->fetchColumn();
$nb_dem_nlus = $pdo->query('SELECT COUNT(*) FROM demandes_projet WHERE lu = 0')->fetchColumn();

$dernieres_visites  = $pdo->query('SELECT * FROM visites ORDER BY date_visite DESC LIMIT 5')->fetchAll();
$dernieres_demandes = $pdo->query('SELECT * FROM demandes_projet ORDER BY date_demande DESC LIMIT 5')->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Tableau de bord</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Tableau de bord</h1>
      <a href="<?= BASE_URL ?>/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <div class="actions" style="margin-bottom:16px;">
        <a href="<?= BASE_URL ?>/admin/utilisateurs/creer.php" class="btn btn-primary">+ Nouvel administrateur</a>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="val"><?= (int)$nb_projets ?></div>
          <div class="label">Projets publiés</div>
        </div>
        <div class="stat-card">
          <div class="val"><?= (int)$nb_msg_nlus ?></div>
          <div class="label">Messages non lus</div>
        </div>
        <div class="stat-card">
          <div class="val"><?= (int)$nb_dem_nlus ?></div>
          <div class="label">Demandes non lues</div>
        </div>
      </div>

      <div class="card">
        <h2>5 dernières visites</h2>
        <table>
          <thead><tr><th>IP</th><th>Page</th><th>Date</th></tr></thead>
          <tbody>
            <?php foreach ($dernieres_visites as $v) : ?>
            <tr>
              <td><?= htmlspecialchars($v['adresse_ip']) ?></td>
              <td><?= htmlspecialchars($v['page']) ?></td>
              <td><?= htmlspecialchars($v['date_visite']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($dernieres_visites)) : ?>
              <tr><td colspan="3" style="color:#888;">Aucune visite enregistrée.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h2>5 dernières demandes de projet</h2>
        <table>
          <thead><tr><th>Nom</th><th>Email</th><th>Type</th><th>Date</th></tr></thead>
          <tbody>
            <?php foreach ($dernieres_demandes as $d) : ?>
            <tr class="<?= $d['lu'] == 0 ? 'unread' : '' ?>">
              <td><?= htmlspecialchars($d['nom']) ?></td>
              <td><?= htmlspecialchars($d['email']) ?></td>
              <td><?= htmlspecialchars($d['type_projet']) ?></td>
              <td><?= htmlspecialchars($d['date_demande']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($dernieres_demandes)) : ?>
              <tr><td colspan="4" style="color:#888;">Aucune demande reçue.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
</body>
</html>

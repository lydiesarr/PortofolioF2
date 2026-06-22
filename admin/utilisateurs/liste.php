<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifier_session_admin();

$admins = $pdo->query('SELECT id, prenom, nom, email, date_creation FROM administrateurs ORDER BY date_creation ASC')->fetchAll();
$succes = $_GET['deleted'] ?? $_GET['created'] ?? $_GET['updated'] ?? '';

$can_add_admin = true;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Administrateurs</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Administrateurs</h1>
      <a href="<?= BASE_URL ?>/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <?php if ($succes) : ?>
        <div class="alert alert-success">Opération effectuée avec succès.</div>
      <?php endif; ?>
      <?php if ($can_add_admin) : ?>
      <div class="actions">
        <a href="creer.php" class="btn btn-primary">+ Nouvel administrateur</a>
      </div>
      <?php endif; ?>
      <div class="card">
        <table>
          <thead><tr><th>Prénom</th><th>Nom</th><th>Email</th><th>Créé le</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach ($admins as $a) : ?>
            <tr>
              <td><?= htmlspecialchars($a['prenom']) ?></td>
              <td><?= htmlspecialchars($a['nom']) ?></td>
              <td><?= htmlspecialchars($a['email']) ?></td>
              <td><?= htmlspecialchars($a['date_creation']) ?></td>
              <td style="display:flex;gap:8px;flex-wrap:wrap;">
                <?php if ((int)$_SESSION['admin_id'] === 2 || (int)$a['id'] === (int)$_SESSION['admin_id']) : ?>
                <a href="modifier.php?id=<?= (int)$a['id'] ?>" class="btn btn-secondary btn-sm">Modifier</a>
                <?php endif; ?>
                <?php if ((int)$_SESSION['admin_id'] === 2 && (int)$a['id'] !== (int)$_SESSION['admin_id'] && (int)$a['id'] !== 2) : ?>
                  <a href="supprimer.php?id=<?= (int)$a['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>

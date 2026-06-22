<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifier_session_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: liste.php'); exit; }

$stmt = $pdo->prepare('SELECT * FROM demandes_projet WHERE id = :id');
$stmt->execute([':id' => $id]);
$dem = $stmt->fetch();
if (!$dem) { header('Location: liste.php'); exit; }

// Marquer comme lu
if ($dem['lu'] == 0) {
    $pdo->prepare('UPDATE demandes_projet SET lu = 1 WHERE id = :id')->execute([':id' => $id]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Demande de projet</title>
  <link rel="stylesheet" href="/portfolio/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Demande de <?= htmlspecialchars($dem['nom']) ?></h1>
      <a href="/portfolio/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <div class="actions">
        <a href="liste.php" class="btn btn-secondary">← Retour</a>
      </div>
      <div class="msg-detail">
        <div class="meta">
          De : <strong><?= htmlspecialchars($dem['nom']) ?></strong> —
          <a href="mailto:<?= htmlspecialchars($dem['email']) ?>"><?= htmlspecialchars($dem['email']) ?></a><br/>
          Type : <?= htmlspecialchars($dem['type_projet']) ?> &nbsp;·&nbsp;
          Budget : <?= htmlspecialchars($dem['budget'] ?? '—') ?><br/>
          Reçu le : <?= htmlspecialchars($dem['date_demande']) ?>
        </div>
        <div class="body"><?= nl2br(htmlspecialchars($dem['description'])) ?></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>

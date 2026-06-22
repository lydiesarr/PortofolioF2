<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifier_session_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: liste.php'); exit; }

$stmt = $pdo->prepare('SELECT * FROM messages_contact WHERE id = :id');
$stmt->execute([':id' => $id]);
$msg = $stmt->fetch();
if (!$msg) { header('Location: liste.php'); exit; }

// Marquer comme lu
if ($msg['lu'] == 0) {
    $pdo->prepare('UPDATE messages_contact SET lu = 1 WHERE id = :id')->execute([':id' => $id]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Message</title>
  <link rel="stylesheet" href="/PortofolioF2/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Message de <?= htmlspecialchars($msg['nom']) ?></h1>
      <a href="/PortofolioF2/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <div class="actions">
        <a href="liste.php" class="btn btn-secondary">← Retour</a>
      </div>
      <div class="msg-detail">
        <div class="meta">
          De : <strong><?= htmlspecialchars($msg['nom']) ?></strong> —
          <a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a><br/>
          Reçu le : <?= htmlspecialchars($msg['date_envoi']) ?>
        </div>
        <div class="body"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>

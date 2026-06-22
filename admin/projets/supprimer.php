<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifier_session_admin();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: liste.php'); exit; }

$stmt = $pdo->prepare('SELECT * FROM projets WHERE id = :id');
$stmt->execute([':id' => $id]);
$projet = $stmt->fetch();
if (!$projet) { header('Location: liste.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Token CSRF invalide.');
    }

    if ($projet['image'] && file_exists('../../images/projets/' . $projet['image'])) {
        unlink('../../images/projets/' . $projet['image']);
    }

    $stmt = $pdo->prepare('DELETE FROM projets WHERE id = :id');
    $stmt->execute([':id' => $id]);
    header('Location: liste.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Supprimer un projet</title>
  <link rel="stylesheet" href="/portfolio/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Supprimer un projet</h1>
      <a href="/portfolio/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <div class="form-block">
        <p style="margin-bottom:20px;">Confirmes-tu la suppression du projet <strong><?= htmlspecialchars($projet['titre']) ?></strong> ? Cette action est irréversible.</p>
        <form method="POST" action="supprimer.php?id=<?= $id ?>">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"/>
          <div style="display:flex;gap:12px;">
            <button type="submit" class="btn btn-danger">Oui, supprimer</button>
            <a href="liste.php" class="btn btn-secondary">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>

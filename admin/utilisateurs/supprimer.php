<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifier_session_admin();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: liste.php'); exit; }

// Un admin ne peut pas supprimer son propre compte
if ($id === (int)$_SESSION['admin_id']) {
    header('Location: liste.php?error=self');
    exit;
}

// Seul le super admin (id=2) peut supprimer
if ((int)$_SESSION['admin_id'] !== 2) {
    header('Location: liste.php?error=forbidden');
    exit;
}

// Le compte id=2 est protégé contre toute suppression
if ($id === 2) {
    header('Location: liste.php?error=protected');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM administrateurs WHERE id = :id');
$stmt->execute([':id' => $id]);
$admin = $stmt->fetch();
if (!$admin) { header('Location: liste.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Token CSRF invalide.');
    }
    if ((int)$_POST['confirm_id'] !== $id) {
        header('Location: liste.php');
        exit;
    }
    $stmt = $pdo->prepare('DELETE FROM administrateurs WHERE id = :id');
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
  <title>Admin · Supprimer un administrateur</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Supprimer un administrateur</h1>
      <a href="<?= BASE_URL ?>/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <div class="form-block">
        <p style="margin-bottom:20px;">Confirmes-tu la suppression du compte de <strong><?= htmlspecialchars($admin['prenom'] . ' ' . $admin['nom']) ?></strong> ?</p>
        <form method="POST" action="supprimer.php?id=<?= $id ?>">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"/>
          <input type="hidden" name="confirm_id" value="<?= $id ?>"/>
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

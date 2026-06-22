<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifier_session_admin();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: liste.php'); exit; }

// Seul le super admin (id=2) peut modifier les autres comptes
if ((int)$_SESSION['admin_id'] !== 2 && $id !== (int)$_SESSION['admin_id']) {
    header('Location: liste.php?error=forbidden');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM administrateurs WHERE id = :id');
$stmt->execute([':id' => $id]);
$admin = $stmt->fetch();
if (!$admin) { header('Location: liste.php'); exit; }

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Token CSRF invalide.');
    }

    $prenom = trim($_POST['prenom'] ?? '');
    $nom    = trim($_POST['nom'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $mdp    = $_POST['mot_de_passe'] ?? '';

    if (empty($prenom))              $erreurs[] = 'Le prénom est obligatoire.';
    if (empty($nom))                 $erreurs[] = 'Le nom est obligatoire.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = 'Email invalide.';
    if ($mdp !== '' && strlen($mdp) < 8) $erreurs[] = 'Le mot de passe doit faire au moins 8 caractères.';

    if (empty($erreurs)) {
        if ($mdp !== '') {
            $hash = password_hash($mdp, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('UPDATE administrateurs SET prenom=:prenom, nom=:nom, email=:email, mot_de_passe=:mdp WHERE id=:id');
            $stmt->execute([':prenom' => $prenom, ':nom' => $nom, ':email' => $email, ':mdp' => $hash, ':id' => $id]);
        } else {
            $stmt = $pdo->prepare('UPDATE administrateurs SET prenom=:prenom, nom=:nom, email=:email WHERE id=:id');
            $stmt->execute([':prenom' => $prenom, ':nom' => $nom, ':email' => $email, ':id' => $id]);
        }
        header('Location: liste.php?updated=1');
        exit;
    }

    $admin['prenom'] = $_POST['prenom'];
    $admin['nom']    = $_POST['nom'];
    $admin['email']  = $_POST['email'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Modifier un administrateur</title>
  <link rel="stylesheet" href="/PortofolioF2/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Modifier un administrateur</h1>
      <a href="/PortofolioF2/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <?php if (!empty($erreurs)) : ?>
        <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $erreurs)) ?></div>
      <?php endif; ?>
      <div class="form-block">
        <form method="POST" action="modifier.php?id=<?= $id ?>">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"/>
          <div class="form-group">
            <label>Prénom *</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($admin['prenom']) ?>" required/>
          </div>
          <div class="form-group">
            <label>Nom *</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($admin['nom']) ?>" required/>
          </div>
          <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required/>
          </div>
          <div class="form-group">
            <label>Nouveau mot de passe <small style="color:#888;">(laisser vide pour ne pas changer)</small></label>
            <input type="password" name="mot_de_passe"/>
          </div>
          <div style="display:flex;gap:12px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="liste.php" class="btn btn-secondary">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>

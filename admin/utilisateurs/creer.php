<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifier_session_admin();

if ((int)$_SESSION['admin_id'] !== 2) {
    header('Location: liste.php?error=forbidden');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
    if (strlen($mdp) < 8)            $erreurs[] = 'Le mot de passe doit faire au moins 8 caractères.';

    if (empty($erreurs)) {
        $hash = password_hash($mdp, PASSWORD_BCRYPT);
        try {
            $stmt = $pdo->prepare('INSERT INTO administrateurs (prenom, nom, email, mot_de_passe) VALUES (:prenom, :nom, :email, :mdp)');
            $stmt->execute([':prenom' => $prenom, ':nom' => $nom, ':email' => $email, ':mdp' => $hash]);
            header('Location: liste.php?created=1');
            exit;
        } catch (PDOException $e) {
            $erreurs[] = 'Cet email est déjà utilisé.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Nouvel administrateur</title>
  <link rel="stylesheet" href="/PortofolioF2/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Nouvel administrateur</h1>
      <a href="/PortofolioF2/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <?php if (!empty($erreurs)) : ?>
        <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $erreurs)) ?></div>
      <?php endif; ?>
      <div class="form-block">
        <form method="POST" action="creer.php">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"/>
          <div class="form-group">
            <label>Prénom *</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" required/>
          </div>
          <div class="form-group">
            <label>Nom *</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required/>
          </div>
          <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>
          </div>
          <div class="form-group">
            <label>Mot de passe * <small style="color:#888;">(min. 8 caractères)</small></label>
            <input type="password" name="mot_de_passe" required/>
          </div>
          <div style="display:flex;gap:12px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">Créer</button>
            <a href="liste.php" class="btn btn-secondary">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>

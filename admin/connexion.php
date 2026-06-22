<?php
require_once '../config/connexion.php';
session_start();

if (!empty($_SESSION['admin_id'])) {
    header('Location: /portfolio/admin/dashboard.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Token CSRF invalide.');
    }

    $email = trim($_POST['email'] ?? '');
    $mdp   = $_POST['mot_de_passe'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM administrateurs WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($mdp, $admin['mot_de_passe'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id']     = $admin['id'];
        $_SESSION['admin_prenom'] = $admin['prenom'];
        header('Location: /portfolio/admin/dashboard.php');
        exit;
    } else {
        $erreur = 'Identifiants incorrects.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Administration · Connexion</title>
  <link rel="stylesheet" href="/portfolio/admin/admin.css"/>
  <style>
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:#f0f2f5; }
    .login-box { background:#fff; border-radius:16px; padding:48px; width:100%; max-width:400px; box-shadow:0 4px 24px rgba(0,0,0,.1); }
    .login-box h1 { font-size:1.4rem; margin-bottom:8px; }
    .login-box p { color:#888; font-size:.9rem; margin-bottom:28px; }
  </style>
</head>
<body>
  <div class="login-box">
    <h1>Espace administration</h1>
    <p>Portfolio · Aissatou Lydi Sarr</p>
    <?php if ($erreur) : ?>
      <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    <form method="POST" action="/portfolio/admin/connexion.php">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"/>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required autofocus/>
      </div>
      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="mot_de_passe" required/>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Se connecter</button>
    </form>
  </div>
</body>
</html>

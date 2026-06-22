<?php
require_once '../../config/connexion.php';
require_once '../../fonctions.php';
verifier_session_admin();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$erreurs = [];
$succes  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Token CSRF invalide.');
    }

    $titre       = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $techs       = trim($_POST['technologies'] ?? '');
    $lien        = trim($_POST['lien'] ?? '') ?: null;
    $image       = null;

    if (empty($titre))       $erreurs[] = 'Le titre est obligatoire.';
    if (empty($description)) $erreurs[] = 'La description est obligatoire.';
    if (empty($techs))       $erreurs[] = 'Les technologies sont obligatoires.';

    if (!empty($_FILES['image']['name'])) {
        $ext_ok = ['jpg','jpeg','png','webp','gif'];
        $ext    = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $ext_ok)) {
            $erreurs[] = 'Format d\'image non autorisé (jpg, jpeg, png, webp, gif).';
        } else {
            $nom_fichier = uniqid('proj_', true) . '.' . $ext;
            $dest        = '../../images/projets/' . $nom_fichier;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image = $nom_fichier;
            } else {
                $erreurs[] = 'Erreur lors de l\'upload de l\'image.';
            }
        }
    }

    if (empty($erreurs)) {
        $stmt = $pdo->prepare('INSERT INTO projets (titre, description, technologies, lien, image) VALUES (:titre, :description, :technologies, :lien, :image)');
        $stmt->execute([
            ':titre'        => htmlspecialchars($titre),
            ':description'  => htmlspecialchars($description),
            ':technologies' => htmlspecialchars($techs),
            ':lien'         => $lien ? htmlspecialchars($lien) : null,
            ':image'        => $image,
        ]);
        header('Location: liste.php?created=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Créer un projet</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Nouveau projet</h1>
      <a href="<?= BASE_URL ?>/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <?php if (!empty($erreurs)) : ?>
        <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $erreurs)) ?></div>
      <?php endif; ?>
      <div class="form-block">
        <form method="POST" action="creer.php" enctype="multipart/form-data">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"/>
          <div class="form-group">
            <label>Titre *</label>
            <input type="text" name="titre" value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>" required/>
          </div>
          <div class="form-group">
            <label>Description *</label>
            <textarea name="description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
          </div>
          <div class="form-group">
            <label>Technologies * <small style="color:#888;">(ex: HTML, CSS, PHP)</small></label>
            <input type="text" name="technologies" value="<?= htmlspecialchars($_POST['technologies'] ?? '') ?>" required/>
          </div>
          <div class="form-group">
            <label>Lien externe</label>
            <input type="url" name="lien" value="<?= htmlspecialchars($_POST['lien'] ?? '') ?>" placeholder="https://..."/>
          </div>
          <div class="form-group">
            <label>Image <small style="color:#888;">(jpg, jpeg, png, webp, gif)</small></label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif"/>
          </div>
          <div style="display:flex;gap:12px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">Créer le projet</button>
            <a href="liste.php" class="btn btn-secondary">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>

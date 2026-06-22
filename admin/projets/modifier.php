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

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Token CSRF invalide.');
    }

    $titre       = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $techs       = trim($_POST['technologies'] ?? '');
    $lien        = trim($_POST['lien'] ?? '') ?: null;
    $image       = $projet['image'];

    if (empty($titre))       $erreurs[] = 'Le titre est obligatoire.';
    if (empty($description)) $erreurs[] = 'La description est obligatoire.';
    if (empty($techs))       $erreurs[] = 'Les technologies sont obligatoires.';

    if (!empty($_FILES['image']['name'])) {
        $ext_ok = ['jpg','jpeg','png','webp','gif'];
        $ext    = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $ext_ok)) {
            $erreurs[] = 'Format d\'image non autorisé.';
        } else {
            $nom_fichier = uniqid('proj_', true) . '.' . $ext;
            $dest        = '../../images/projets/' . $nom_fichier;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                if ($projet['image'] && file_exists('../../images/projets/' . $projet['image'])) {
                    unlink('../../images/projets/' . $projet['image']);
                }
                $image = $nom_fichier;
            } else {
                $erreurs[] = 'Erreur lors de l\'upload de l\'image.';
            }
        }
    }

    if (empty($erreurs)) {
        $stmt = $pdo->prepare('UPDATE projets SET titre=:titre, description=:description, technologies=:technologies, lien=:lien, image=:image WHERE id=:id');
        $stmt->execute([
            ':titre'        => htmlspecialchars($titre),
            ':description'  => htmlspecialchars($description),
            ':technologies' => htmlspecialchars($techs),
            ':lien'         => $lien ? htmlspecialchars($lien) : null,
            ':image'        => $image,
            ':id'           => $id,
        ]);
        header('Location: liste.php?updated=1');
        exit;
    }

    $projet['titre']        = $_POST['titre'];
    $projet['description']  = $_POST['description'];
    $projet['technologies'] = $_POST['technologies'];
    $projet['lien']         = $_POST['lien'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin · Modifier un projet</title>
  <link rel="stylesheet" href="/PortofolioF2/admin/admin.css"/>
</head>
<body>
<div class="admin-wrapper">
  <?php require '../_sidebar.php'; ?>
  <div class="main">
    <div class="topbar">
      <h1>Modifier le projet</h1>
      <a href="/PortofolioF2/admin/deconnexion.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
      <?php if (!empty($erreurs)) : ?>
        <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $erreurs)) ?></div>
      <?php endif; ?>
      <div class="form-block">
        <form method="POST" action="modifier.php?id=<?= $id ?>" enctype="multipart/form-data">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"/>
          <div class="form-group">
            <label>Titre *</label>
            <input type="text" name="titre" value="<?= htmlspecialchars($projet['titre']) ?>" required/>
          </div>
          <div class="form-group">
            <label>Description *</label>
            <textarea name="description" required><?= htmlspecialchars($projet['description']) ?></textarea>
          </div>
          <div class="form-group">
            <label>Technologies *</label>
            <input type="text" name="technologies" value="<?= htmlspecialchars($projet['technologies']) ?>" required/>
          </div>
          <div class="form-group">
            <label>Lien externe</label>
            <input type="url" name="lien" value="<?= htmlspecialchars($projet['lien'] ?? '') ?>" placeholder="https://..."/>
          </div>
          <div class="form-group">
            <label>Image <small style="color:#888;">(laisser vide pour conserver l'actuelle)</small></label>
            <?php if ($projet['image']) : ?>
              <img src="/images/projets/<?= htmlspecialchars($projet['image']) ?>" style="max-height:80px;border-radius:6px;margin-bottom:8px;display:block;"/>
            <?php endif; ?>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif"/>
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

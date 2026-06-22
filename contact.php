<?php
require_once 'config/connexion.php';
require_once 'fonctions.php';
enregistrer_visite($pdo, 'contact');
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$erreurs_contact = [];
$succes_contact  = false;
$nom_c    = '';
$email_c  = '';
$message_c = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_contact'])) {
  if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
      die('Token CSRF invalide.');
  }

  $nom_c     = nettoyer($_POST['nom'] ?? '');
  $email_c   = nettoyer($_POST['email'] ?? '');
  $message_c = nettoyer($_POST['message'] ?? '');

  if (!champ_requis($nom_c))      $erreurs_contact[] = 'Le nom est obligatoire.';
  if (!email_valide($email_c))    $erreurs_contact[] = 'L\'adresse e-mail est invalide.';
  if (!champ_requis($message_c))  $erreurs_contact[] = 'Le message ne peut pas être vide.';

  if (empty($erreurs_contact)) {
      $stmt = $pdo->prepare('INSERT INTO messages_contact (nom, email, message) VALUES (:nom, :email, :message)');
      $stmt->execute([':nom' => $nom_c, ':email' => $email_c, ':message' => $message_c]);
      $succes_contact = true;
      $nom_c = $email_c = $message_c = '';
  }
}

$erreurs_projet = [];
$succes_projet  = false;
$demande        = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_projet'])) {
  if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
      die('Token CSRF invalide.');
  }

  $demande = [
    'nom'         => nettoyer($_POST['nom_p'] ?? ''),
    'email'       => nettoyer($_POST['email_p'] ?? ''),
    'type_projet' => nettoyer($_POST['type'] ?? ''),
    'budget'      => nettoyer($_POST['budget'] ?? ''),
    'delai'       => nettoyer($_POST['delai'] ?? ''),
    'description' => nettoyer($_POST['description'] ?? ''),
  ];

  if (!champ_requis($demande['nom']))         $erreurs_projet[] = 'Le nom est obligatoire.';
  if (!email_valide($demande['email']))        $erreurs_projet[] = 'L\'adresse e-mail est invalide.';
  if (!champ_requis($demande['description'])) $erreurs_projet[] = 'La description est obligatoire.';

  if (empty($erreurs_projet)) {
    $stmt = $pdo->prepare('INSERT INTO demandes_projet (nom, email, type_projet, budget, description) VALUES (:nom, :email, :type_projet, :budget, :description)');
    $stmt->execute([
      ':nom'         => $demande['nom'],
      ':email'       => $demande['email'],
      ':type_projet' => $demande['type_projet'],
      ':budget'      => $demande['budget'],
      ':description' => $demande['description'],
    ]);
    $succes_projet = true;
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portfolio · Contact</title>
  <link rel="stylesheet" href="style.css"/>
  <script src="theme.js"></script>
</head>
<body>

  <?php require 'composants/navigation.php'; ?>

  <div class="page" style="justify-content:flex-start;">
    <div class="section-label fade-up">Contact</div>
    <h2 class="fade-up d1">Travaillons ensemble</h2>
    <p class="fade-up d2" style="color:var(--muted); margin-bottom:40px; max-width:500px;">
      Tu as un projet, une question ou une opportunité de stage ? Écris-moi.
    </p>

    <?php if ($succes_contact) : ?>
      <div class="msg-succes fade-up">✅ Message envoyé avec succès ! Je te répondrai très bientôt.</div>
    <?php endif; ?>
    <?php if (!empty($erreurs_contact)) : ?>
      <div class="msg-erreur fade-up">
        <strong>Corrige les erreurs suivantes :</strong>
        <ul>
          <?php foreach ($erreurs_contact as $e) : ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="contact-grid fade-up d3">
      <form class="contact-form" method="POST" action="contact.php">
        <input type="hidden" name="form_contact" value="1"/>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"/>
        <div class="form-group">
          <label>Nom</label>
          <input type="text" name="nom" placeholder="Victor Diagne" value="<?= htmlspecialchars($nom_c) ?>" required/>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="vito@mail.com" value="<?= htmlspecialchars($email_c) ?>" required/>
        </div>
        <div class="form-group">
          <label>Message</label>
          <textarea name="message" placeholder="Bonjour, je voulais vous contacter pour..." required><?= htmlspecialchars($message_c) ?></textarea>
        </div>
        <button type="submit" class="btn">Envoyer le message</button>
      </form>

      <div class="contact-info">
        <div class="contact-item">
          <div class="ci-label">Email</div>
          <div class="ci-value"><a href="mailto:aissatoulydie001@gmail.com">aissatoulydie001@gmail.com</a></div>
        </div>
        <div class="contact-item">
          <div class="ci-label">LinkedIn</div>
          <div class="ci-value">Lydie Sarr</div>
        </div>
        <div class="contact-item">
          <div class="ci-label">Instagram</div>
          <div class="ci-value">
            <a href="https://www.instagram.com/lydieeeee___s/" target="_blank" rel="noopener">Lydie Sarr</a>
          </div>
        </div>
        <div class="contact-item">
          <div class="ci-label">Twitter</div>
          <div class="ci-value">Alysa04</div>
        </div>
        <div class="contact-item">
          <div class="ci-label">Téléphone</div>
          <div class="ci-value"><a href="tel:+221776736976">77 673 69 76</a></div>
        </div>
      </div>
    </div>
  </div>

  <div style="max-width:1100px; margin:0 auto; padding:0 60px 80px;">
    <div class="section-label fade-up">Demande de projet</div>
    <h2 class="fade-up d1" style="font-size:clamp(1.5rem, 3vw, 2.2rem); margin-bottom:16px;">Tu as un projet en tête ?</h2>
    <p class="fade-up d2" style="color:var(--muted); margin-bottom:36px; max-width:500px;">
      Décris-moi ton projet et je reviendrai vers toi avec une proposition adaptée.
    </p>

    <?php if ($succes_projet) : ?>
      <div class="recap fade-up">
        <h3>✅ Demande reçue — Récapitulatif</h3>
        <div class="recap-item"><span>Nom</span><?= htmlspecialchars($demande['nom']) ?></div>
        <div class="recap-item"><span>Email</span><?= htmlspecialchars($demande['email']) ?></div>
        <div class="recap-item"><span>Type de projet</span><?= htmlspecialchars($demande['type_projet']) ?></div>
        <div class="recap-item"><span>Budget</span><?= htmlspecialchars($demande['budget']) ?></div>
        <div class="recap-item"><span>Délai</span><?= htmlspecialchars($demande['delai']) ?></div>
        <div class="recap-item"><span>Description</span><?= htmlspecialchars($demande['description']) ?></div>
      </div>
    <?php endif; ?>

    <?php if (!empty($erreurs_projet)) : ?>
      <div class="msg-erreur fade-up">
        <strong>Corrige les erreurs suivantes :</strong>
        <ul>
          <?php foreach ($erreurs_projet as $e) : ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (!$succes_projet) : ?>
    <form class="contact-form fade-up d3" method="POST" action="contact.php" style="max-width:680px;">
      <input type="hidden" name="form_projet" value="1"/>
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"/>
      <div class="form-group">
        <label>Nom complet</label>
        <input type="text" name="nom_p" placeholder="Ton nom" value="<?= htmlspecialchars($demande['nom'] ?? '') ?>" required/>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email_p" placeholder="ton@email.com" value="<?= htmlspecialchars($demande['email'] ?? '') ?>" required/>
      </div>
      <div class="form-group">
        <label>Type de projet</label>
        <select name="type">
          <option value="">-- Choisir un type --</option>
          <option <?= ($demande['type_projet'] ?? '') === 'Site web' ? 'selected' : '' ?>>Site web</option>
          <option <?= ($demande['type_projet'] ?? '') === 'Application web' ? 'selected' : '' ?>>Application web</option>
          <option <?= ($demande['type_projet'] ?? '') === 'Administration réseau' ? 'selected' : '' ?>>Administration réseau</option>
          <option <?= ($demande['type_projet'] ?? '') === 'Base de données' ? 'selected' : '' ?>>Base de données</option>
          <option <?= ($demande['type_projet'] ?? '') === 'Autre' ? 'selected' : '' ?>>Autre</option>
        </select>
      </div>
      <div class="form-group">
        <label>Budget estimé</label>
        <select name="budget">
          <option value="">-- Choisir un budget --</option>
          <option <?= ($demande['budget'] ?? '') === 'Moins de 100 000 FCFA' ? 'selected' : '' ?>>Moins de 100 000 FCFA</option>
          <option <?= ($demande['budget'] ?? '') === '100 000 - 300 000 FCFA' ? 'selected' : '' ?>>100 000 - 300 000 FCFA</option>
          <option <?= ($demande['budget'] ?? '') === '300 000 - 500 000 FCFA' ? 'selected' : '' ?>>300 000 - 500 000 FCFA</option>
          <option <?= ($demande['budget'] ?? '') === 'Plus de 500 000 FCFA' ? 'selected' : '' ?>>Plus de 500 000 FCFA</option>
          <option <?= ($demande['budget'] ?? '') === 'À discuter' ? 'selected' : '' ?>>À discuter</option>
        </select>
      </div>
      <div class="form-group">
        <label>Délai souhaité</label>
        <input type="text" name="delai" placeholder="Ex : 2 semaines, 1 mois..." value="<?= htmlspecialchars($demande['delai'] ?? '') ?>"/>
      </div>
      <div class="form-group">
        <label>Description du projet</label>
        <textarea name="description" placeholder="Décris ton projet en détail..." style="min-height:160px;" required><?= htmlspecialchars($demande['description'] ?? '') ?></textarea>
      </div>
      <button type="submit" class="btn">Envoyer ma demande</button>
    </form>
    <?php endif; ?>
  </div>

  <?php require 'composants/pied-de-page.php'; ?>

</body>
</html>

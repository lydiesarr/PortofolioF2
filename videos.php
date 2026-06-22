<?php
require_once 'config/connexion.php';
require_once 'fonctions.php';
enregistrer_visite($pdo, 'videos');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portfolio · Vidéo</title>
  <link rel="stylesheet" href="style.css"/>
  <script src="theme.js"></script>
</head>
<body>

  <?php require 'composants/navigation.php'; ?>

  <div class="page" style="justify-content:flex-start;">
    <div class="section-label fade-up">Présentation</div>
    <h2 class="fade-up d1">Ma vidéo de présentation</h2>
    <p class="fade-up d2" style="color:var(--muted); max-width:560px;">
      Découvrez qui je suis, ce que je fais et ce qui me passionne à travers cette courte présentation.
    </p>

    <div class="video-wrapper fade-up d3">
       <video src="videos/encore moi.mp4" controls poster="videos/encore moi.mp4"></video>
    </div>
  </div>

  <?php require 'composants/pied-de-page.php'; ?>

</body>
</html>

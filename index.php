<?php
require_once 'config/connexion.php';
require_once 'fonctions.php';
enregistrer_visite($pdo, 'accueil');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portfolio · Accueil</title>
  <link rel="stylesheet" href="style.css"/>
  <script src="theme.js"></script>
</head>
<body>

  <?php require 'composants/navigation.php'; ?>

  <div class="page">
    <div class="section-label fade-up">Étudiante · Génie Logiciel & Administration Réseaux</div>
    <h1 class="fade-up d1">
      Bonjour, je suis<br/>
      <span>Aissatou Lydi SARR</span>
    </h1>
    <p class="fade-up d2" style="color:var(--muted); font-size:1.1rem; max-width:520px; margin-bottom:40px;">
      Étudiante en 2ème année de Génie Logiciel et Administration Réseau.
      Je construis des solutions propres, efficaces et bien structurées.
      Derrière chaque interface se cache une architecture complexe.
      Mon ambition ? Maîtriser l'intégralité de la chaîne numérique
      pour transformer des idées complexes en réalités stables et scalables.
    </p>
    <div class="fade-up d3">
      <a href="projets.php" class="btn">Voir mes projets</a>
      <a href="contact.php" class="btn btn-outline">Me contacter</a>
    </div>

    <div class="stats-row fade-up d4">
      <div>
        <div class="stat-val">2A</div>
        <div class="stat-label">Niveau actuel</div>
      </div>
      <div>
        <div class="stat-val">4+</div>
        <div class="stat-label">Projets réalisés</div>
      </div>
      <div>
        <div class="stat-val">2</div>
        <div class="stat-label">Spécialités</div>
      </div>
    </div>
  </div>

  <?php require 'composants/pied-de-page.php'; ?>

</body>
</html>

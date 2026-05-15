<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portfolio · À propos</title>
  <link rel="stylesheet" href="style.css"/>
  <script src="theme.js"></script>
</head>
<body>

  <?php require 'composants/navigation.php'; ?>

  <div class="page" style="justify-content:flex-start;">
    <div class="section-label fade-up">À propos</div>
    <h2 class="fade-up d1">Qui suis-je ?</h2>

    <div class="about-grid fade-up d2">
      <div class="about-text">
        <p>
          Je suis étudiante en <strong>2ème année de Génie Logiciel et Administration Réseau</strong>.
          Passionnée par le développement logiciel et les infrastructures réseaux.
        </p>
        <p>
          On me demande souvent pourquoi j'ai choisi de coupler le Génie Logiciel et l'Administration Réseaux.
          La réponse tient en un mot : curiosité. Je ne me contente pas de coder une application ;
          j'ai besoin de comprendre l'infrastructure qui la propulse. Cette envie de lever le capot
          et de comprendre chaque rouage me permet de bâtir des solutions cohérentes,
          où le code et le système dialoguent parfaitement.
        </p>

        <div class="skills-block">
          <h3>Développement</h3>
          <div class="tags">
            <span class="tag">Python</span>
            <span class="tag">Java</span>
            <span class="tag">HTML / CSS</span>
            <span class="tag">JavaScript</span>
            <span class="tag">SQL</span>
          </div>
        </div>

        <div class="skills-block">
          <h3>Réseaux & Systèmes</h3>
          <div class="tags">
            <span class="tag">Linux</span>
            <span class="tag">Cisco Packet Tracer</span>
            <span class="tag">TCP/IP</span>
            <span class="tag">Huawei</span>
            <span class="tag">DNS / DHCP</span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="info-item">
          <div class="info-label">Formation</div>
          <div class="info-value">Génie Logiciel & Administration Réseau</div>
        </div>
        <div class="info-item">
          <div class="info-label">Niveau</div>
          <div class="info-value">2ème Année</div>
        </div>
        <div class="info-item">
          <div class="info-label">Localisation</div>
          <div class="info-value">Dakar, Sénégal</div>
        </div>
        <div class="info-item">
          <div class="info-label">Disponibilité</div>
          <div class="info-value">Stage / Projets collaboratifs</div>
        </div>
        <div class="info-item">
          <div class="info-label">Langues</div>
          <div class="info-value">Français · Anglais · Wolof · Espagnol</div>
        </div>
      </div>
    </div>
  </div>

  <?php require 'composants/pied-de-page.php'; ?>

</body>
</html>

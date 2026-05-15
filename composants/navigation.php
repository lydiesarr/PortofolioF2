<?php
$page_courante = basename($_SERVER['PHP_SELF']);
?>
<nav>
  <a href="index.php" class="logo">Lydi</a>
  <ul>
    <li><a href="index.php" <?php if($page_courante === 'index.php') echo 'class="active"'; ?>>Accueil</a></li>
    <li><a href="about.php" <?php if($page_courante === 'about.php') echo 'class="active"'; ?>>À propos</a></li>
    <li><a href="videos.php" <?php if($page_courante === 'videos.php') echo 'class="active"'; ?>>Vidéo</a></li>
    <li><a href="projets.php" <?php if($page_courante === 'projets.php') echo 'class="active"'; ?>>Projets</a></li>
    <li><a href="contact.php" <?php if($page_courante === 'contact.php') echo 'class="active"'; ?>>Contact</a></li>
  </ul>
  <div class="nav-right">
    <button class="theme-toggle" id="themeToggle" aria-label="Changer le thème">
      <span class="icon-moon">🌙</span>
      <span class="icon-sun">☀️</span>
      <span class="knob"></span>
    </button>
    <button class="button">
      <a href="fichiers/CV — Aissatou Lydi Sarr.pdf" class="CV-button" download>Curriculum Vitae</a>
    </button>
  </div>
</nav>

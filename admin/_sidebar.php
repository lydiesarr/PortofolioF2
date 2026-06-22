<?php
$nb_msg = $pdo->query('SELECT COUNT(*) FROM messages_contact WHERE lu = 0')->fetchColumn();
$nb_dem = $pdo->query('SELECT COUNT(*) FROM demandes_projet WHERE lu = 0')->fetchColumn();
$page_active = basename($_SERVER['PHP_SELF']);
$dir_active  = basename(dirname($_SERVER['PHP_SELF']));
?>
<aside class="sidebar">
  <div class="logo">Admin · Lydi</div>
  <nav>
    <a href="<?= BASE_URL ?>/admin/dashboard.php" class="<?= $page_active === 'dashboard.php' ? 'active' : '' ?>">Tableau de bord</a>
    <a href="<?= BASE_URL ?>/admin/projets/liste.php" class="<?= $dir_active === 'projets' ? 'active' : '' ?>">Projets</a>
    <a href="<?= BASE_URL ?>/admin/utilisateurs/liste.php" class="<?= $dir_active === 'utilisateurs' ? 'active' : '' ?>">Administrateurs</a>
    <a href="<?= BASE_URL ?>/admin/messages/liste.php" class="<?= $dir_active === 'messages' ? 'active' : '' ?>">
      Messages <?php if ($nb_msg > 0) : ?><span class="badge"><?= (int)$nb_msg ?></span><?php endif; ?>
    </a>
    <a href="<?= BASE_URL ?>/admin/demandes/liste.php" class="<?= $dir_active === 'demandes' ? 'active' : '' ?>">
      Demandes <?php if ($nb_dem > 0) : ?><span class="badge"><?= (int)$nb_dem ?></span><?php endif; ?>
    </a>
  </nav>
  <div class="admin-name">Connecté : <?= htmlspecialchars($_SESSION['admin_prenom']) ?></div>
</aside>

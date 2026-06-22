<?php
session_start();
session_destroy();
header('Location: /portfolio/admin/connexion.php');
exit;

<?php
session_start();
session_destroy();
header('Location: /PortofolioF2/admin/connexion.php');
exit;

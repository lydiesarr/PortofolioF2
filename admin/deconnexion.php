<?php
require_once '../config/connexion.php';
session_start();
session_destroy();
header('Location: ' . BASE_URL . '/admin/connexion.php');
exit;

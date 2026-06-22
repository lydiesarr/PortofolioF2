<?php
require_once 'config/connexion.php';

$prenom = 'Lydi';
$nom = 'Sarr';
$email = 'aissatoulydie001@gmail.com';
$mot_de_passe = 'passer123';
$hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

$stmt = $pdo->prepare('INSERT INTO administrateurs (prenom, nom, email, mot_de_passe) VALUES (:prenom, :nom, :email, :mot_de_passe)');
$stmt->execute([
    ':prenom' => $prenom,
    ':nom' => $nom,
    ':email' => $email,
    ':mot_de_passe' => $hash
]);

echo 'Compte créé !<br>';
echo 'Email : ' . $email . '<br>';
echo 'Mot de passe : ' . $mot_de_passe . '<br>';
echo '<strong>Supprime ce fichier maintenant !</strong>';
?>

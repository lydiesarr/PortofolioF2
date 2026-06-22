<?php
function champ_requis(string $valeur): bool {
    return !empty(trim($valeur));
}

function nettoyer(string $valeur): string {
    return htmlspecialchars(trim($valeur));
}

function email_valide(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function enregistrer_visite(PDO $pdo, string $page): void {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
    try {
        $stmt = $pdo->prepare('INSERT INTO visites (adresse_ip, page) VALUES (:adresse_ip, :page)');
        $stmt->execute([':adresse_ip' => $ip, ':page' => $page]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

function verifier_session_admin(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['admin_id'])) {
        header('Location: /portfolio/admin/connexion.php');
        exit;
    }
}
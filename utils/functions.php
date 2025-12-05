<?php
// Vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Vérifier si l'utilisateur est admin
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

// Rediriger si pas connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php?action=login');
        exit;
    }
}

// Générer un hash de mot de passe (pour tester)
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}
?>
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
// Dans utils/functions.php
function requireAdmin() {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?action=login');
        exit;
    }
    
    // Récupérer l'objet User depuis la session
    $userArray = $_SESSION['user'];
    $user = User::fromArray($userArray);
    
    if (!$user->isAdmin()) {
        die("Accès refusé : vous devez être administrateur");
    }
}
?>

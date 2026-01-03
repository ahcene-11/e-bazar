<?php
function isLoggedIn() {
    return isset($_SESSION['user']);
}
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php?action=loginForm');
        exit;
    }
}
function requireAdmin() {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?action=loginForm');
        exit;
    }
    $userArray = $_SESSION['user'];
    $user = User::fromArray($userArray);
    
    if (!$user->isAdmin()) {
        die("Accès refusé : vous devez être administrateur");
    }
}
?>

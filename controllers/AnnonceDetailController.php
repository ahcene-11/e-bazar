<?php
require_once 'models/AnnonceModel.php';

/**
 * Afficher le détail d'une annonce
 */
function showAnnonceDetail() {
    global $pdo;
    $annonceId = $_GET['id'] ?? 0;
    
    $annonceModel = new AnnonceModel($pdo);
    $annonce = $annonceModel->getById($annonceId);
    
    if (!$annonce) {
        $_SESSION['error'] = "Annonce introuvable";
        header('Location: index.php');
        exit;
    }
    
    include 'views/annonce_detail.php';
}

/**
 * Traiter la création d'une annonce (Phase 4)
 */
function doCreateAnnonce($pdo) {
    // On verra ça en Phase 4
}

/**
 * Traiter la suppression d'une annonce
 */
function doDeleteAnnonce() {
    global $pdo;
    // Vérifier que l'utilisateur est connecté
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Vous devez être connecté";
        header('Location: index.php?action=login');
        exit;
    }
    
    $annonceId = $_POST['annonce_id'] ?? 0;
    $userId = $_SESSION['user']['id'];
    
    $annonceModel = new AnnonceModel($pdo);
    $annonce = $annonceModel->getById($annonceId);
    
    // Vérifier que l'annonce existe
    if (!$annonce) {
        $_SESSION['error'] = "Annonce introuvable";
        header('Location: index.php');
        exit;
    }
    
    // Vérifier que c'est bien l'utilisateur propriétaire ou un admin
    if ($annonce['user_id'] != $userId && $_SESSION['user']['role'] != 'admin') {
        $_SESSION['error'] = "Vous n'êtes pas autorisé à supprimer cette annonce";
        header('Location: index.php?action=detail&id=' . $annonceId);
        exit;
    }
    
    // Supprimer l'annonce
    if ($annonceModel->delete($annonceId)) {
        $_SESSION['success'] = "Annonce supprimée avec succès";
        header('Location: index.php?action=dashboard');
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression";
        header('Location: index.php?action=detail&id=' . $annonceId);
    }
    exit;
}
?>
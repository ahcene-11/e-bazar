<?php
require_once 'models/CategoryModel.php';
require_once 'models/AnnonceModel.php';

/**
 * Afficher la page d'accueil
 */
function showHome() {
    global $pdo;
    
    // Instancier les modèles
    $categoryModel = new CategoryModel($pdo);
    $annonceModel = new AnnonceModel($pdo);
    
    // Récupérer les données
    $categories = $categoryModel->getAllWithCount();
    $recentAnnonces = $annonceModel->getRecent(4);
    
    // Afficher la vue (les variables sont disponibles dans home.php)
    include 'views/home.php';
}
?>
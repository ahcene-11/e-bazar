<?php
require_once 'models/CategoryModel.php';
require_once 'models/AnnonceModel.php';

function showHome() {
    global $pdo;
    $categoryModel = new CategoryModel($pdo);
    $annonceModel = new AnnonceModel($pdo);
    $categories = $categoryModel->getAllWithCount();
    $recentAnnonces = $annonceModel->getRecent(4);
    include 'views/home.php';
}
?>
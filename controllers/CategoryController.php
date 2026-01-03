<?php
require_once 'models/CategoryModel.php';
require_once 'models/AnnonceModel.php';

function categoryList() {
    global $pdo;
    $categoryId = $_GET['id'] ?? 0;
    $page = $_GET['page'] ?? 1;
    
    $categoryModel = new CategoryModel($pdo);
    $annonceModel = new AnnonceModel($pdo);
    
    $category = $categoryModel->getById($categoryId);
    if (!$category) {
        $_SESSION['error'] = "Catégorie introuvable";
        header('Location: index.php');
        exit;
    }
    
    $perPage = 10;
    $annonces = $annonceModel->getByCategory($categoryId, $page, $perPage);
    $totalAnnonces = $annonceModel->countByCategory($categoryId);
    $totalPages = ceil($totalAnnonces / $perPage);
    $currentPage = $page;
    
    include 'views/category_list.php';
}
?>
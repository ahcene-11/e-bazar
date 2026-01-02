<?php
require_once 'models/UserModel.php';
require_once 'models/CategoryModel.php';
require_once 'models/AnnonceModel.php';
require_once 'models/PhotoModel.php';
require_once 'utils/upload.php';

/**
 * Afficher le dashboard admin
 */
function showAdminDashboard($pdo) {
    // Vérifier que l'utilisateur est admin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "Accès refusé : vous devez être administrateur";
        header('Location: index.php');
        exit;
    }
    
    $userModel = new UserModel($pdo);
    $categoryModel = new CategoryModel($pdo);
    $annonceModel = new AnnonceModel($pdo);
    $photoModel = new PhotoModel($pdo);
    
    // Récupérer tous les utilisateurs (sauf l'admin lui-même)
    $users = $userModel->getAll();
    
    // Récupérer toutes les catégories avec le nombre d'annonces
    $categories = $categoryModel->getAllWithCount();
    
    // Récupérer toutes les annonces (pour modération)
    // On va créer une méthode dans AnnonceModel pour ça
    // B. Annonces (Pré-chargement des photos pour éviter les requêtes dans la vue)
    $rawAnnonces = $annonceModel->getAllForAdmin();
    $allAnnonces = [];
    
    foreach ($rawAnnonces as $annonce) {
        // On récupère la photo ici, dans le contrôleur
        $photo = $photoModel->getPrimaryByAnnonce($annonce['id']);
        
        // On injecte le nom de fichier directement dans le tableau de l'annonce
        $annonce['primary_photo_filename'] = $photo ? $photo->getFilename() : null;
        
        $allAnnonces[] = $annonce;
    }
    $pageTitle = 'Administration - e-bazar';
    include 'views/admin_dashboard.php';
}

/**
 * Supprimer un utilisateur
 */
function doDeleteUser($pdo) {
    // Vérifier que l'utilisateur est admin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "Accès refusé";
        header('Location: index.php');
        exit;
    }
    
    $userId = $_POST['user_id'] ?? 0;
    
    // Empêcher l'admin de se supprimer lui-même
    if ($userId == $_SESSION['user']['id']) {
        $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte";
        header('Location: index.php?action=admin');
        exit;
    }
    
    $userModel = new UserModel($pdo);
    $user = $userModel->getById($userId);
    
    if (!$user) {
        $_SESSION['error'] = "Utilisateur introuvable";
        header('Location: index.php?action=admin');
        exit;
    }
    
    // Empêcher de supprimer un autre admin
    if ($user->getRole() === 'admin') {
        $_SESSION['error'] = "Vous ne pouvez pas supprimer un autre administrateur";
        header('Location: index.php?action=admin');
        exit;
    }
    
    // Récupérer toutes les annonces de l'utilisateur pour supprimer les photos
    $annonceModel = new AnnonceModel($pdo);
    $photoModel = new PhotoModel($pdo);
    $userAnnonces = $annonceModel->getByUser($userId);
    
    // Supprimer les photos de toutes ses annonces
    foreach ($userAnnonces as $annonce) {
        $photos = $photoModel->getByAnnonce($annonce['id']);
        deleteAllPhotos($photos);
    }
    
    // Supprimer l'utilisateur (les annonces seront supprimées par CASCADE)
    if ($userModel->delete($userId)) {
        $_SESSION['success'] = "Utilisateur supprimé avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression";
    }
    
    header('Location: index.php?action=admin');
    exit;
}

/**
 * Supprimer une annonce (par l'admin)
 */
function doDeleteAnnonceAdmin($pdo) {
    // Vérifier que l'utilisateur est admin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "Accès refusé";
        header('Location: index.php');
        exit;
    }
    
    $annonceId = $_POST['annonce_id'] ?? 0;
    
    $annonceModel = new AnnonceModel($pdo);
    $photoModel = new PhotoModel($pdo);
    
    $annonce = $annonceModel->getById($annonceId);
    
    if (!$annonce) {
        $_SESSION['error'] = "Annonce introuvable";
        header('Location: index.php?action=admin');
        exit;
    }
    
    // Récupérer et supprimer les photos du disque
    $photos = $photoModel->getByAnnonce($annonceId);
    deleteAllPhotos($photos);
    
    // Supprimer l'annonce
    if ($annonceModel->delete($annonceId)) {
        $_SESSION['success'] = "Annonce supprimée avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression";
    }
    
    header('Location: index.php?action=admin');
    exit;
}

/**
 * Créer une nouvelle catégorie
 */
function doCreateCategory($pdo) {
    // Vérifier que l'utilisateur est admin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "Accès refusé";
        header('Location: index.php');
        exit;
    }
    
    $categoryName = trim($_POST['category_name'] ?? '');
    
    // Validation
    if (empty($categoryName)) {
        $_SESSION['error'] = "Le nom de la catégorie ne peut pas être vide";
        header('Location: index.php?action=admin');
        exit;
    }
    
    if (strlen($categoryName) < 3 || strlen($categoryName) > 50) {
        $_SESSION['error'] = "Le nom doit contenir entre 3 et 50 caractères";
        header('Location: index.php?action=admin');
        exit;
    }
    
    $categoryModel = new CategoryModel($pdo);
    
    if ($categoryModel->create($categoryName)) {
        $_SESSION['success'] = "Catégorie créée avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la création de la catégorie";
    }
    
    header('Location: index.php?action=admin');
    exit;
}

/**
 * Renommer une catégorie
 */
function doRenameCategory($pdo) {
    // Vérifier que l'utilisateur est admin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "Accès refusé";
        header('Location: index.php');
        exit;
    }
    
    $categoryId = $_POST['category_id'] ?? 0;
    $newName = trim($_POST['new_name'] ?? '');
    
    // Validation
    if (empty($newName)) {
        $_SESSION['error'] = "Le nom de la catégorie ne peut pas être vide";
        header('Location: index.php?action=admin');
        exit;
    }
    
    if (strlen($newName) < 3 || strlen($newName) > 50) {
        $_SESSION['error'] = "Le nom doit contenir entre 3 et 50 caractères";
        header('Location: index.php?action=admin');
        exit;
    }
    
    $categoryModel = new CategoryModel($pdo);
    $category = $categoryModel->getById($categoryId);
    
    if (!$category) {
        $_SESSION['error'] = "Catégorie introuvable";
        header('Location: index.php?action=admin');
        exit;
    }
    
    if ($categoryModel->rename($categoryId, $newName)) {
        $_SESSION['success'] = "Catégorie renommée avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors du renommage";
    }
    
    header('Location: index.php?action=admin');
    exit;
}

/**
 * Supprimer une catégorie
 */
function doDeleteCategory($pdo) {
    // Vérifier que l'utilisateur est admin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "Accès refusé";
        header('Location: index.php');
        exit;
    }
    
    $categoryId = $_POST['category_id'] ?? 0;
    
    $categoryModel = new CategoryModel($pdo);
    $category = $categoryModel->getById($categoryId);
    
    if (!$category) {
        $_SESSION['error'] = "Catégorie introuvable";
        header('Location: index.php?action=admin');
        exit;
    }
    
    // Vérifier qu'il n'y a pas d'annonces dans cette catégorie
    $nbAnnonces = $categoryModel->countAnnonces($categoryId);
    
    if ($nbAnnonces > 0) {
        $_SESSION['error'] = "Impossible de supprimer une catégorie contenant des annonces ($nbAnnonces annonce(s))";
        header('Location: index.php?action=admin');
        exit;
    }
    
    if ($categoryModel->delete($categoryId)) {
        $_SESSION['success'] = "Catégorie supprimée avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression";
    }
    
    header('Location: index.php?action=admin');
    exit;
}
?>
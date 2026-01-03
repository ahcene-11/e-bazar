<?php
require_once 'models/UserModel.php';
require_once 'models/CategoryModel.php';
require_once 'models/AnnonceModel.php';
require_once 'models/PhotoModel.php';
require_once 'utils/upload.php';


function adminDashboard($pdo) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "Accès refusé : vous devez être administrateur";
        header('Location: index.php');
        exit;
    }
    
    $userModel = new UserModel($pdo);
    $categoryModel = new CategoryModel($pdo);
    $annonceModel = new AnnonceModel($pdo);
    $photoModel = new PhotoModel($pdo);
    $users = $userModel->getAll();
     $categories = $categoryModel->getAllWithCount();
    $rawAnnonces = $annonceModel->getAllForAdmin();
    $allAnnonces = [];
    
    foreach ($rawAnnonces as $annonce) {
        $photo = $photoModel->getPrimaryByAnnonce($annonce['id']);
        $annonce['primary_photo_filename'] = $photo ? $photo->getFilename() : null;
        $allAnnonces[] = $annonce;
    }
    $pageTitle = 'Administration - e-bazar';
    include 'views/admin_dashboard.php';
}

function deleteUser($pdo) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "Accès refusé";
        header('Location: index.php');
        exit;
    }
    
    $userId = $_POST['user_id'] ?? 0;
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
    if ($user->getRole() === 'admin') {
        $_SESSION['error'] = "Vous ne pouvez pas supprimer un autre administrateur";
        header('Location: index.php?action=admin');
        exit;
    }
   $annonceModel = new AnnonceModel($pdo);
    $photoModel = new PhotoModel($pdo);
    $userAnnonces = $annonceModel->getByUser($userId);
    foreach ($userAnnonces as $annonce) {
        $photos = $photoModel->getByAnnonce($annonce['id']);
        deleteAllPhotos($photos);
    }
    if ($userModel->delete($userId)) {
        $_SESSION['success'] = "Utilisateur supprimé avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression";
    }
    
    header('Location: index.php?action=admin');
    exit;
}
function deleteAnnonceAdmin($pdo) {
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
    $photos = $photoModel->getByAnnonce($annonceId);
    deleteAllPhotos($photos);
    if ($annonceModel->delete($annonceId)) {
        $_SESSION['success'] = "Annonce supprimée avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression";
    }
    
    header('Location: index.php?action=admin');
    exit;
}

function createCategory($pdo) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "Accès refusé";
        header('Location: index.php');
        exit;
    }
    $categoryName = trim($_POST['category_name'] ?? '');
    
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

function renameCategory($pdo) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "Accès refusé";
        header('Location: index.php');
        exit;
    }
    
    $categoryId = $_POST['category_id'] ?? 0;
    $newName = trim($_POST['new_name'] ?? '');
    
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

function deleteCategory($pdo) {
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
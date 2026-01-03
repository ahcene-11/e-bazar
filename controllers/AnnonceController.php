<?php
require_once 'models/AnnonceModel.php';
require_once 'models/PhotoModel.php';
require_once 'utils/upload.php';

function createForm($pdo) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Vous devez être connecté pour déposer une annonce";
        header('Location: index.php?action=loginForm');
        exit;
    }

    if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
        $_SESSION['error'] = "Les administrateurs ne peuvent pas déposer d'annonces.";
        header('Location: index.php'); 
        exit;
    }
    
    require_once 'models/CategoryModel.php';
    $categoryModel = new CategoryModel($pdo);
    $categories = $categoryModel->getAll();
    
    include 'views/annonce_form.php';
}

function createAnnonce($pdo) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Vous devez être connecté";
        header('Location: index.php?action=loginForm');
        exit;
    }
    
    $userId = $_SESSION['user']['id'];
    
    $categoryId = $_POST['category_id'] ?? 0;
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? 0;
    $deliveryPostal = isset($_POST['delivery_postal']) ? 1 : 0;
    $deliveryHand = isset($_POST['delivery_hand']) ? 1 : 0;
    
    
    $errors = [];
    
    if (empty($categoryId)) {
        $errors[] = "Veuillez sélectionner une catégorie";
    }
    
    if (strlen($title) < 5 || strlen($title) > 30) {
        $errors[] = "Le titre doit contenir entre 5 et 30 caractères";
    }
    
    if (strlen($description) < 5 || strlen($description) > 200) {
        $errors[] = "La description doit contenir entre 5 et 200 caractères";
    }
    
    if (!is_numeric($price) || $price < 0) {
        $errors[] = "Le prix doit être un nombre positif";
    }
    
    if (!$deliveryPostal && !$deliveryHand) {
        $errors[] = "Veuillez sélectionner au moins un mode de livraison";
    }
    
    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        header('Location: index.php?action=create');
        exit;
    }
    
    
    $annonceModel = new AnnonceModel($pdo);
    
    $annonceId = $annonceModel->create(
        $userId,
        $categoryId,
        $title,
        $description,
        $price,
        $deliveryPostal,
        $deliveryHand
    );
    
    if (!$annonceId) {
        $_SESSION['error'] = "Erreur lors de la création de l'annonce";
        header('Location: index.php?action=create');
        exit;
    }
    
    if (!empty($_FILES['photos']['name'][0])) {
        $uploadResult = handlePhotoUpload($_FILES['photos']);
        
        if (!$uploadResult['success']) {
            $annonceModel->delete($annonceId);
            $_SESSION['error'] = $uploadResult['error'];
            header('Location: index.php?action=create');
            exit;
        }
        
        $photoModel = new PhotoModel($pdo);
        
        foreach ($uploadResult['filenames'] as $index => $filename) {
            $isPrimary = ($index === 0); 
            $photoModel->create($annonceId, $filename, $isPrimary);
        }
    }
    
    $_SESSION['success'] = "Annonce créée avec succès !";
    header('Location: index.php?action=detail&id=' . $annonceId);
    exit;
}

function annonceDetail($pdo) {
    $annonceId = $_GET['id'] ?? 0;
    
    $annonceModel = new AnnonceModel($pdo);
    $photoModel = new PhotoModel($pdo);
    
    $annonce = $annonceModel->getById($annonceId);
    
    if (!$annonce) {
        $_SESSION['error'] = "Annonce introuvable";
        header('Location: index.php');
        exit;
    }
    
    $photos = $photoModel->getByAnnonce($annonceId);
    
    include 'views/annonce_detail.php';
}

function deleteAnnonce($pdo) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Vous devez être connecté";
        header('Location: index.php?action=loginForm');
        exit;
    }
    
    $annonceId = $_POST['annonce_id'] ?? 0;
    $userId = $_SESSION['user']['id'];
    
    $annonceModel = new AnnonceModel($pdo);
    $photoModel = new PhotoModel($pdo);
    
    $annonce = $annonceModel->getById($annonceId);
    
    if (!$annonce) {
        $_SESSION['error'] = "Annonce introuvable";
        header('Location: index.php');
        exit;
    }
    
    if ($annonce['user_id'] != $userId && $_SESSION['user']['role'] != 'admin') {
        $_SESSION['error'] = "Vous n'êtes pas autorisé à supprimer cette annonce";
        header('Location: index.php?action=detail&id=' . $annonceId);
        exit;
    }
    
    $photos = $photoModel->getByAnnonce($annonceId);
    deleteAllPhotos($photos);
    
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
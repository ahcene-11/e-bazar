<?php
require_once 'models/UserModel.php';
require_once 'models/AnnonceModel.php';
require_once 'models/TransactionModel.php';
require_once 'models/PhotoModel.php';

function login() {
    global $pdo;

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $userModel = new UserModel($pdo);
    $user = $userModel->authenticate($email, $password);

    if ($user) {
        session_regenerate_id(true);
        $_SESSION['user'] = $user->toArray();
        if (isset($_SESSION['pending_purchase_id'])) {
            $annonceId = $_SESSION['pending_purchase_id'];
            unset($_SESSION['pending_purchase_id']); 
            header('Location: index.php?action=purchaseConfirm&id=' . $annonceId);
        } else {
            header('Location: index.php');
        }
    } else {
        $_SESSION['error'] = "Email ou mot de passe incorrect";
        header('Location: index.php?action=loginForm');
    }
    exit;
}

function register() {
    global $pdo;

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Tous les champs sont obligatoires";
        header('Location: index.php?action=signUp');
        exit;
    }

    $userModel = new UserModel($pdo);
    $userId = $userModel->create($email, $password);

    if ($userId) {
        $user = $userModel->authenticate($email, $password);
        
        session_regenerate_id(true);
        $_SESSION['user'] = $user->toArray();
        $_SESSION['success'] = "Compte créé ! Vous êtes connecté.";
        if (isset($_SESSION['pending_purchase_id'])) {
            $annonceId = $_SESSION['pending_purchase_id'];
            unset($_SESSION['pending_purchase_id']); 
            header('Location: index.php?action=purchaseConfirm&id=' . $annonceId);
        } else {
            header('Location: index.php');
        }

    } else {
        $_SESSION['error'] = "Email déjà utilisé";
        header('Location: index.php?action=signUp');
    }
    exit;
}
function showDashboard($pdo) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Vous devez être connecté";
        header('Location: index.php?action=loginForm');
        exit;
    }

    $userId = $_SESSION['user']['id'];

    $annonceModel = new AnnonceModel($pdo);
    $transactionModel = new TransactionModel($pdo);
    $photoModel = new PhotoModel($pdo);
    $myAnnonces = $annonceModel->getByUser($userId, 'available');
    $mySales = $transactionModel->getBySeller($userId);
    $myPurchases = $transactionModel->getByBuyer($userId);
    foreach ($myAnnonces as &$annonce) {
        $photo = $photoModel->getPrimaryByAnnonce($annonce['id']);
        $annonce['photo_filename'] = $photo ? $photo->getFilename() : null;
    }
    unset($annonce);

    foreach ($mySales as &$sale) {
        $targetId = isset($sale['annonce_id']) ? $sale['annonce_id'] : $sale['id'];
        
        $photo = $photoModel->getPrimaryByAnnonce($targetId);
        $sale['photo_filename'] = $photo ? $photo->getFilename() : null;
    }
    unset($sale);
    
    foreach ($myPurchases as &$purchase) {
        $targetId = isset($purchase['annonce_id']) ? $purchase['annonce_id'] : $purchase['id'];
        
        $photo = $photoModel->getPrimaryByAnnonce($targetId);
        $purchase['photo_filename'] = $photo ? $photo->getFilename() : null;
    }
    unset($purchase);

    include 'views/user_dashboard.php';
}
?>
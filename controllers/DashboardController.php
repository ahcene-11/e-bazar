<?php
require_once 'models/AnnonceModel.php';
require_once 'models/TransactionModel.php';
require_once 'models/PhotoModel.php';

/** Afficher le dashboard utilisateur*/
function showDashboard($pdo) {
    // Vérifier que l'utilisateur est connecté
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Vous devez être connecté";
        header('Location: index.php?action=login');
        exit;
    }

    $userId = $_SESSION['user']['id'];

    $annonceModel = new AnnonceModel($pdo);
    $transactionModel = new TransactionModel($pdo);
    $photoModel = new PhotoModel($pdo);

    // Mes annonces en vente
    $myAnnonces = $annonceModel->getByUser($userId, 'available');

    // Mes ventes (annonces vendues par moi)
    $mySales = $transactionModel->getBySeller($userId);

    // Mes achats
    $myPurchases = $transactionModel->getByBuyer($userId);

    foreach ($myAnnonces as &$annonce) {
        $photo = $photoModel->getPrimaryByAnnonce($annonce['id']);
        // On crée une nouvelle clé 'photo_filename' dans le tableau
        $annonce['photo_filename'] = $photo ? $photo->getFilename() : null;
    }
    unset($annonce);

    foreach ($mySales as &$sale) {
        // Attention : vérifie si l'ID de l'annonce est 'id' ou 'annonce_id' dans ce tableau
        // Dans ta vue précédente, tu utilisais sale['annonce_id'], donc :
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
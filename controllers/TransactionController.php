<?php
require_once 'models/TransactionModel.php';
require_once 'models/AnnonceModel.php';
require_once 'models/CategoryModel.php';
require_once 'models/PhotoModel.php';


function purchaseConfirm($pdo) {
    $requestedId = $_POST['annonce_id'] ?? $_GET['id'] ?? 0;
if (!isset($_SESSION['user'])) {
        $_SESSION['pending_purchase_id'] = $requestedId;
        
        header('Location: index.php?action=signUp');
        exit;
    }
    $annonceId = $requestedId;
    
    $annonceModel = new AnnonceModel($pdo);
    $annonce = $annonceModel->getById($annonceId);

    if (!$annonce) {
        $_SESSION['error'] = "Annonce introuvable";
        header('Location: index.php');
        exit;
    }
    if ($annonce['status'] !== 'available') {
        $_SESSION['error'] = "Cette annonce n'est plus disponible";
        header('Location: index.php?action=detail&id=' . $annonceId);
        exit;
    }
    if ($annonce['user_id'] == $_SESSION['user']['id']) {
        $_SESSION['error'] = "Vous ne pouvez pas acheter votre propre annonce";
        header('Location: index.php?action=detail&id=' . $annonceId);
        exit;
    }
    $photoModel = new PhotoModel($pdo);
    $primaryPhoto = $photoModel->getPrimaryByAnnonce($annonce['id']);

    include 'views/purchase_confirm.php';
}

function purchase($pdo) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Vous devez être connecté";
        header('Location: index.php?action=loginForm');
        exit;
    }

    $annonceId = $_POST['annonce_id'] ?? 0;
    $deliveryMode = $_POST['delivery_mode'] ?? '';
    $buyerId = $_SESSION['user']['id'];
    if (!in_array($deliveryMode, ['postal', 'hand'])) {
        $_SESSION['error'] = "Mode de livraison invalide";
        header('Location: index.php?action=detail&id=' . $annonceId);
        exit;
    }

    $annonceModel = new AnnonceModel($pdo);
    $annonce = $annonceModel->getById($annonceId);
    if (!$annonce) {
        $_SESSION['error'] = "Annonce introuvable";
        header('Location: index.php');
        exit;
    }

    if ($annonce['status'] !== 'available') {
        $_SESSION['error'] = "Cette annonce n'est plus disponible";
        header('Location: index.php?action=detail&id=' . $annonceId);
        exit;
    }

    if ($annonce['user_id'] == $buyerId) {
        $_SESSION['error'] = "Vous ne pouvez pas acheter votre propre annonce";
        header('Location: index.php?action=detail&id=' . $annonceId);
        exit;
    }
    if ($deliveryMode === 'postal' && !$annonce['delivery_postal']) {
        $_SESSION['error'] = "L'envoi postal n'est pas accepté pour cette annonce";
        header('Location: index.php?action=purchaseConfirm');
        exit;
    }

    if ($deliveryMode === 'hand' && !$annonce['delivery_hand']) {
        $_SESSION['error'] = "La remise en main propre n'est pas acceptée pour cette annonce";
        header('Location: index.php?action=purchaseConfirm');
        exit;
    }
    $transactionModel = new TransactionModel($pdo);
    $transactionId = $transactionModel->create($annonceId, $buyerId, $deliveryMode);

    if (!$transactionId) {
        $_SESSION['error'] = "Erreur lors de l'achat";
        header('Location: index.php?action=detail&id=' . $annonceId);
        exit;
    }
    $annonceModel->updateStatus($annonceId, 'sold');

    $_SESSION['success'] = "Achat effectué avec succès ! Le vendeur va préparer la livraison.";
    header('Location: index.php?action=dashboard');
    exit;
}

function confirmReception($pdo) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Vous devez être connecté";
        header('Location: index.php?action=loginForm');
        exit;
    }

    $annonceId = $_POST['annonce_id'] ?? 0;
    $userId = $_SESSION['user']['id'];

    $transactionModel = new TransactionModel($pdo);
    $transaction = $transactionModel->getByAnnonce($annonceId);
    if (!$transaction) {
        $_SESSION['error'] = "Transaction introuvable";
        header('Location: index.php?action=dashboard');
        exit;
    }

    if ($transaction->getBuyerId() != $userId) {
        $_SESSION['error'] = "Vous n'êtes pas l'acheteur de cette annonce";
        header('Location: index.php?action=dashboard');
        exit;
    }

    if ($transaction->isConfirmed()) {
        $_SESSION['error'] = "La réception a déjà été confirmée";
        header('Location: index.php?action=dashboard');
        exit;
    }
    if ($transactionModel->confirmReception($annonceId)) {
        $_SESSION['success'] = "Réception confirmée ! La transaction est terminée.";
    } else {
        $_SESSION['error'] = "Erreur lors de la confirmation";
    }

    header('Location: index.php?action=dashboard');
    exit;
}
?>
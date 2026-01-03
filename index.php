<?php
session_start();
require_once 'config.php';
$action = $_GET['action'] ?? 'home';
switch($action) {
    case 'home':
        require_once 'controllers/HomeController.php';
        showHome();
        break;
    case 'loginForm':
        include 'views/login.php';
        break;
    case 'signUp':
        include 'views/signUp.php';
        break;
    case 'login':
        include 'controllers/UserController.php';
        login();
        break;
    case 'register':
        include 'controllers/UserController.php';
        register();
        break;
    case 'logout':
        session_destroy();
        header('Location: index.php');
        break;
    case 'category':
        require_once 'controllers/CategoryController.php';
        categoryList(); 
        break;
    case 'detail':
        require_once 'controllers/AnnonceController.php';
        annonceDetail($pdo); 
        break;
    case 'deleteAnnonce':
        require_once 'controllers/AnnonceController.php';
        deleteAnnonce($pdo);
        break; 
    case 'create':
        require_once 'controllers/AnnonceController.php';
        createForm($pdo);
        break;
    case 'createAnnonce':
        require_once 'controllers/AnnonceController.php';
        createAnnonce($pdo);
        break;
    case 'purchaseConfirm':
        require_once 'controllers/TransactionController.php';
        purchaseConfirm($pdo);
        break;
    case 'purchase':
        require_once 'controllers/TransactionController.php';
        purchase($pdo);
        break;
    case 'confirmReception':
        require_once 'controllers/TransactionController.php';
        confirmReception($pdo);
        break;
    case 'dashboard':
        require_once 'controllers/UserController.php';
        showDashboard($pdo);
        break;
    case 'admin':
        require_once 'controllers/AdminController.php';
        adminDashboard($pdo);
        break;
    case 'deleteUser':
        require_once 'controllers/AdminController.php';
        deleteUser($pdo);
    break;
    case 'deleteAnnonceAdmin':
        require_once 'controllers/AdminController.php';
        deleteAnnonceAdmin($pdo);
        break;
    case 'createCategory':
        require_once 'controllers/AdminController.php';
        createCategory($pdo);
        break;
    case 'renameCategory':
        require_once 'controllers/AdminController.php';
       renameCategory($pdo);
        break;
    case 'deleteCategory':
        require_once 'controllers/AdminController.php';
        deleteCategory($pdo);
        break;
    default:
        echo "Page non trouvée";
}
?>
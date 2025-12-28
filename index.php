<?php
session_start();
require_once 'config.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Récupérer l'action demandée dans l'URL
// Ex: index.php?action=login ou juste index.php
$action = $_GET['action'] ?? 'home';

// Router simple : selon l'action, on inclut la bonne vue
switch($action) {
    case 'home':
        require_once 'controllers/HomeController.php';
        showHome();
        break;
    
    case 'login':
        include 'views/login.php';
        break;

    case 'signUp':
        include 'views/signUp.php';
        break;

    case 'do_login':
        include 'controllers/UserController.php';
        doLogin(); // fonction dans le controller
        break;

    case 'do_signUp':
        include 'controllers/UserController.php';
        do_signUp(); // fonction dans le controller
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php');
        break;

    case 'category':
        require_once 'controllers/CategoryListController.php';
        showCategoryList(); 
        break;

    case 'detail':
        require_once 'controllers/AnnonceDetailController.php';
        showAnnonceDetail($pdo); 
        break;

    case 'do_delete_annonce':
        require_once 'controllers/AnnonceDetailController.php';
        doDeleteAnnonce($pdo);
        break;
    
    case 'create':
        require_once 'controllers/AnnonceDetailController.php';
        showCreateForm($pdo);
        break;

    case 'do_create_annonce':
        require_once 'controllers/AnnonceDetailController.php';
        doCreateAnnonce($pdo);
        break;
    //achat
    case 'purchase':
        require_once 'controllers/TransactionController.php';
        showPurchaseConfirm($pdo);
        break;

    case 'do_purchase':
        require_once 'controllers/TransactionController.php';
        doPurchase($pdo);
        break;

    case 'do_confirm_reception':
        require_once 'controllers/TransactionController.php';
        doConfirmReception($pdo);
        break;
        
    //dashboards
    case 'dashboard':
        require_once 'controllers/DashboardController.php';
        showDashboard($pdo);
        break;
    default:
        echo "Page non trouvée";
}
?>
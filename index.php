<?php
session_start();
require_once 'config.php';

// Récupérer l'action demandée dans l'URL
// Ex: index.php?action=login ou juste index.php
$action = $_GET['action'] ?? 'home';

// Router simple : selon l'action, on inclut la bonne vue
switch($action) {
    case 'home':
        include 'views/home.php';
        break;
    
    case 'login':
        include 'views/login.php';
        break;

    case 'signIn':
        include 'views/signIn.php';
        break;

    case 'do_login':
        include 'controllers/UserController.php';
        doLogin(); // fonction dans le controller
        break;
    
    case 'logout':
        session_destroy();
        header('Location: index.php');
        break;
    
    default:
        echo "Page non trouvée";
}
?>
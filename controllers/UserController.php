<?php
// Cette fonction est appelée quand on soumet le formulaire de connexion

function doLogin() {
    global $pdo;

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Authentifier
    $userModel = new UserModel($pdo);
    $user = $userModel->authenticate($email, $password);

    if ($user) {
        // Stocker en session (utiliser toArray())
        $_SESSION['user'] = $user->toArray();
        header('Location: index.php');
    } else {
        $_SESSION['error'] = "Email ou mot de passe incorrect";
        header('Location: index.php?action=login');
    }
    exit;
}

function doRegister() {
    global $pdo;

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validation basique
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Tous les champs sont obligatoires";
        header('Location: index.php?action=signUp');
        exit;
    }

    // Créer l'utilisateur
    $userModel = new UserModel($pdo);
    $userId = $userModel->create($email, $password);

    if ($userId) {
        $_SESSION['success'] = "Compte créé avec succès !";
        header('Location: index.php?action=login');
    } else {
        $_SESSION['error'] = "Email déjà utilisé";
        header('Location: index.php?action=signUp');
    }
    exit;
}
?>
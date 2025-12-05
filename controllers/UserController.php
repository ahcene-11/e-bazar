<?php
// Cette fonction est appelée quand on soumet le formulaire de connexion

function doLogin() {
    global $pdo; // Accès à la connexion BDD
    
    // Récupérer les données du formulaire
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Chercher l'utilisateur dans la BDD
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Vérifier le mot de passe
    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie : on stocke l'user en session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        header('Location: index.php'); // Redirection vers accueil
    } else {
        // Échec : message d'erreur
        $_SESSION['error'] = "Email ou mot de passe incorrect";
        header('Location: index.php?action=login');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'e-bazar' ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <h1 class="logo">
                <a href="index.php">e-bazar</a>
            </h1>
            <nav class="nav-links">
                <a href="index.php">Accueil</a>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] === 'user'): ?>
                        <a href="index.php?action=dashboard"><img src="public/images/icone-utilisateur.png" alt="icone user" class="user-avatar">Mon espace </a>
                    <?php endif; ?>
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <a href="index.php?action=admin" class="btn-primary-nav">Admin</a>
                    <?php endif; ?>
                    
                    <a href="index.php?action=logout">Déconnexion</a>
                <?php else: ?>
                    <a href="index.php?action=loginForm">Connexion</a>
                    <a href="index.php?action=signUp" class="btn-primary-nav">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
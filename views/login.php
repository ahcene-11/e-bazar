<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <h1>e-bazar</h1>
        <a href="index.php">Retour accueil</a>
    </header>

    <main>
        <h2>Connexion</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="index.php?action=do_login" method="POST">
            <label>Email:
                <input type="email" name="email" required>
            </label>
            
            <label>Mot de passe:
                <input type="password" name="password" required>
            </label>
            
            <button type="submit">Se connecter</button>
        </form>
    </main>
</body>
</html>
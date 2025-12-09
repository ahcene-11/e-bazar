<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>e-bazar - Accueil</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <h1>e-bazar</h1>
        <nav>
            <?php if (isset($_SESSION['user'])): ?>
                <span>Bonjour <?= htmlspecialchars($_SESSION['user']['email']) ?></span>
                <a href="index.php?action=logout">Déconnexion</a>
            <?php else: ?>
                <a href="index.php?action=login">Connexion</a>
                <a href="index.php?action=signIn">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h2>Catégories</h2>
        <?php
        // Récupérer les catégories depuis la BDD
        $stmt = $pdo->query("SELECT * FROM categories");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($categories as $cat): ?>
            <div class="category-card">
                <h3><?= htmlspecialchars($cat['name']) ?></h3>
                <a href="index.php?action=category&id=<?= $cat['id'] ?>">Voir les annonces</a>
            </div>
        <?php endforeach; ?>
    </main>
</body>
</html>
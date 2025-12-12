<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>e-bazar - Accueil</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    
<?php
// Variables disponibles depuis le contrôleur :
// $categories (tableau avec id, name, nb_annonces)
// $recentAnnonces (tableau avec toutes les infos)

$pageTitle = 'Accueil - e-bazar';
include 'views/header.php';
?>
    <main class="container">
    <!-- Section Catégories -->
    <section class="section-categories">
        <h2>Catégories</h2>
        <div class="categories-grid">
            <?php foreach($categories as $category): ?>
                <?php include 'views/components/category_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Section Dernières annonces -->
    <section class="section-recent">
        <h2>Dernières annonces</h2>
        <div class="annonces-grid">
            <?php if (empty($recentAnnonces)): ?>
                <p class="no-results">Aucune annonce disponible pour le moment.</p>
            <?php else: ?>
                <?php foreach($recentAnnonces as $annonce): ?>
                    <?php include 'views/components/annonce_card.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>
</body>
</html>


<?php
$pageTitle = 'Accueil - e-bazar';
include 'views/components/header.php';
?>
    <main class="container">
    <section class="section-categories">
        <h2>Catégories</h2>
        <div class="categories-grid">
            <?php foreach($categories as $category): ?>
                <?php include 'views/components/category_component.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="section-recent">
        <h2>Dernières annonces</h2>
        <div class="annonces-grid">
            <?php if (empty($recentAnnonces)): ?>
                <p class="no-results">Aucune annonce disponible pour le moment.</p>
            <?php else: ?>
                <?php foreach($recentAnnonces as $annonce): ?>
                    <?php include 'views/components/annonce_component.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>
</body>
</html>


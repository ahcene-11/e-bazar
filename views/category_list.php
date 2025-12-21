 <?php

$pageTitle = htmlspecialchars($category->getName()) . ' - e-bazar';
include 'views/components/header.php';
?>

<main class="container">
    <div class="breadcrumb">
        <a href="index.php">  Retour à l'accueil</a>
    </div>

    <div class="category-header">
        <h1><?= htmlspecialchars($category->getName()) ?></h1>
        <p class="category-info">
            <?= $totalAnnonces ?> annonce<?= $totalAnnonces > 1 ? 's' : '' ?> disponible<?= $totalAnnonces > 1 ? 's' : '' ?>
        </p>
    </div>

    <?php if (empty($annonces)): ?>
        <div class="empty-state">
            <p class="empty-icon">📦</p>
            <h2>Aucune annonce dans cette catégorie</h2>
            <p>Soyez le premier à déposer une annonce !</p>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="index.php?action=create" class="btn-primary">Déposer une annonce</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="annonces-list">
            <?php foreach($annonces as $annonce): ?>
                <div class="annonce-list-item">
                    <div class="annonce-image-large">
                        <?php
                        // Pour l'instant pas de photos (Phase 4)
                        ?>
                        <div class="image-placeholder">📷</div>
                    </div>
                    <div class="annonce-details">
                        <h3 class="annonce-title-large">
                            <?= htmlspecialchars($annonce['title']) ?>
                        </h3>
                        <p class="annonce-price-large">
                            <?= number_format($annonce['price'], 2, ',', ' ') ?> €
                        </p>
                        <p class="annonce-excerpt">
                            <?= htmlspecialchars(substr($annonce['description'], 0, 100)) ?>
                            <?= strlen($annonce['description']) > 100 ? '...' : '' ?>
                        </p>
                        <div class="annonce-meta">
                            <span class="meta-date">
                                📅 <?= date('d/m/Y', strtotime($annonce['created_at'])) ?>
                            </span>
                            <span class="meta-delivery">
                                <?php if ($annonce['delivery_postal']): ?>
                                    📮 Envoi postal
                                <?php endif; ?>
                                <?php if ($annonce['delivery_hand']): ?>
                                    🤝 Remise en main propre
                                <?php endif; ?>
                            </span>
                        </div>
                        <a href="index.php?action=detail&id=<?= $annonce['id'] ?>" class="btn-primary">
                            Voir le détail 
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="index.php?action=category&id=<?= $categoryId ?>&page=<?= $currentPage - 1 ?>" 
                       class="pagination-btn">
                         Précédent
                    </a>
                <?php endif; ?>

                <div class="pagination-numbers">
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="pagination-number active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="index.php?action=category&id=<?= $categoryId ?>&page=<?= $i ?>" 
                               class="pagination-number">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="index.php?action=category&id=<?= $categoryId ?>&page=<?= $currentPage + 1 ?>" 
                       class="pagination-btn">
                        Suivant 
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

</body>
</html>
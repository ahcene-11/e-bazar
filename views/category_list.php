 <?php

$pageTitle = htmlspecialchars($category->getName()) . ' - e-bazar';
include 'views/components/header.php';
?>

<main class="container">
    <div class="fil_darianne">
        <a href="index.php">Retour à l'accueil</a>
    </div>

    <div class="category-header">
        <h1><?= htmlspecialchars($category->getName()) ?></h1>
        <p class="category-info">
            <?= $totalAnnonces ?> annonce<?= $totalAnnonces > 1 ? 's' : '' ?> disponible<?= $totalAnnonces > 1 ? 's' : '' ?>
        </p>
    </div>

    <?php if (empty($annonces)): ?>
        <div class="empty-state">
            <p class="empty-icon"><img src="public/images/vide.png" alt="image pour exprimer l'absence d'annonces" style="width: 30%; height: 30%; object-fit: cover;"></p>
            <h2>Aucune annonce dans cette catégorie</h2>
            <p>Soyez le premier à déposer une annonce !</p>
            
                <a href="index.php?action=create" class="btn-primary">Déposer une annonce</a>
        </div>
    <?php else: ?>
        <div class="annonces-list">
            <?php foreach($annonces as $annonce): ?>
                <div class="annonce-list-item">
                    <div class="annonce-image-large">
    <?php if (!empty($annonce['photo_filename'])): ?>
        <img src="public/uploads/<?= htmlspecialchars($annonce['photo_filename']) ?>" 
             alt="<?= htmlspecialchars($annonce['title']) ?>"
             style="width: 100%; height: 100%; object-fit: cover;">
    <?php else: ?>
        <div class="image-placeholder"><img src="public/images/placeholder.png" alt="placeholder d'image" style="width: 100%; height: 100%; object-fit: cover;"></div>
    <?php endif; ?>
</div>
                    <div class="annonce-details">
                        <h2 class="annonce-title-large">
                            <?= htmlspecialchars($annonce['title']) ?>
                        </h2>
                        <p class="annonce-price-large">
                            <?= number_format($annonce['price'], 2, ',', ' ') ?> €
                        </p>
                        <p class="annonce-excerpt">
                            <?= htmlspecialchars(substr($annonce['description'], 0, 100)) ?>
                            <?= strlen($annonce['description']) > 100 ? '...' : '' ?>
                        </p>
                        <div class="annonce-meta">
                            <span class="meta-date">
                                <img src="public/images/date.png" alt="date de annonce" class="petite-icone" > <?= date('d/m/Y', strtotime($annonce['created_at'])) ?>
                            </span>
                            <span class="meta-delivery">
                                <?php if ($annonce['delivery_postal']): ?>
                                    <img src="public/images/envoyer.png" alt="envoi postal" class="petite-icone" > Envoi postal
                                <?php endif; ?>
                                <?php if ($annonce['delivery_hand']): ?>
                                    <img src="public/images/achats.png" alt="remise en main propre" class="petite-icone" >  Remise en main propre
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
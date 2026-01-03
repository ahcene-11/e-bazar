<div class="annonce-card">
    <div class="annonce-image">
        <?php if (!empty($annonce['photo_filename'])): ?>
            <img src="public/uploads/<?= htmlspecialchars($annonce['photo_filename']) ?>" 
                 alt="<?= htmlspecialchars($annonce['title']) ?>"
                 style="width: 100%; height: 100%; object-fit: cover;">
        <?php else: ?>
            <div class="image-placeholder"><img src="public/images/placeholder.png" alt="placeholder d'image" style="width: 100%; height: 100%; object-fit: cover;"></div>
        <?php endif; ?>
    </div>

    <div class="annonce-content">
        <h3 class="annonce-title"><?= htmlspecialchars($annonce['title']) ?></h3>
        
        <p class="annonce-price">
            <?php if ($annonce['price'] == 0): ?>
                Gratuit
            <?php else: ?>
                <?= number_format($annonce['price'], 2, ',', ' ') ?> €
            <?php endif; ?>
        </p>
        
        <p class="annonce-category">
            <span class="category-badge">
                <?= htmlspecialchars($annonce['category_name']) ?>
            </span>
        </p>
        
        <a href="index.php?action=detail&id=<?= $annonce['id'] ?>" class="btn-primary">
            Voir le détail
        </a>
    </div>
</div>
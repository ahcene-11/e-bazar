<div class="annonce-card">
    <div class="annonce-image">
        <?php
        // Pour l'instant pas de photos (Phase 4)
        // On affichera juste un placeholder
        ?>
        <div class="image-placeholder">📷</div>
    </div>
    <div class="annonce-content">
        <h3 class="annonce-title"><?php echo htmlspecialchars($annonce['title']) ?></h3>
        <p class="annonce-price"><?php echo number_format($annonce['price'], 2, ',', ' ') ?> €</p>
        <p class="annonce-category">
            <span class="category-badge"><?php echo htmlspecialchars($annonce['category_name']) ?></span>
        </p>
        <a href="index.php?action=detail&id=<?php echo $annonce['id'] ?>" class="btn-primary">
            Voir le détail →
        </a>
    </div>
</div>

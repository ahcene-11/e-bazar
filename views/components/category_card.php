<div class="category-card">
    <h3><?php echo htmlspecialchars($category['name']) ?></h3>
    <p class="category-count">
        <?php echo $category['nb_annonces'] ?? 0 ?> annonce<?php echo ($category['nb_annonces'] ?? 0) > 1 ? 's' : '' ?>
    </p>
    <a href="index.php?action=category&id=<?php echo $category['id'] ?>" class="btn-secondary">
        Voir les annonces 
    </a>
</div>

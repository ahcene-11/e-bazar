<?php

$pageTitle = htmlspecialchars($annonce['title']) . ' - e-bazar';
include 'views/components/header.php';
?>

<main class="container">
    <div class="breadcrumb">
        <a href="index.php">Accueil</a>
        <span class="separator">›</span>
        <a href="index.php?action=category&id=<?= $annonce['category_id'] ?>">
            <?= htmlspecialchars($annonce['category_name']) ?>
        </a>
        <span class="separator">›</span>
        <span><?= htmlspecialchars($annonce['title']) ?></span>
    </div>

    <div class="annonce-detail-container">
        <div class="annonce-photos">
    <div class="main-photo">
        <?php if (!empty($photos)): ?>
            <img src="public/uploads/<?= htmlspecialchars($photos[0]->getFilename()) ?>" 
     alt="<?= htmlspecialchars($annonce['title']) ?>"
     id="main-photo"
     style="max-width: 100%; height: auto;">
        <?php else: ?>
            <div class="image-placeholder-large"><img src="public/images/placeholder.png" alt="placeholder d'image" style="width: 100%; height: 100%; object-fit: cover;"></div>
        <?php endif; ?>
    </div>
    
    <?php if (count($photos) > 1): ?>
        <div class="photo-thumbnails">
            <?php foreach($photos as $index => $photo): ?>
                <div class="thumbnail <?= $index === 0 ? 'active' : '' ?>" 
                     onclick="changeMainPhoto('public/uploads/<?= htmlspecialchars($photo->getFilename()) ?>', this)">
                    <img src="public/uploads/<?= htmlspecialchars($photo->getFilename()) ?>" 
                         alt="Photo <?= $index + 1 ?>"
     style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            <?php endforeach; ?>
        </div>
        <!---- a revoir -->
        <script>
        function changeMainPhoto(src, thumbnail) {
            document.getElementById('main-photo').src = src;
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            thumbnail.classList.add('active');
        }
        </script>
    <?php endif; ?>
</div>

        <div class="annonce-info">
            <h1 class="detail-title"><?= htmlspecialchars($annonce['title']) ?></h1>
            
            <div class="detail-price">
                <?php if ($annonce['price'] == 0): ?>
                    <span class="price-free">GRATUIT (Don)</span>
                <?php else: ?>
                    <?= number_format($annonce['price'], 2, ',', ' ') ?> €
                <?php endif; ?>
            </div>

            <div class="detail-category">
                <span class="category-badge-large">
                     <?= htmlspecialchars($annonce['category_name']) ?>
                </span>
            </div>

            <div class="detail-section">
                <h3>Description</h3>
                <p class="detail-description"><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>
            </div>

            <div class="detail-section">
                <h3>Modes de livraison acceptés</h3>
                <div class="delivery-modes">
                    <?php if ($annonce['delivery_postal']): ?>
                        <span class="delivery-badge"> Envoi postal</span>
                    <?php endif; ?>
                    <?php if ($annonce['delivery_hand']): ?>
                        <span class="delivery-badge"> Remise en main propre</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-actions">
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['id'] == $annonce['user_id']): ?>
                            <p class="info-message">C'est votre annonce</p>
                            <form method="POST" action="index.php?action=deleteAnnonce" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                                <button type="submit" class="btn-danger">
                                    Supprimer l'annonce
                                </button>
                            </form>

                        <?php elseif ($_SESSION['user']['role'] === 'admin'): ?>
                        <p class="info-message">Vous ne pouvez pas acheter en tant qu'admin</p>
                        
                        <?php else: ?>
                            <form method="POST" action="index.php?action=purchaseConfirm">
                                <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                                <button type="submit" class="btn-buy">
                                    Acheter
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                       <form method="POST" action="index.php?action=purchaseConfirm">
                                <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                                <button type="submit" class="btn-buy">
                                    Acheter
                                </button>
                        </form>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</main>
</body>
</html>
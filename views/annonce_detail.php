<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <?php
// Variables disponibles depuis le contrôleur (case 'detail' dans index.php) :
// $annonce (tableau associatif avec toutes les infos)

$pageTitle = htmlspecialchars($annonce['title']) . ' - e-bazar';
include 'views/layout/header.php';
?>

<main class="container">
    <!-- Fil d'Ariane -->
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
        <!-- Colonne Gauche : Photos -->
        <div class="annonce-photos">
            <div class="main-photo">
                <?php
                // Pour l'instant pas de photos (Phase 4)
                ?>
                <div class="image-placeholder-large">📷</div>
            </div>
            <!-- Miniatures (pour Phase 4)
            <div class="photo-thumbnails">
                <div class="thumbnail active">
                    <div class="image-placeholder-small">📷</div>
                </div>
            </div>
            -->
        </div>

        <!-- Colonne Droite : Informations -->
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
                    📂 <?= htmlspecialchars($annonce['category_name']) ?>
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
                        <span class="delivery-badge">📮 Envoi postal</span>
                    <?php endif; ?>
                    <?php if ($annonce['delivery_hand']): ?>
                        <span class="delivery-badge">🤝 Remise en main propre</span>
                    <?php endif; ?>
                </div>
            </div>


            <!-- Actions -->
            <div class="detail-actions">
                
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['id'] == $annonce['user_id']): ?>
                            <!-- C'est l'annonce de l'utilisateur connecté -->
                            <p class="info-message">C'est votre annonce</p>
                            <form method="POST" action="index.php?action=do_delete_annonce" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                                <button type="submit" class="btn-danger">
                                    Supprimer l'annonce
                                </button>
                            </form>
                        <?php else: ?>
                            <!-- Utilisateur connecté, pas son annonce : peut acheter -->
                            <form method="POST" action="index.php?action=purchase">
                                <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                                <button type="submit" class="btn-buy">
                                    Acheter
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Utilisateur non connecté -->
                       <form method="POST" action="index.php?action=purchase">
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
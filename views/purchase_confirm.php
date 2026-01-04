<?php
$pageTitle = 'Confirmer l\'achat - e-bazar';
include 'views/components/header.php';
?>

<main class="container">
    <div class="purchase-container">
        <h1>Confirmer l'achat</h1>
        <div class="purchase-summary">
            <h2>Récapitulatif</h2>
            <div class="summary-item">
                <div class="summary-image">
                    <?php if (isset($primaryPhoto) && $primaryPhoto): ?>
                        <img src="public/uploads/<?= htmlspecialchars($primaryPhoto->getFilename()) ?>"
                            alt="<?= htmlspecialchars($annonce['title']) ?>">
                    <?php else: ?>
                        <div class="image-placeholder-small"><img src="public/images/placeholder.png" alt="placeholder d'image" style="width: 100%; height: 100%; object-fit: cover;"></div>
                    <?php endif; ?>
                </div>
                <div class="summary-details">
                    <h3><?= htmlspecialchars($annonce['title']) ?></h3>
                    <p class="summary-category"><?= htmlspecialchars($annonce['category_name']) ?></p>
                    <p class="summary-price"><?= number_format($annonce['price'], 2, ',', ' ') ?> €</p>
                </div>
            </div>
        </div>
        <form action="index.php?action=purchase" method="POST" class="purchase-form">
    <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">

    <div class="form-group">
        <label class="required">Mode de livraison</label>
        <div class="radio-group">
            <?php if ($annonce['delivery_postal']): ?>
                <label class="radio-label">
                    <input type="radio" name="delivery_mode" value="postal" required>
                    <span class="radio-content">
                        <span style="display:block; width: 100%;">
                            <img src="public/images/envoyer.png" alt="envoi postal" class="petite-icone" >
                            <strong>Envoi postal</strong>
                            <span class="radio-description" style="display:block; margin-top: 5px;">
                                Le vendeur vous enverra le bien par la poste
                            </span>
                        </span>
                    </span>
                </label>
            <?php endif; ?>

            <?php if ($annonce['delivery_hand']): ?>
                <label class="radio-label">
                    <input type="radio" name="delivery_mode" value="hand" required>
                    <span class="radio-content">
                        <span style="display:block; width: 100%;">
                            <img src="public/images/achats.png" alt="remise en main propre" class="petite-icone" >
                            <strong>Remise en main propre</strong>
                            <span class="radio-description" style="display:block; margin-top: 5px;">
                                Vous récupérerez le bien directement auprès du vendeur
                            </span>
                        </span>
                    </span>
                </label>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="info-box">
        <h4>Informations importantes</h4>
        <ul>
            <li>Le vendeur sera notifié de votre achat</li>
            <li>Vous devrez confirmer la réception du bien une fois reçu</li>
        </ul>
    </div>

    <div class="form-actions">
        <a href="index.php?action=detail&id=<?= $annonce['id'] ?>" class="btn-secondary">
            Annuler
        </a>
        <button type="submit" class="btn-buy">
            Confirmer l'achat
        </button>
    </div>
</form>
    </div>
</main>
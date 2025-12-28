<?php
// Variables disponibles :
// $myAnnonces (mes annonces en vente)
// $mySales (mes ventes à livrer)
// $myPurchases (mes achats)

$pageTitle = 'Mon espace personnel - e-bazar';
include 'views/components/header.php';

?>

<main class="container">
    <div class="dashboard-container">
        <h1>Mon espace personnel</h1>

        <!-- Section : Mes annonces en vente -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2>📦 Mes annonces en vente (<?= count($myAnnonces) ?>)</h2>
                <a href="index.php?action=create" class="btn-primary">+ Nouvelle annonce</a>
            </div>

            <?php if (empty($myAnnonces)): ?>
                <div class="empty-message">
                    <p>Vous n'avez aucune annonce en vente.</p>
                    <a href="index.php?action=create" class="btn-primary">Déposer une annonce</a>
                </div>
            <?php else: ?>
                <div class="dashboard-list">
                    <?php foreach($myAnnonces as $annonce): ?>
                        
                        <div class="dashboard-item">
                            <div class="item-image">
                                <?php if (!empty($annonce['photo_filename'])): ?> 
                    
                                    <img src="public/uploads/<?= htmlspecialchars($annonce['photo_filename']) ?>"
                                        alt="<?= htmlspecialchars($annonce['title']) ?>">
                                        
                                <?php else: ?>
                                    <div class="image-placeholder-dashboard">📷</div>
                                <?php endif; ?>
                            </div>
                            <div class="item-details">
                                <h3><?= htmlspecialchars($annonce['title']) ?></h3>
                                <p class="item-price"><?= number_format($annonce['price'], 2, ',', ' ') ?> €</p>
                                <p class="item-category"><?= htmlspecialchars($annonce['category_name']) ?></p>
                                <p class="item-date">Publié le <?= date('d/m/Y', strtotime($annonce['created_at'])) ?></p>
                            </div>
                            <div class="item-actions">
                                <a href="index.php?action=detail&id=<?= $annonce['id'] ?>" class="btn-secondary-small">
                                    Voir
                                </a>
                                <form method="POST" action="index.php?action=do_delete_annonce"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                    <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                                    <button type="submit" class="btn-danger-small">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Section : Mes ventes à livrer -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2>📮 Mes ventes à livrer (<?= count($mySales) ?>)</h2>
            </div>

            <?php if (empty($mySales)): ?>
                <div class="empty-message">
                    <p>Vous n'avez aucune vente en attente de livraison.</p>
                </div>
            <?php else: ?>
                <div class="dashboard-list">
                    <?php foreach($mySales as $sale): ?>
                        <div class="dashboard-item">
                            <div class="item-image">
                                <?php if (!empty($sale['photo_filename'])): ?>
                                    <img src="public/uploads/<?= htmlspecialchars($sale['photo_filename']) ?>"
                                        alt="<?= htmlspecialchars($sale['title']) ?>">
                                <?php else: ?>
                                    <div class="image-placeholder-dashboard">📷</div>
                                <?php endif; ?>
                            </div>
                            <div class="item-details">
                                <h3><?= htmlspecialchars($sale['title']) ?></h3>
                                <p class="item-price"><?= number_format($sale['price'], 2, ',', ' ') ?> €</p>
                                <p class="item-buyer">Acheteur : <?= htmlspecialchars($sale['buyer_email']) ?></p>
                                <p class="item-delivery">
                                    Mode : <?= $sale['delivery_mode'] === 'postal' ? '📮 Envoi postal' : '🤝 Remise en main propre' ?>
                                </p>
                                <p class="item-status">
                                    <?php if ($sale['confirmed']): ?>
                                        <span class="status-badge status-confirmed"> Réception confirmée</span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending">⏳ En attente de confirmation</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Section : Mes achats -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2>🛒 Mes achats (<?= count($myPurchases) ?>)</h2>
            </div>

            <?php if (empty($myPurchases)): ?>
                <div class="empty-message">
                    <p>Vous n'avez effectué aucun achat.</p>
                    <a href="index.php" class="btn-primary">Parcourir les annonces</a>
                </div>
            <?php else: ?>
                <div class="dashboard-list">
                    <?php foreach($myPurchases as $purchase): ?>
                        <div class="dashboard-item">
                            <div class="item-image">
                                <?php if (!empty($purchase['photo_filename'])): ?>
                                    <img src="public/uploads/<?= htmlspecialchars($purchase['photo_filename']) ?>"
                                        alt="<?= htmlspecialchars($purchase['title']) ?>">
                                <?php else: ?>
                                    <div class="image-placeholder-dashboard">📷</div>
                                <?php endif; ?>
                            </div>
                            <div class="item-details">
                                <h3><?= htmlspecialchars($purchase['title']) ?></h3>
                                <p class="item-price"><?= number_format($purchase['price'], 2, ',', ' ') ?> €</p>
                                <p class="item-seller">Vendeur : <?= htmlspecialchars($purchase['seller_email']) ?></p>
                                <p class="item-delivery">
                                    Mode : <?= $purchase['delivery_mode'] === 'postal' ? '📮 Envoi postal' : '🤝 Remise en main propre' ?>
                                </p>
                                <p class="item-date">Acheté le <?= date('d/m/Y', strtotime($purchase['created_at'])) ?></p>
                            </div>
                            <div class="item-actions">
                                <?php if (!$purchase['confirmed']): ?>
                                    <form method="POST" action="index.php?action=do_confirm_reception"
                                          onsubmit="return confirm('Confirmez-vous avoir bien reçu ce bien ?');">
                                        <input type="hidden" name="annonce_id" value="<?= $purchase['annonce_id'] ?>">
                                        <button type="submit" class="btn-success-small">
                                             Confirmer la réception
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="status-badge status-confirmed"> Réception confirmée</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>
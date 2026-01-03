<?php
include 'views/components/header.php';
?>

<main class="container">
    <div class="admin-container">
        <h1>Administration</h1>
        <section class="admin-section">
            <h2>Gestion des catégories</h2>
            <div class="admin-form-box">
                <h3>Ajouter une catégorie</h3>
                <form action="index.php?action=createCategory" method="POST" class="inline-form">
                    <input 
                        type="text" 
                        name="category_name" 
                        placeholder="Nom de la catégorie" 
                        required
                        minlength="3"
                        maxlength="50"
                    >
                    <button type="submit" class="btn-primary">Ajouter</button>
                </form>
            </div>
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Nb d'annonces</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $category): ?>
                            <tr>
                                <td><?= $category['id'] ?></td>
                                <td><?= htmlspecialchars($category['name']) ?></td>
                                <td><?= $category['nb_annonces'] ?? 0 ?></td>
                                <td class="actions-cell">
                                    <button 
                                        onclick="showRenameForm(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name'], ENT_QUOTES) ?>')" 
                                        class="btn-edit">
                                        Renommer
                                    </button>
                                    
                                    <?php if ($category['nb_annonces'] == 0): ?>
                                        <form method="POST" action="index.php?action=deleteCategory" style="display: inline;"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                            <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                                            <button type="submit" class="btn-delete"> Supprimer</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted" title="Impossible de supprimer une catégorie avec des annonces">
                                             Supprimer
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="admin-section">
            <h2> Modération des annonces (<?= count($allAnnonces) ?> dernières)</h2>
            
            <?php if (empty($allAnnonces)): ?>
                <p class="empty-message">Aucune annonce à modérer.</p>
            <?php else: ?>
                <div class="admin-list">
                    <?php foreach($allAnnonces as $annonce): ?>
                        <div class="admin-item">
                            <div class="item-image-small">
                                <?php if (!empty($annonce['primary_photo_filename'])): ?>
                                    <img src="public/uploads/<?= htmlspecialchars($annonce['primary_photo_filename']) ?>" 
                                         alt="<?= htmlspecialchars($annonce['title']) ?>">
                                <?php else: ?>
                                    <div class="image-placeholder-tiny">📷</div>
                                <?php endif; ?>
                            </div>
                            <div class="item-info-admin">
                                <h4><?= htmlspecialchars($annonce['title']) ?></h4>
                                <p class="item-meta">
                                    Par <?= htmlspecialchars($annonce['seller_email']) ?> 
                                    • <?= htmlspecialchars($annonce['category_name']) ?>
                                    • <?= number_format($annonce['price'], 2) ?> €
                                </p>
                                <p class="item-status-admin">
                                    Statut : 
                                    <?php
                                    switch($annonce['status']) {
                                        case 'available':
                                            echo '<span class="badge-available">Disponible</span>';
                                            break;
                                        case 'sold':
                                            echo '<span class="badge-sold">Vendu</span>';
                                            break;
                                        case 'confirmed':
                                            echo '<span class="badge-confirmed">Confirmé</span>';
                                            break;
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="item-actions-admin">
                                <a href="index.php?action=detail&id=<?= $annonce['id'] ?>" 
                                   class="btn-view" target="_blank">
                                     Voir
                                </a>
                                <form method="POST" action="index.php?action=deleteAnnonceAdmin"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                    <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                                    <button type="submit" class="btn-delete"> Supprimer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
<section class="admin-section">
            <h2> Gestion des utilisateurs (<?= count($users) ?>)</h2>
            
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Nb d'annonces</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                            <tr>
                                <td><?= $user->getId() ?></td>
                                <td><?= htmlspecialchars($user->getEmail()) ?></td>
                                <td>
                                    <?php if ($user->isAdmin()): ?>
                                        <span class="badge-admin">Admin</span>
                                    <?php else: ?>
                                        <span class="badge-user">Utilisateur</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $userCounts[$user->getId()] ?? 0 ?></td>
                                
                                <td class="actions-cell">
                                    <?php if ($user->getId() == $_SESSION['user']['id']): ?>
                                        <span class="text-muted">C'est vous</span>
                                    <?php elseif ($user->isAdmin()): ?>
                                        <span class="text-muted">Admin</span>
                                    <?php else: ?>
                                        <form method="POST" action="index.php?action=deleteUser"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Toutes ses annonces seront supprimées.');">
                                            <input type="hidden" name="user_id" value="<?= $user->getId() ?>">
                                            <button type="submit" class="btn-delete"> Supprimer</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<div id="renameModal" class="modal">
    <div class="modal-content">
        <h3>Renommer la catégorie</h3>
        <form method="POST" action="index.php?action=renameCategory" id="renameForm">
            <input type="hidden" name="category_id" id="renameCategoryId">
            <div class="form-group">
                <label>Nouveau nom :</label>
                <input 
                    type="text" 
                    name="new_name" 
                    id="renameInput" 
                    required
                    minlength="3"
                    maxlength="50"
                >
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeRenameModal()" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn-primary">Renommer</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRenameForm(categoryId, currentName) {
    document.getElementById('renameCategoryId').value = categoryId;
    document.getElementById('renameInput').value = currentName;
    document.getElementById('renameModal').style.display = 'flex';
}

function closeRenameModal() {
    document.getElementById('renameModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('renameModal');
    if (event.target == modal) {
        closeRenameModal();
    }
}
</script>

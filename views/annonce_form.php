<?php

$pageTitle = 'Déposer une annonce - e-bazar';
include 'views/components/header.php';
?>

<main class="container">
    <div class="forma-container">
        <h1>Déposer une annonce</h1>     
        <form action="index.php?action=createAnnonce" method="POST" enctype="multipart/form-data" class="annonce-form">
            <div class="form-group">
                <label for="category_id" class="required">Catégorie</label>
                <select name="category_id" id="category_id" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?= $category->getId() ?>">
                            <?= htmlspecialchars($category->getName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="title" class="required">Titre (5-30 caractères)</label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    minlength="5" 
                    maxlength="30" 
                    required
                    placeholder="Ex: Clavier mécanique RGB"
                >
                <small class="form-hint">
                    <span id="title-count">0</span>/30 caractères
                </small>
            </div>
            
            <div class="form-group">
                <label for="description" class="required">Description (5-200 caractères)</label>
                <textarea 
                    name="description" 
                    id="description" 
                    minlength="5" 
                    maxlength="200" 
                    rows="6" 
                    required
                    placeholder="Décrivez votre bien en détail..."
                ></textarea>
                <small class="form-hint">
                    <span id="description-count">0</span>/200 caractères
                </small>
            </div>
            
            <div class="form-group">
                <label for="price" class="required">Prix (€)</label>
                <input 
                    type="number" 
                    name="price" 
                    id="price" 
                    step="0.01" 
                    min="0" 
                    required
                    placeholder="0.00"
                >
                <small class="form-hint">Prix en euros (peut être 0 pour un don)</small>
            </div>
            
            <div class="form-group">
                <label class="required">Modes de livraison (au moins un)</label>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="delivery_postal" value="1">
                        <span>📮 Envoi postal</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="delivery_hand" value="1">
                        <span>🤝 Remise en main propre</span>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="photos">Photos (max 5, JPEG, 200 Ko max chacune)</label>
                <input 
                    type="file" 
                    name="photos[]" 
                    id="photos" 
                    accept="image/jpeg"
                    multiple
                >
                <small class="form-hint">La première photo servira de vignette</small>
                <div id="photo-preview" class="photo-preview"></div>
            </div>
            
            <div class="form-actions">
                <a href="index.php" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary">Publier l'annonce</button>
            </div>
        </form>
    </div>
</main>

<script>
document.getElementById('title').addEventListener('input', function() {
    document.getElementById('title-count').textContent = this.value.length;
});

document.getElementById('description').addEventListener('input', function() {
    document.getElementById('description-count').textContent = this.value.length;
});

document.getElementById('photos').addEventListener('change', function() {
    const preview = document.getElementById('photo-preview');
    preview.innerHTML = '';
    
    if (this.files.length > 5) {
        alert('Maximum 5 photos autorisées');
        this.value = '';
        return;
    }
    
    for (let i = 0; i < this.files.length; i++) {
        const file = this.files[i];
        
        if (file.size > 200 * 1024) {
            alert(`La photo "${file.name}" dépasse 200 Ko`);
            this.value = '';
            preview.innerHTML = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'preview-item';
            if (i === 0) {
                div.innerHTML = `<img src="${e.target.result}" alt="Preview"><span class="primary-badge">Principale</span>`;
            } else {
                div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            }
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    }
});
document.querySelector('.annonce-form').addEventListener('submit', function(event) {

    const postal = document.querySelector('input[name="delivery_postal"]').checked;
    const hand = document.querySelector('input[name="delivery_hand"]').checked;

    if (!postal && !hand) {
        event.preventDefault(); 
        alert("Veuillez sélectionner au moins un mode de livraison (Envoi postal ou Remise en main propre).");
    }
});
</script>

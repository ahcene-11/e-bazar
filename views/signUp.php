
    <?php
    $pageTitle = 'Inscription - e-bazar';
    include_once 'views/components/header.php';
    ?>
    <div class="form-container">
    <form action="index.php?action=register" method="POST">
        <div class="form-group">
    <h2>Inscription</h2>
    <label >
        Email:
        <input type="email" name="email" required>
    </label>
    <label >
        Mot de passe:
        <input type="password" name="password" required>
    </label>
    <button type="submit" class="btn-primary">S'inscrire</button>
    </div>
    </form>
    </div>
</body>
</html>
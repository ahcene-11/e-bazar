    <?php
    $pageTitle = 'Connexion - e-bazar';
    include_once 'views/components/header.php';
    ?>
    <main>
        <div class="form-container">
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="index.php?action=login" method="POST">
        <div class="form-group">
        <h2>Connexion</h2>
            <label>Email:
                <input type="email" name="email" required>
            </label>
            
            <label>Mot de passe:
                <input type="password" name="password" required>
            </label>
            
            <button type="submit" class=btn-primary>Se connecter</button>
        </div>
        </form>
        </div>
    </main>
</body>
</html>
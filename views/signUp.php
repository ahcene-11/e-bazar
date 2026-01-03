
    <?php
    $pageTitle = 'Inscription - e-bazar';
    include_once 'views/components/header.php';
    ?>
    <div class="form-container">
    <form action="index.php?action=register" method="POST">
    <h2>Inscription</h2>
    <label >
        Email:
        <input type="email" name="email" required>
    </label>
    <label >
        Mot de passe:
        <input type="password" name="password" required>
    </label>
    <button type="submit">S'inscrire</button>

    </form>
    </div>
</body>
</html>
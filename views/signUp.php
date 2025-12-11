<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <h1>e-bazar</h1>
        <a href="index.php">Retour accueil</a>
    </header>

    <h2>Inscription</h2>
    <form action="index.php?action=do_signUp" method="POST">
    <label for="">
        Email:
        <input type="email" name="email" required>
    </label>
    <label for="">
        Mot de passe:
        <input type="password" name="password" required>
    </label>
    <button type="submit">S'inscrire</button>

    </form>
</body>
</html>
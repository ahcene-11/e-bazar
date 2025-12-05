<?php
// Le prof modifie ces 4 lignes selon son environnement
//$db = mysqli_connect('localhost', 'mysql.admin', 'Super.Admin2025','databaseMySQL' );

define('DB_HOST', 'localhost');
define('DB_NAME', 'mon_projet'); // à adapter
define('DB_USER', 'mysql.admin');       // à adapter
define('DB_PASS', 'Super.Admin2025');    // à adapter

// Connexion PDO (ne pas toucher)
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Erreur connexion BDD : " . $e->getMessage());
}
?>
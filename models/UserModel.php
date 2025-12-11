<?php
require_once 'models/User.php';

/**
 * Classe UserModel - Gère l'accès aux données des utilisateurs dans la BDD
 */
class UserModel {
    private $pdo;
    
    /**
     * Constructeur - reçoit la connexion PDO
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // ========== CRÉATION ==========
    
    /**
     * Créer un nouvel utilisateur
     * @param string $email
     * @param string $password (en clair, sera hashé)
     * @param string $role (par défaut 'user')
     * @return int|false ID du user créé ou false si erreur
     */
    public function create($email, $password, $role = 'user') {
        // 1. Vérifier que l'email n'existe pas déjà
        if ($this->getByEmail($email)) {
            return false; // Email déjà utilisé
        }
        
        // 2. Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // 3. Insérer en BDD
        $sql = "INSERT INTO users (email, password, role, created_at) 
                VALUES (?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        
        if ($stmt->execute([$email, $hashedPassword, $role])) {
            return $this->pdo->lastInsertId(); // Retourne l'ID du nouvel user
        }
        
        return false;
    }
    
    // ========== LECTURE ==========
    
    /**
     * Récupérer un utilisateur par son ID
     * @param int $id
     * @return User|null
     */
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            return User::fromArray($data);
        }
        
        return null;
    }
    
    /**
     * Récupérer un utilisateur par son email
     * @param string $email
     * @return User|null
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            return User::fromArray($data);
        }
        
        return null;
    }
    
    /**
     * Récupérer tous les utilisateurs (pour l'admin)
     * @return array Tableau d'objets User
     */
    public function getAll() {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        
        $users = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = User::fromArray($data);
        }
        
        return $users;
    }
    
    // ========== AUTHENTIFICATION ==========
    
    /**
     * Authentifier un utilisateur
     * @param string $email
     * @param string $password (en clair)
     * @return User|null Retourne l'utilisateur si authentification OK, null sinon
     */
    public function authenticate($email, $password) {
        // 1. Récupérer l'utilisateur avec son mot de passe
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 2. Vérifier que l'utilisateur existe
        if (!$data) {
            return null;
        }
        
        // 3. Vérifier le mot de passe
        if (password_verify($password, $data['password'])) {
            return User::fromArray($data);
        }
        
        return null;
    }
    
    // ========== SUPPRESSION ==========
    
    /**
     * Supprimer un utilisateur
     * @param int $id
     * @return bool True si suppression OK, false sinon
     */
    public function delete($id) {
        // Note: Il faudrait aussi supprimer ses annonces et photos
        // ou utiliser des contraintes CASCADE dans la BDD
        
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([$id]);
    }
    
    // ========== STATISTIQUES (optionnel) ==========
    
    /**
     * Compter le nombre d'annonces d'un utilisateur
     * @param int $userId
     * @return int
     */
    public function countAnnonces($userId) {
        $sql = "SELECT COUNT(*) FROM annonces WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        
        return $stmt->fetchColumn();
    }
}
?>

<?php
/**
 * Classe User - Représente un utilisateur
 * C'est juste un conteneur de données (pas de logique BDD ici)
 */
class User {
    private $id;
    private $email;
    private $role;
    private $createdAt;

    /**
     * Constructeur
     */
    public function __construct($id, $email, $role, $createdAt = null) {
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
        $this->createdAt = $createdAt;
    }

    // ========== GETTERS ==========

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    // ========== MÉTHODES UTILES ==========

    /**
     * Vérifie si l'utilisateur est administrateur
     */
    public function isAdmin() {
        return $this->role === 'admin';
    }

    /**
     * Retourne un tableau associatif (pratique pour les sessions)
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->role,
            'createdAt' => $this->createdAt
        ];
    }

    /**
     * Crée un User depuis un tableau (pratique pour créer depuis la BDD)
     */
    public static function fromArray($data) {
        return new User(
            $data['id'],
            $data['email'],
            $data['role'],
            $data['created_at'] ?? null
        );
    }
}
?>

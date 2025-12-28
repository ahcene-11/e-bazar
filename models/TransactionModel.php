<?php
require_once 'models/Transaction.php';

/**
 * Classe TransactionModel - Gère l'accès aux transactions dans la BDD
 */
class TransactionModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ========== CRÉATION ==========

    /**
     * Créer une transaction (achat)
     */
    public function create($annonceId, $buyerId, $deliveryMode) {
        $sql = "INSERT INTO transactions (annonce_id, buyer_id, delivery_mode, created_at)
                VALUES (?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute([$annonceId, $buyerId, $deliveryMode])) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }

    /**
     * Récupérer une transaction par l'ID de l'annonce
     */
    public function getByAnnonce($annonceId) {
        $sql = "SELECT * FROM transactions WHERE annonce_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$annonceId]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return Transaction::fromArray($data);
        }

        return null;
    }

    /**
     * Récupérer tous les achats d'un utilisateur
     * @param int $buyerId
     * @return array Tableau associatif avec infos annonce + transaction
     */
    public function getByBuyer($buyerId) {
        $sql = "SELECT t.*, a.title, a.price, a.user_id as seller_id,
                       c.name as category_name, u.email as seller_email
                FROM transactions t
                JOIN annonces a ON t.annonce_id = a.id
                JOIN categories c ON a.category_id = c.id
                JOIN users u ON a.user_id = u.id
                WHERE t.buyer_id = ?
                ORDER BY t.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$buyerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer toutes les ventes d'un vendeur (annonces vendues)
     * @param int $sellerId
     * @return array Tableau associatif avec infos annonce + transaction
     */
    public function getBySeller($sellerId) {
        $sql = "SELECT t.*, a.title, a.price, a.user_id as seller_id,
                       c.name as category_name, u.email as buyer_email
                FROM transactions t
                JOIN annonces a ON t.annonce_id = a.id
                JOIN categories c ON a.category_id = c.id
                JOIN users u ON t.buyer_id = u.id
                WHERE a.user_id = ?
                ORDER BY t.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$sellerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ========== MODIFICATION ==========

    /**
     * Confirmer la réception d'un bien (par l'acheteur)
     * @param int $annonceId
     * @return bool
     */
    public function confirmReception($annonceId) {
        // 1. Marquer la transaction comme confirmée
        $sql = "UPDATE transactions SET confirmed = TRUE WHERE annonce_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$annonceId]);

        // 2. Mettre à jour le statut de l'annonce
        $sql2 = "UPDATE annonces SET status = 'confirmed' WHERE id = ?";
        $stmt2 = $this->pdo->prepare($sql2);

        return $stmt2->execute([$annonceId]);
    }
}
?>
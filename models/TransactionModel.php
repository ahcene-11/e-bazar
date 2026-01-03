<?php
require_once 'models/Transaction.php';

class TransactionModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function create($annonceId, $buyerId, $deliveryMode) {
        $sql = "INSERT INTO transactions (annonce_id, buyer_id, delivery_mode, created_at)
                VALUES (?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute([$annonceId, $buyerId, $deliveryMode])) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }
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
    public function confirmReception($annonceId) {
        $sql = "UPDATE transactions SET confirmed = TRUE WHERE annonce_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$annonceId]);
        $sql2 = "UPDATE annonces SET status = 'confirmed' WHERE id = ?";
        $stmt2 = $this->pdo->prepare($sql2);

        return $stmt2->execute([$annonceId]);
    }
}
?>
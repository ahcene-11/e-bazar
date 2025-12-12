<?php
require_once 'models/Annonce.php';

class AnnonceModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getRecent($limit = 4) {
        $sql = "SELECT a.*, c.name as category_name, u.email as seller_email
                FROM annonces a
                JOIN categories c ON a.category_id = c.id
                JOIN users u ON a.user_id = u.id
                WHERE a.status = 'available'
                ORDER BY a.created_at DESC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT a.*, c.name as category_name, u.email as seller_email
                FROM annonces a
                JOIN categories c ON a.category_id = c.id
                JOIN users u ON a.user_id = u.id
                WHERE a.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getByCategory($categoryId, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT a.*, c.name as category_name
                FROM annonces a
                JOIN categories c ON a.category_id = c.id
                WHERE a.category_id = :cat AND a.status = 'available'
                ORDER BY a.created_at DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':cat', (int)$categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function countByCategory($categoryId) {
        $sql = "SELECT COUNT(*) FROM annonces 
                WHERE category_id = ? AND status = 'available'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$categoryId]);
        
        return $stmt->fetchColumn();
    }
    

    public function getByUser($userId, $status = null) {
        if ($status) {
            $sql = "SELECT a.*, c.name as category_name
                    FROM annonces a
                    JOIN categories c ON a.category_id = c.id
                    WHERE a.user_id = ? AND a.status = ?
                    ORDER BY a.created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $status]);
        } else {
            $sql = "SELECT a.*, c.name as category_name
                    FROM annonces a
                    JOIN categories c ON a.category_id = c.id
                    WHERE a.user_id = ?
                    ORDER BY a.created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
   
    public function create($userId, $categoryId, $title, $description, $price, $deliveryPostal, $deliveryHand) {
        $sql = "INSERT INTO annonces 
                (user_id, category_id, title, description, price, delivery_postal, delivery_hand, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'available', NOW())";
        $stmt = $this->pdo->prepare($sql);
        
        if ($stmt->execute([$userId, $categoryId, $title, $description, $price, $deliveryPostal, $deliveryHand])) {
            return $this->pdo->lastInsertId();
        }
        
        return false;
    }
    
    public function updateStatus($id, $status) {
        $sql = "UPDATE annonces SET status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([$status, $id]);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM annonces WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([$id]);
    }
}
?>
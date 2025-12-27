<?php
require_once 'models/Photo.php';

class PhotoModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($annonceId, $filename, $isPrimary = false) {
        $sql = "INSERT INTO photos (annonce_id, filename, is_primary) 
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $isPrimaryInt = $isPrimary ? 1 : 0;
        
        if ($stmt->execute([$annonceId, $filename, $isPrimaryInt])) {
            return $this->pdo->lastInsertId();
        }
        
        return false;
    }
    
    public function getByAnnonce($annonceId) {
        $sql = "SELECT * FROM photos 
                WHERE annonce_id = ? 
                ORDER BY is_primary DESC, id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$annonceId]);
        
        $photos = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $photos[] = Photo::fromArray($data);
        }
        
        return $photos;
    }

    public function getPrimaryByAnnonce($annonceId) {
        $sql = "SELECT * FROM photos 
                WHERE annonce_id = ? AND is_primary = TRUE 
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$annonceId]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            return Photo::fromArray($data);
        }
        
        return null;
    }
    
    
    public function deleteByAnnonce($annonceId) {
        $sql = "DELETE FROM photos WHERE annonce_id = ?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([$annonceId]);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM photos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([$id]);
    }
}
?>
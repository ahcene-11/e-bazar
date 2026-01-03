<?php
require_once 'models/User.php';

class UserModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function create($email, $password, $role = 'user') {
        
        if ($this->getByEmail($email)) {
            return false; 
        }
        
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (email, password, role) 
                VALUES (?, ?, ?)";
        $pre_req = $this->pdo->prepare($sql);
        
        if ($pre_req->execute([$email, $hashedPassword, $role])) {
            return $this->pdo->lastInsertId(); 
        }
        
        return false;
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $pre_req = $this->pdo->prepare($sql);
        $pre_req->execute([$id]);
        
        $data = $pre_req->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            return User::fromArray($data);
        }
        
        return null;
    }
    
    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $pre_req = $this->pdo->prepare($sql);
        $pre_req->execute([$email]);
        
        $data = $pre_req->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            return User::fromArray($data);
        }
        
        return null;
    }
    
    public function getAll() {
        $sql = "SELECT * FROM users";
        $pre_req = $this->pdo->query($sql);
        
        $users = [];
        while ($data = $pre_req->fetch(PDO::FETCH_ASSOC)) {
            $users[] = User::fromArray($data);
        }
        
        return $users;
    }
    
    public function authenticate($email, $password) {
        
        $sql = "SELECT * FROM users WHERE email = ?";
        $pre_req = $this->pdo->prepare($sql);
        $pre_req->execute([$email]);
        
        $data = $pre_req->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }
        
        if (password_verify($password, $data['password'])) {
            return User::fromArray($data);
        }
        
        return null;
    }
    

    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $pre_req = $this->pdo->prepare($sql);
        
        return $pre_req->execute([$id]);
    }
    
 
    public function countAnnonces($userId) {
        $sql = "SELECT COUNT(*) FROM annonces WHERE user_id = ?";
        $pre_req = $this->pdo->prepare($sql);
        $pre_req->execute([$userId]);
        
        return $pre_req->fetchColumn();
    }
}
?>

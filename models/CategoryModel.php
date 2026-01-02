<?php
require_once 'models/Category.php';

class CategoryModel{
    private $pdo;

    public function __construct($pdo){
        $this->pdo= $pdo;
    }
    //retourne un tableau d'objets Category
    public function getAll(){
        $req= "SELECT * from categories order by name";
        $query = $this->pdo->query($req);

        $categories = [];
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $categories[] =Category::fromArray($data);
        }
        return $categories;
    }

    public function getAllWithCount(){
        $sql ="SELECT c.id, c.name,(SELECT COUNT(*) 
                                    FROM annonces 
                                    WHERE category_id = c.id 
                                    AND status = 'available'
                                    ) AS nb_annonces
                FROM categories c
                ORDER BY c.name";
        $query = $this->pdo->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id){
        $req = "SELECT * from categories where id = ?";
        $pre_req = $this->pdo->prepare($req);
        $pre_req->execute([$id]);
        $data = $pre_req->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            return Category::fromArray($data);
        }
        
        return null;
    }

    //fonctions pour admin 

    public function create($name){
        $req= "INSERT into categories (name) values (?)";
        $pre_req=$this->pdo->prepare($req);
        if ($pre_req->execute([$name])) {
            return $this->pdo->lastInsertId();
        }
        
        return false;
    }

    public function rename($id,$newName){
        $req= "UPDATE categories set name = ? where id = ? ";
        $pre_req= $this->pdo->prepare($req);
        return $pre_req->execute([$newName, $id]);
    }
    // ========== STATISTIQUES ==========

/**
 * Compter le nombre d'annonces dans une catégorie
 * @param int $id
 * @return int
 */
public function countAnnonces($id) {
    $sql = "SELECT COUNT(*) FROM annonces WHERE category_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id]);
    
    return $stmt->fetchColumn();
}

// ========== SUPPRESSION ==========

/**
 * Supprimer une catégorie
 * @param int $id
 * @return bool
 */
public function delete($id) {
    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    
    return $stmt->execute([$id]);
}
}

?>
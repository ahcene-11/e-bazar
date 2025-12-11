<?php


class Annonce {
    private $id;
    private $userId;
    private $categoryId;
    private $title;
    private $description;
    private $price;
    private $deliveryPostal;
    private $deliveryHand;
    private $status;
    private $createdAt;
    
    public function __construct($id, $userId, $categoryId, $title, $description, $price, $deliveryPostal, $deliveryHand, $status, $createdAt = null) {
        $this->id = $id;
        $this->userId = $userId;
        $this->categoryId = $categoryId;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->deliveryPostal = $deliveryPostal;
        $this->deliveryHand = $deliveryHand;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }
    
    
    public function getId(){
        return $this->id;
    }
    
    public function getUserId(){
        return $this->userId;
    }
    
    public function getCategoryId(){
        return $this->categoryId;
    }
    
    public function getTitle(){
        return $this->title;
    }
    
    public function getDescription(){
        return $this->description;
    }
    
    public function getPrice(){
        return $this->price;
    }
    
    public function getDeliveryPostal(){
        return $this->deliveryPostal;
    }
    
    public function getDeliveryHand(){
        return $this->deliveryHand;
    }
    
    public function getStatus(){
        return $this->status;
    }
    
    public function getCreatedAt(){
        return $this->createdAt;
    }
    
    
    public function isAvailable(){
        return $this->status === 'available';
    }
    
    public function isSold(){
        return $this->status === 'sold';
    }
    
    public function getFormattedPrice(){
        return number_format($this->price, 2, ',', ' ') . ' €';
    }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'delivery_postal' => $this->deliveryPostal,
            'delivery_hand' => $this->deliveryHand,
            'status' => $this->status,
            'created_at' => $this->createdAt
        ];
    }
    
    public static function fromArray($data) {
        return new Annonce(
            $data['id'],
            $data['user_id'],
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['price'],
            $data['delivery_postal'],
            $data['delivery_hand'],
            $data['status'],
            $data['created_at'] ?? null
        );
    }
}
?>
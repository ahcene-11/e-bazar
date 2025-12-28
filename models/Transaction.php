<?php
/**
 * Classe Transaction - Représente une transaction d'achat
 */
class Transaction {
    private $id;
    private $annonceId;
    private $buyerId;
    private $deliveryMode;
    private $confirmed;
    private $createdAt;

    public function __construct($id, $annonceId, $buyerId, $deliveryMode, $confirmed, $createdAt = null) {
        $this->id = $id;
        $this->annonceId = $annonceId;
        $this->buyerId = $buyerId;
        $this->deliveryMode = $deliveryMode;
        $this->confirmed = $confirmed;
        $this->createdAt = $createdAt;
    }

    // ========== GETTERS ==========

    public function getId() {
        return $this->id;
    }

    public function getAnnonceId() {
        return $this->annonceId;
    }

    public function getBuyerId() {
        return $this->buyerId;
    }

    public function getDeliveryMode() {
        return $this->deliveryMode;
    }

    public function isConfirmed() {
        return $this->confirmed;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }


    public function getDeliveryModeLabel() {
        return $this->deliveryMode === 'postal' ? '📮 Envoi postal' : '🤝 Remise en main propre';
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'annonce_id' => $this->annonceId,
            'buyer_id' => $this->buyerId,
            'delivery_mode' => $this->deliveryMode,
            'confirmed' => $this->confirmed,
            'created_at' => $this->createdAt
        ];
    }

    public static function fromArray($data) {
        return new Transaction(
            $data['id'],
            $data['annonce_id'],
            $data['buyer_id'],
            $data['delivery_mode'],
            $data['confirmed'],
            $data['created_at'] ?? null
        );
    }
}
?>
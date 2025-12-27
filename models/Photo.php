<?php

class Photo {
    private $id;
    private $annonceId;
    private $filename;
    private $isPrimary;
    
    public function __construct($id, $annonceId, $filename, $isPrimary) {
        $this->id = $id;
        $this->annonceId = $annonceId;
        $this->filename = $filename;
        $this->isPrimary = $isPrimary;
    }

    
    public function getId() {
        return $this->id;
    }
    
    public function getAnnonceId() {
        return $this->annonceId;
    }
    
    public function getFilename() {
        return $this->filename;
    }
    
    public function getIsPrimary() {
        return $this->isPrimary;
    }
    
    public function getPath() {
        return 'public/uploads/' . $this->filename;
    }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'annonce_id' => $this->annonceId,
            'filename' => $this->filename,
            'is_primary' => $this->isPrimary
        ];
    }
    
    public static function fromArray($data) {
        return new Photo(
            $data['id'],
            $data['annonce_id'],
            $data['filename'],
            $data['is_primary']
        );
    }
}
?>
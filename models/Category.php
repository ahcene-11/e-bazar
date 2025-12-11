<?php

class Category {
    private $id;
    private $name;

    public function __construct(){
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    //UTILS
    public function toArray(){
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
    public static function fromArray($data){
        return new Category(
            $data['id'],
            $data['name']
        );
    }
}


?>
<?php

class User {
    private $id;
    private $email;
    private $role;


    public function __construct($id, $email, $role) {
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
    }


    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    //UTILS
    public function isAdmin() {
        return $this->role === 'admin';
    }


    public function toArray() {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->role
        ];
    }

    public static function fromArray($data) {
        return new User(
            $data['id'],
            $data['email'],
            $data['role']
        );
    }
}
?>

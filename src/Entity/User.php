<?php

class User
{
    private ?int $id = null;
    private string $username;
    private string $email;
    private string $password;
    private string $statut;
    private string $role;

    public function __construct(
        $username,
        $email,
        $password,
        $statut = 'waiting',
        $role = 'Garden'
    ) {
        $this->username = $username;
        $this->email    = $email;
        $this->password = $password;
        $this->statut   = $statut;
        $this->role     = $role;
    }

    public function getId(){
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getStatut(){
        return $this->statut;
    }

    public function getRole(){
        return $this->role;
    }
}


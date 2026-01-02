<?php
class User
{
    protected int|null $id;
    protected string $username;
    protected string $password;
    protected string $email;
    protected string $statu;
    protected string $role;

    public function __construct($id=null,$username, $password, $email, $statu, $role)
    {
        $this->username = $username;
    }
    public function __get($proprty)
    {
        return $this->$proprty;
    }
    public function __set($proprty,$value){
        $this->$proprty=$value;
    }
}

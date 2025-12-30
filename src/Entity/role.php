<?php
class role{
    private int|null $id;
    private string $statu;

    public function __construct($id=null,$statu) {
        $this->id=$id;
        $this->statu=$statu;
    }
    public function __get($proprty)
    {
        return $this->$proprty;
    }
    public function __set($proprty, $value)
    {
        $this->$proprty=$value;
    }
}

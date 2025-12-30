<?php
class Note{
    private int|null $id ;
    private string $titre;
    private string $importance;
    private string $contenu;
    private DateTime $dateCreation;

    public function __construct($id=null,$titre,$importance,$contenu,$dateCreation) {
        $this->id = $id;
        $this->titre=$titre;
        $this->importance=$importance;
        $this->contenu=$contenu;
        $this->dateCreation=$dateCreation;
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
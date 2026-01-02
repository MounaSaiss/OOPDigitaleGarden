<?php
class Theme{
    private ?int $id ;
    private string $nom;
    private string $badgeCouleur;
    private ?string $tags;
    private int $nombersNotes;

    public function __construct(string $nom,string $badgeCouleur,$id=null,?string $tags=null,int $nombersNotes=0) {
        $this->nom=$nom;
        $this->badgeCouleur=$badgeCouleur;
        $this->id = $id;
        $this->tags=$tags;
        $this->nombersNotes=$nombersNotes;
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
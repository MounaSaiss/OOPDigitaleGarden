<?php
class Theme{
    private int|null $id ;
    private string $nom;
    private string $badgeCleur;
    private string $tags;
    private int $nombersNotes;

    public function __construct($id=null,$nom,$badgeCleur,$tags,$nombersNotes) {
        $this->id = $id;
        $this->nom=$nom;
        $this->badgeCleur=$badgeCleur;
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
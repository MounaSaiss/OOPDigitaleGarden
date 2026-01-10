<?php
class Note{
    private ?int $id ;
    private string $titre;
    private string $importance;
    private string $contenu;
    private DateTime $dateCreation;
    private int $id_theme;
    private ?string $theme ;
    private ?string $color;
    
    public function __construct($id=null,$titre,$importance,$contenu,$dateCreation,$id_theme,$theme=null,$color=null) {
        $this->id = $id;
        $this->titre=$titre;
        $this->importance=$importance;
        $this->contenu=$contenu;
        $this->dateCreation=$dateCreation;
        $this->id_theme = $id_theme;
        $this->theme=$theme;
        $this->color=$color;
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
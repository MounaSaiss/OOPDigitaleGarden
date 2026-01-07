<?php
class Signalement
{
    private int $id;
    private string $type;
    private string $raison;
    private string $statut;
    private ?int $idNote;
    private ?int $idUser;
    private ?int $idUserReported;

    public function __construct(
        string $type,
        string $raison,
        string $statut = 'waiting',
        ?int $idNote = null,
        ?int $idUser = null,
        ?int $idUserReported = null
    ) {
        $this->type = $type;
        $this->raison = $raison;
        $this->statut = $statut;
        $this->idNote = $idNote;
        $this->idUser = $idUser;
        $this->idUserReported = $idUserReported;
    }
    // geeters
    public function getId(): int
    {
        return $this->id;
    }
    public function getType(): string
    {
        return $this->type;
    }
    public function getRaison(): string
    {
        return $this->raison;
    }
    public function getStatut(): string
    {
        return $this->statut;
    }
    public function getIdNote(): ?int
    {
        return $this->idNote;
    }
    public function getIdUser(): ?int
    {
        return $this->idUser;
    }
    public function getIdUserReported(): ?int
    {
        return $this->idUserReported;
    }
// setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }
    
}

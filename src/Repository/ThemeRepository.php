<?php
require_once '../../config/database.php';
require_once 'RepositoryInterface.php';
require_once '../Entity/Theme.php';

class ThemeRepository implements RepositoryInterface
{
    private  PDO $conn;
    private  int $idUser;
    public function __construct(PDO $conn, int $idUser)
    {
        $this->conn = $conn;
        $this->idUser = $idUser;
    }                  

    public function findAll()
    {
        $query = "SELECT t.id,t.nom,t.badgeCouleur,t.tags, COUNT(n.id) 
        AS total_notes FROM theme AS t LEFT JOIN note AS n ON n.id_theme = t.id
        WHERE t.id_user = :id_user
        GROUP BY t.id;
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([':id_user' => $this->idUser]);

        $result = $stmt->fetchAll();

        $themes = [];

        foreach ($result as $item) {
            $themes[] = new Theme($item['id'], $item['nom'], $item['badgeCouleur'], $item['tags'], $item['total_notes']);
        }
        return $themes;
    }

    public function find(int $id)
    {
        $query = "SELECT t.id,t.nom,t.badgeCouleur,t.tags, COUNT(n.id) 
        AS total_notes FROM theme AS t LEFT JOIN note AS n ON n.id_theme = t.id
        WHERE  t.id = :id
        GROUP BY t.id;
        ";
        $stmt = $this->conn->prepare($query);

        $stmt->execute([':id' => $id]);

        $result = $stmt->fetch();

        if ($result) {
            return new Theme($result['id'], $result['nom'], $result['badgeCouleur'], $result['tags'], $result['total_notes']);
        }

        return null;
    }
    public function add(object $entity)
    {
        $query = "INSERT INTO theme(nom, badgeCouleur, tags,id_user) VALUES (:nom,:color,:tags,:userId)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':nom' => $entity->nom, ':color' => $entity->badgeCouleur, ':tags' => $entity->tags, ':userId' => $this->idUser]);
    }

    public function edit(object $entity) {}

    public function update(object $entity)
    : bool{
        $query = "UPDATE theme 
                    SET nom = :nom, badgeCouleur = :color,tags=:tags,nombersNotes=:nombersNotes
                    WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            'nom'   => $entity->name,
            'color' => $entity->badgeCleur,
            'tags'  =>$entity->tags,
            'nombersNotes'=>$entity->nombersNotes
        ]);
    }
    public function delete(object $entity) {}
}



























    // private $conn;

    // // private $clientRepository; 

    // public function __construct()
    // {
    //     $this->conn = new Database()->getConnection();
    //     // $this->clientRepository = new ClientRepository();
    // }
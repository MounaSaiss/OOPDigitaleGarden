<?php
require_once __DIR__ . '/RepositoryInterface.php';
require_once __DIR__ . '/../Entity/Theme.php';

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
            $themes[] = new Theme( $item['nom'], $item['badgeCouleur'],$item['id'], $item['tags'], $item['total_notes']);
        }
        return $themes;
    }

    public function find(int $id)
    {
        $query = "SELECT t.id,t.nom,t.badgeCouleur,t.tags, COUNT(n.id) 
        AS total_notes FROM theme AS t LEFT JOIN note AS n ON n.id_theme = t.id
        WHERE  t.id = :id AND t.id_user = :id_user
        GROUP BY t.id;
        ";
        $stmt = $this->conn->prepare($query);

        $stmt->execute([':id' => $id , ':id_user'=>$this->idUser]);

        $result = $stmt->fetch();

        if ($result) {
            return new Theme( $result['nom'], $result['badgeCouleur'],$result['id'], $result['tags'], $result['total_notes']);
        }
        return null;
    }
    public function add(object $entity)
    {
        $query = "INSERT INTO theme(nom, badgeCouleur, tags,id_user) VALUES (:nom,:color,:tags,:userId)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':nom' => $entity->nom, ':color' => $entity->badgeCouleur, ':tags' => $entity->tags, ':userId' => $this->idUser]);
    }

    public function update(object $entity): bool
    {
        $query = "UPDATE theme 
                    SET nom = :nom, badgeCouleur = :color,tags=:tags
                    WHERE id = :id AND id_user =:id_user" ;

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            'nom'   => $entity->nom,
            'color' => $entity->badgeCouleur,
            'tags'  => $entity->tags,
            'id'=>$entity->id,
            ':id_user'=>$this->idUser,
        ]);
    }
    
    public function delete(int $id): bool
    {
        $query = "DELETE FROM theme 
                WHERE id = :id ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' =>$id
        ]);
    }
}

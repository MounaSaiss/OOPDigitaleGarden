<?php
require_once __DIR__ . '/RepositoryInterface.php';
require_once __DIR__ . '/../Entity/Note.php';

class NoteRepository implements RepositoryInterface
{
    private PDO $conn;
    private int $idUser;

    public function __construct(PDO $conn, int $idUser)
    {
        $this->conn = $conn;
        $this->idUser = $idUser;
    }

    public function findAll()
    {
        $query = "SELECT n.id, n.titre, n.importance, n.contenu, n.dateCreation,n.id_theme,
                t.nom AS theme, t.badgeCouleur AS color
                FROM note AS n
                LEFT JOIN theme AS t ON t.id = n.id_theme
                ORDER BY n.dateCreation DESC";
        
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $result=$stmt->fetchAll();
        $notes = [];
        foreach ($result as $item) {
            $notes[] = new Note(
                $item['id'],
                $item['titre'],
                $item['importance'],
                $item['contenu'],
                new DateTime($item['dateCreation']),
                $item['id_theme'],
                $item['theme'],
                $item['color']
            );
        }
        return $notes;
    }

    public function find(int $id)
    {
        $query = "SELECT * FROM note WHERE id = :id AND id_theme = :id_theme LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':id' => $id,
            ':id_theme' => $this->idUser
        ]);

        $result = $stmt->fetch();

        if ($result) {
            return new Note(
                $result['id'],
                $result['titre'],
                $result['importance'],
                $result['contenu'],
                new DateTime($result['dateCreation']),
                $result['theme'],
                $result['color']
            );
        }
        return null;
    }

    public function add(object $note): bool
    {
        $query = "INSERT INTO note (titre, importance, contenu, dateCreation, id_theme)
                VALUES (:titre, :importance, :contenu, :dateCreation, :id_theme)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':titre' => $note->titre,
            ':importance' => $note->importance,
            ':contenu' => $note->contenu,
            ':dateCreation' => $note->dateCreation->format('Y-m-d H:i:s'),
            ':id_theme' => $note->id_theme ?? null,
        ]);
    }

    public function update(object $note): bool
    {
        $query = "UPDATE note SET titre = :titre, importance = :importance, contenu = :contenu, 
                    dateCreation = :dateCreation, id_theme = :id_theme 
                    WHERE id = :id AND id_theme = :id_theme";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':titre' => $note->titre,
            ':importance' => $note->importance,
            ':contenu' => $note->contenu,
            ':dateCreation' => $note->dateCreation->format('Y-m-d H:i:s'),
            ':id_theme' => $note->id_theme ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM note
            WHERE id = :id ";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}

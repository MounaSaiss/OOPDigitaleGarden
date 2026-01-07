<?php

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert(User $user)
    {
        $sql = "INSERT INTO users (username, email, password, statut, role,dateInscription)
        VALUES (?, ?, ?, ?, ?,NOW()) ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $user->getUsername(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getStatut(),
            $user->getRole()
        ]);
    }
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    public function findAll()
    {
        $query="SELECT id, username, email, statut, role FROM users";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: [];
    }
    public function updateStatut(int $id, string $statut)
    {
        $sql = "UPDATE users SET statut = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$statut, $id]);
    }
}


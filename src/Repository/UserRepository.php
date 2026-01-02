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
        $sql = "INSERT INTO users (username, email, password, statut, role)
        VALUES (?, ?, ?, ?, ?)";

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
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function findAll()
    {
        $stmt = $this->pdo->query("SELECT id, username, email, statut, role FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateStatut(int $id,$statut)
    {
        $sql = "UPDATE users SET statut = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$statut, $id]);
    }
}

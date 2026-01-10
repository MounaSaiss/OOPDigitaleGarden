<?php
// src/Repository/SignalementRepository.php

require_once __DIR__ . '/../Entity/Signalement.php';

class SignalementRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Créer un signalement
    public function create(Signalement $signalement): bool
    {
        $sql = "INSERT INTO Signalement (
            type, raison, statut, id_note, id_user, id_user_reported
        ) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            $signalement->getType(),
            $signalement->getRaison(),
            $signalement->getStatut(),
            $signalement->getIdNote(),
            $signalement->getIdUser(),
            $signalement->getIdUserReported()
        ]);
    }

    // Récupérer tous les signalements avec infos utilisateurs
    public function findAllWithDetails(): array
    {
        $sql = "SELECT 
            s.*,
            u1.username as reporter_username,
            u1.email as reporter_email,
            u2.username as reported_username,
            u2.email as reported_email,
            n.titre as note_titre
        FROM Signalement s
        LEFT JOIN users u1 ON s.id_user = u1.id
        LEFT JOIN users u2 ON s.id_user_reported = u2.id
        LEFT JOIN note n ON s.id_note = n.id";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les signalements par statut
    public function findByStatut(string $statut): array
    {
        $sql = "SELECT 
            s.*,
            u1.username as reporter_username,
            u2.username as reported_username,
            n.titre as note_titre
        FROM Signalement s
        LEFT JOIN users u1 ON s.id_user = u1.id
        LEFT JOIN users u2 ON s.id_user_reported = u2.id
        LEFT JOIN note n ON s.id_note = n.id
        WHERE s.statut = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$statut]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Traiter un signalement (ignorer/archiver)
    public function traiterSignalement(int $id, string $statut, int $adminId): bool
    {
        $sql = "UPDATE Signalement 
                SET statut = ?, 
                WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$statut,$id]);
    }
    // Compter les signalements par statut
    public function countByStatut(): array
    {
        $sql = "SELECT statut, COUNT(*) as count 
                FROM Signalement 
                GROUP BY statut";
        $stmt = $this->pdo->query($sql);
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $counts = ['waiting' => 0, 'accepted' => 0, 'refused' => 0];
        
        foreach ($result as $row) {
            $counts[$row['statut']] = (int)$row['count'];
        }
        
        return $counts;
    }
    // Récupérer un signalement par ID
    public function findById(int $id): ?array
    {
        $sql = "SELECT 
            s.*,
            u1.username as reporter_username,
            u1.email as reporter_email,
            u2.username as reported_username,
            u2.email as reported_email,
            n.titre as note_titre,
            n.contenu as note_contenu
        FROM Signalement s
        LEFT JOIN users u1 ON s.id_user = u1.id
        LEFT JOIN users u2 ON s.id_user_reported = u2.id
        LEFT JOIN note n ON s.id_note = n.id
        WHERE s.id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $signalement = $stmt->fetch(PDO::FETCH_ASSOC);
        return $signalement ?: null;
    }
}
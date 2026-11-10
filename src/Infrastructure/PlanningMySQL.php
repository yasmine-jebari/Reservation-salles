<?php
class PlanningMySQL implements PlanningStore {
    private const FORMAT = 'Y-m-d H:i:s';

    public function trouverSalle(int $salleId): ?array {
        $requete = Database::connect()->prepare(
            'SELECT id, name, is_active FROM rooms WHERE id = :id'
        );
        $requete->execute([':id' => $salleId]);
        $salle = $requete->fetch();
        return $salle ?: null;
    }

    public function reservationsEnConflit(
        int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin
    ): int {
        $requete = Database::connect()->prepare(
            "SELECT COUNT(*) FROM reservations
             WHERE room_id = :salle
               AND status = 'confirmed'
               AND starts_at < :fin
               AND ends_at   > :debut"
        );
        $requete->execute([
            ':salle' => $salleId,
            ':debut' => $debut->format(self::FORMAT),
            ':fin'   => $fin->format(self::FORMAT),
        ]);
        return (int) $requete->fetchColumn();
    }

    public function maintenancesEnConflit(
        int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin
    ): int {
        $requete = Database::connect()->prepare(
            'SELECT COUNT(*) FROM maintenance_blocks
             WHERE room_id = :salle
               AND starts_at < :fin
               AND ends_at   > :debut'
        );
        $requete->execute([
            ':salle' => $salleId,
            ':debut' => $debut->format(self::FORMAT),
            ':fin'   => $fin->format(self::FORMAT),
        ]);
        return (int) $requete->fetchColumn();
    }
        public function commencerTransaction(): void
    {
        Database::connect()->beginTransaction();
    }

    public function validerTransaction(): void
    {
        Database::connect()->commit();
    }

    public function annulerTransaction(): void
    {
        $pdo = Database::connect();
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
    }

    public function verrouillerSalle(int $salleId): ?array
    {
        $requete = Database::connect()->prepare(
            'SELECT id, name, is_active FROM rooms WHERE id = :id FOR UPDATE'
        );
        $requete->execute([':id' => $salleId]);
        $salle = $requete->fetch();

        return $salle === false ? null : $salle;
    }

    public function creerReservation(int $utilisateurId, int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin): int
    {
        $pdo = Database::connect();
        $requete = $pdo->prepare(
            "INSERT INTO reservations (user_id, room_id, starts_at, ends_at, status)
             VALUES (:user_id, :room_id, :debut, :fin, 'confirmed')"
        );
        $requete->execute([
            ':user_id' => $utilisateurId,
            ':room_id' => $salleId,
            ':debut'   => $debut->format(self::FORMAT),
            ':fin'     => $fin->format(self::FORMAT),
        ]);

        return (int) $pdo->lastInsertId();
    }
}
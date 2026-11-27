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
        public function reservationsDeUtilisateur(int $utilisateurId): array
    {
        $requete = Database::connect()->prepare(
            'SELECT r.id, r.room_id, rooms.name AS salle, r.starts_at, r.ends_at, r.status
             FROM reservations r
             JOIN rooms ON rooms.id = r.room_id
             WHERE r.user_id = :user_id
             ORDER BY r.starts_at DESC'
        );
        $requete->execute([':user_id' => $utilisateurId]);

        return $requete->fetchAll();
    }
        public function trouverReservation(int $reservationId): ?array
    {
        $requete = Database::connect()->prepare(
            'SELECT id, user_id, room_id, starts_at, ends_at, status
             FROM reservations WHERE id = :id'
        );
        $requete->execute([':id' => $reservationId]);
        $ligne = $requete->fetch();

        return $ligne === false ? null : $ligne;
    }

    public function marquerAnnulee(int $reservationId): void
    {
        $requete = Database::connect()->prepare(
            "UPDATE reservations SET status = 'cancelled' WHERE id = :id"
        );
        $requete->execute([':id' => $reservationId]);
    }
        public function creerMaintenance(int $adminId, int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin, string $motif): int
    {
        $pdo = Database::connect();
        $requete = $pdo->prepare(
            'INSERT INTO maintenance_blocks (room_id, created_by, starts_at, ends_at, reason)
             VALUES (:room_id, :created_by, :debut, :fin, :motif)'
        );
        $requete->execute([
            ':room_id'    => $salleId,
            ':created_by' => $adminId,
            ':debut'      => $debut->format(self::FORMAT),
            ':fin'        => $fin->format(self::FORMAT),
            ':motif'      => $motif,
        ]);

        return (int) $pdo->lastInsertId();
    }

        public function occupationsDansPeriode(DateTimeImmutable $debut, DateTimeImmutable $fin): array
    {
        $pdo = Database::connect();
        $requete = $pdo->prepare(
            "SELECT 'reservation' AS type, rm.name AS room_name, r.starts_at, r.ends_at, u.name AS detail
             FROM reservations r
             JOIN rooms rm ON rm.id = r.room_id
             JOIN users u ON u.id = r.user_id
             WHERE r.status = 'confirmed' AND r.starts_at < :fin1 AND r.ends_at > :debut1
             UNION ALL
             SELECT 'maintenance' AS type, rm.name AS room_name, m.starts_at, m.ends_at, m.reason AS detail
             FROM maintenance_blocks m
             JOIN rooms rm ON rm.id = m.room_id
             WHERE m.starts_at < :fin2 AND m.ends_at > :debut2
             ORDER BY room_name, starts_at"
        );
        $requete->execute([
            ':fin1'   => $fin->format(self::FORMAT),
            ':debut1' => $debut->format(self::FORMAT),
            ':fin2'   => $fin->format(self::FORMAT),
            ':debut2' => $debut->format(self::FORMAT),
        ]);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
        public function occupationParSalle(DateTimeImmutable $debut, DateTimeImmutable $fin): array
    {
        $pdo = Database::connect();
        $params = [
            ':debut' => $debut->format(self::FORMAT),
            ':fin'   => $fin->format(self::FORMAT),
        ];

        $salles = $pdo->query('SELECT id, name FROM rooms WHERE is_active = 1 ORDER BY name')
                      ->fetchAll(PDO::FETCH_ASSOC);

        $requete = $pdo->prepare(
            "SELECT room_id, starts_at, ends_at FROM reservations
             WHERE status = 'confirmed' AND starts_at < :fin AND ends_at > :debut"
        );
        $requete->execute($params);
        $reservations = $requete->fetchAll(PDO::FETCH_ASSOC);

        $requete = $pdo->prepare(
            'SELECT room_id, starts_at, ends_at FROM maintenance_blocks
             WHERE starts_at < :fin AND ends_at > :debut'
        );
        $requete->execute($params);
        $maintenances = $requete->fetchAll(PDO::FETCH_ASSOC);

        return ['salles' => $salles, 'reservations' => $reservations, 'maintenances' => $maintenances];
    }
}

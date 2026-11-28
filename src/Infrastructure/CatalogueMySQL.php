
<?php

require_once __DIR__ . '/../Application/CatalogueStore.php';
require_once __DIR__ . '/Database.php';

class CatalogueMySQL implements CatalogueStore
{
    public function listerSalles(): array
    {
        $requete = Database::connect()->prepare(
            "SELECT id, name, capacity, building, floor
             FROM rooms
             WHERE is_active = 1
             ORDER BY name"
        );

        $requete->execute();

        return $requete->fetchAll();
    }

    public function trouverSalle(int $salleId): ?array
    {
        $requete = Database::connect()->prepare(
            "SELECT id, name, capacity, building, floor
             FROM rooms
             WHERE id = :id AND is_active = 1"
        );

        $requete->execute([':id' => $salleId]);

        $salle = $requete->fetch();

        return $salle ?: null;
    }

    public function rechercherSalles(
        ?int $capacite,
        ?string $batiment,
        ?int $equipementId
    ): array {

        $sql = "SELECT DISTINCT rooms.id, rooms.name,
                       rooms.capacity, rooms.building, rooms.floor
                FROM rooms
                LEFT JOIN equipment_room
                    ON rooms.id = equipment_room.room_id
                WHERE rooms.is_active = 1";

        $parametres = [];

        if ($capacite !== null) {
            $sql .= " AND rooms.capacity >= :capacite";
            $parametres[':capacite'] = $capacite;
        }

        if ($batiment !== null && $batiment !== '') {
            $sql .= " AND rooms.building = :batiment";
            $parametres[':batiment'] = $batiment;
        }

        if ($equipementId !== null) {
            $sql .= " AND equipment_room.equipment_id = :equipement";
            $parametres[':equipement'] = $equipementId;
        }

        $sql .= " ORDER BY rooms.name";

        $requete = Database::connect()->prepare($sql);
        $requete->execute($parametres);

        return $requete->fetchAll();
    }
    public function listerToutesLesSalles(): array
    {
        $requete = Database::connect()->prepare(
            "SELECT id, name, capacity, building, floor, is_active
             FROM rooms
             ORDER BY name"
        );

        $requete->execute();

        return $requete->fetchAll();
    }

    public function trouverSalleAdmin(int $salleId): ?array
    {
        $requete = Database::connect()->prepare(
            "SELECT id, name, capacity, building, floor, is_active
             FROM rooms
             WHERE id = :id"
        );

        $requete->execute([':id' => $salleId]);

        $salle = $requete->fetch();

        return $salle ?: null;
    }

    public function creerSalle(string $nom, int $capacite, ?string $batiment, ?int $etage): int
    {
        $pdo = Database::connect();

        $requete = $pdo->prepare(
            "INSERT INTO rooms (name, capacity, building, floor)
             VALUES (:nom, :capacite, :batiment, :etage)"
        );

        $requete->execute([
            ':nom'      => $nom,
            ':capacite' => $capacite,
            ':batiment' => $batiment,
            ':etage'    => $etage,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public function modifierSalle(int $salleId, string $nom, int $capacite, ?string $batiment, ?int $etage): void
    {
        $requete = Database::connect()->prepare(
            "UPDATE rooms
             SET name = :nom, capacity = :capacite, building = :batiment, floor = :etage
             WHERE id = :id"
        );

        $requete->execute([
            ':nom'      => $nom,
            ':capacite' => $capacite,
            ':batiment' => $batiment,
            ':etage'    => $etage,
            ':id'       => $salleId,
        ]);
    }
    }

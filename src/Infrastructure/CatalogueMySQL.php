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
}
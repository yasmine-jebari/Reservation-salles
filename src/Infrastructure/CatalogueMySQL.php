
<?php

require_once __DIR__ . '/../Application/CatalogueStore.php';
require_once __DIR__ . '/Database.php';

class CatalogueMySQL implements CatalogueStore
{
    public function listerSalles(): array
    {
        $requete = Database::connect()->prepare(
            "SELECT id, nom AS name, capacite AS capacity,
                    batiment AS building, etage AS floor
             FROM salles
             WHERE statut = 'active'
             ORDER BY nom"
        );

        $requete->execute();

        return $requete->fetchAll();
    }

    public function trouverSalle(int $salleId): ?array
    {
        $requete = Database::connect()->prepare(
            "SELECT id, nom AS name, capacite AS capacity,
                    batiment AS building, etage AS floor
             FROM salles
             WHERE id = :id AND statut = 'active'"
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

    $sql = "SELECT DISTINCT s.id, s.nom AS name,
                   s.capacite AS capacity,
                   s.batiment AS building,
                   s.etage AS floor
            FROM salles s
            LEFT JOIN salle_equipement se
                ON s.id = se.salle_id
            WHERE s.statut = 'active'";

    $parametres = [];

    if ($capacite !== null) {
        $sql .= " AND s.capacite >= :capacite";
        $parametres[':capacite'] = $capacite;
    }

    if ($batiment !== null && $batiment !== '') {
        $sql .= " AND s.batiment = :batiment";
        $parametres[':batiment'] = $batiment;
    }

    if ($equipementId !== null) {
        $sql .= " AND se.equipement_id = :equipement";
        $parametres[':equipement'] = $equipementId;
    }

    $sql .= " ORDER BY s.nom";

    $requete = Database::connect()->prepare($sql);
    $requete->execute($parametres);

    return $requete->fetchAll();
}
    }

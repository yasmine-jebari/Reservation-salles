
<?php

interface CatalogueStore
{
    public function listerSalles(): array;

    public function trouverSalle(int $salleId): ?array;

    public function rechercherSalles(
        ?int $capacite,
        ?string $batiment,
        ?int $equipementId
    ): array;
}

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

    public function listerToutesLesSalles(): array;

    public function trouverSalleAdmin(int $salleId): ?array;

    public function creerSalle(string $nom, int $capacite, ?string $batiment, ?int $etage): int;

    public function modifierSalle(int $salleId, string $nom, int $capacite, ?string $batiment, ?int $etage): void;
}
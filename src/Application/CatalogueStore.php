<?php

interface CatalogueStore
{
    public function listerSalles(): array;

    public function trouverSalle(int $salleId): ?array;
}
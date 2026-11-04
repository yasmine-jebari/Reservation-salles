<?php
interface PlanningStore {
    public function trouverSalle(int $salleId): ?array;

    public function reservationsEnConflit(
        int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin
    ): int;

    public function maintenancesEnConflit(
        int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin
    ): int;
}
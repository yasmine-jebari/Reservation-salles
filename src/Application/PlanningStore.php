<?php

interface PlanningStore
{
    // Lecture (déjà utilisée par la disponibilité)
    public function trouverSalle(int $salleId): ?array;
    public function reservationsEnConflit(int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin): int;
    public function maintenancesEnConflit(int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin): int;

    // Nouveau : transaction
    public function commencerTransaction(): void;
    public function validerTransaction(): void;
    public function annulerTransaction(): void;

    // Nouveau : verrouille la ligne de la salle et la renvoie
    public function verrouillerSalle(int $salleId): ?array;

    // Nouveau : enregistre la réservation et renvoie son id
    public function creerReservation(int $utilisateurId, int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin): int;
}
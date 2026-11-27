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
    // Nouveau : toutes les réservations d'un utilisateur (avec le nom de la salle)
    public function reservationsDeUtilisateur(int $utilisateurId): array;

        public function trouverReservation(int $reservationId): ?array;
    public function marquerAnnulee(int $reservationId): void;
        public function creerMaintenance(int $adminId, int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin, string $motif): int;
            public function occupationsDansPeriode(DateTimeImmutable $debut, DateTimeImmutable $fin): array;
}
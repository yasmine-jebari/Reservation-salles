<?php

require_once __DIR__ . '/PlanningStore.php';
require_once __DIR__ . '/../Domain/Creneau.php';
require_once __DIR__ . '/../Domain/ReglesReservation.php';

class AnnulerReservation
{
    private PlanningStore $store;

    public function __construct(PlanningStore $store)
    {
        $this->store = $store;
    }

    public function executer(int $utilisateurId, int $reservationId, DateTimeImmutable $maintenant): array
    {
        if ($utilisateurId <= 0) {
            return $this->refus('Vous devez être connecté.');
        }

        // 1. On retrouve la réservation pour connaître sa salle
        $reservation = $this->store->trouverReservation($reservationId);
        if ($reservation === null) {
            return $this->refus("Cette réservation n'existe pas.");
        }

        // 2. Transaction + verrou de la salle (comme pour réserver)
        $this->store->commencerTransaction();

        try {
            $this->store->verrouillerSalle((int) $reservation['room_id']);

            // 3. On relit la réservation, car elle a pu changer entre-temps
            $reservation = $this->store->trouverReservation($reservationId);
            if ($reservation === null || $reservation['status'] !== 'confirmed') {
                $this->store->annulerTransaction();
                return $this->refus('Cette réservation ne peut plus être annulée.');
            }

            // 4. La règle métier : bon propriétaire et avant le début
            $creneau = new Creneau(
                new DateTimeImmutable($reservation['starts_at']),
                new DateTimeImmutable($reservation['ends_at'])
            );
            if (!ReglesReservation::peutAnnuler((int) $reservation['user_id'], $utilisateurId, $creneau, $maintenant)) {
                $this->store->annulerTransaction();
                return $this->refus("Vous ne pouvez pas annuler cette réservation.");
            }

            // 5. On change l'état, on garde la ligne
            $this->store->marquerAnnulee($reservationId);
            $this->store->validerTransaction();

            return ['reussi' => true, 'message' => 'Réservation annulée.'];
        } catch (Throwable $erreur) {
            $this->store->annulerTransaction();
            return $this->refus("Une erreur est survenue, l'annulation n'a pas été faite.");
        }
    }

    private function refus(string $message): array
    {
        return ['reussi' => false, 'message' => $message];
    }
}
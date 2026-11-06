<?php
require_once __DIR__ . '/../Domain/Creneau.php';
require_once __DIR__ . '/../Domain/ReglesReservation.php';

class VerifierDisponibilite {
    private PlanningStore $planning;

    public function __construct(PlanningStore $planning) {
        $this->planning = $planning;
    }

    public function executer(
        int $salleId, string $date, string $heureDebut, string $heureFin,
        DateTimeImmutable $maintenant
    ): array {
        // 1. Lire la date et les heures saisies
        $debut = DateTimeImmutable::createFromFormat('Y-m-d H:i', "$date $heureDebut");
        $fin   = DateTimeImmutable::createFromFormat('Y-m-d H:i', "$date $heureFin");
        if ($debut === false || $fin === false) {
            return $this->refus('La date ou l\'heure est invalide.');
        }

        // 2. Les règles du Domain (aucun accès à la base)
        try {
            $creneau = new Creneau($debut, $fin);
            ReglesReservation::verifierHoraires($creneau);
            ReglesReservation::verifierDebutFutur($creneau, $maintenant);
        } catch (InvalidArgumentException $e) {
            return $this->refus($e->getMessage());
        }

        // 3. La salle existe-t-elle et est-elle active ?
        $salle = $this->planning->trouverSalle($salleId);
        if ($salle === null) {
            return $this->refus('Cette salle n\'existe pas.');
        }
        if (!$salle['is_active']) {
            return $this->refus('Cette salle est désactivée.');
        }

        // 4. Les conflits dans la base
        if ($this->planning->maintenancesEnConflit($salleId, $debut, $fin) > 0) {
            return $this->refus('La salle est en maintenance sur ce créneau.');
        }
        if ($this->planning->reservationsEnConflit($salleId, $debut, $fin) > 0) {
            return $this->refus('La salle est déjà réservée sur ce créneau.');
        }

        return ['libre' => true, 'message' => 'La salle est libre sur ce créneau.'];
    }

    private function refus(string $message): array {
        return ['libre' => false, 'message' => $message];
    }
}
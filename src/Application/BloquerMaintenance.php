<?php

require_once __DIR__ . '/PlanningStore.php';
require_once __DIR__ . '/../Domain/Creneau.php';
require_once __DIR__ . '/../Domain/ReglesReservation.php';

class BloquerMaintenance
{
    private PlanningStore $store;

    public function __construct(PlanningStore $store)
    {
        $this->store = $store;
    }

    public function executer(
        int $adminId, string $role, int $salleId,
        string $date, string $heureDebut, string $heureFin,
        string $motif, DateTimeImmutable $maintenant
    ): array {
        // 1. Les droits : décidés par le serveur, jamais par le formulaire
        if ($adminId <= 0 || $role !== 'admin') {
            return $this->refus("Cette action est réservée à l'administrateur.");
        }

        // 2. Les dates et les règles (aucun accès à la base)
        $debut = DateTimeImmutable::createFromFormat('Y-m-d H:i', "$date $heureDebut");
        $fin   = DateTimeImmutable::createFromFormat('Y-m-d H:i', "$date $heureFin");
        if ($debut === false || $fin === false) {
            return $this->refus("La date ou l'heure est invalide.");
        }

        $motif = trim($motif);
        try {
            $creneau = new Creneau($debut, $fin);
            ReglesReservation::verifierFinFuture($creneau, $maintenant);
            ReglesReservation::verifierMotif($motif);
        } catch (InvalidArgumentException $e) {
            return $this->refus($e->getMessage());
        }

        // 3. Transaction + verrou de la salle (le même que pour réserver)
        $this->store->commencerTransaction();

        try {
            $salle = $this->store->verrouillerSalle($salleId);
            if ($salle === null) {
                $this->store->annulerTransaction();
                return $this->refus("Cette salle n'existe pas.");
            }

            if ($this->store->reservationsEnConflit($salleId, $debut, $fin) > 0) {
                $this->store->annulerTransaction();
                return $this->refus('Impossible : une réservation confirmée existe déjà sur ce créneau. Rien n\'a été annulé.');
            }
            if ($this->store->maintenancesEnConflit($salleId, $debut, $fin) > 0) {
                $this->store->annulerTransaction();
                return $this->refus('Une maintenance existe déjà sur ce créneau.');
            }

            $id = $this->store->creerMaintenance($adminId, $salleId, $debut, $fin, $motif);
            $this->store->validerTransaction();

            return ['reussi' => true, 'message' => 'Maintenance enregistrée (n° ' . $id . ').'];
        } catch (Throwable $erreur) {
            $this->store->annulerTransaction();
            return $this->refus("Une erreur est survenue, le blocage n'a pas été enregistré.");
        }
    }

    private function refus(string $message): array
    {
        return ['reussi' => false, 'message' => $message];
    }
}
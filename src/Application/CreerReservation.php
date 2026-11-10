<?php

require_once __DIR__ . '/VerifierDisponibilite.php';

class CreerReservation
{
    private PlanningStore $store;

    public function __construct(PlanningStore $store)
    {
        $this->store = $store;
    }

    public function executer(
        int $utilisateurId,
        int $salleId,
        string $date,
        string $heureDebut,
        string $heureFin,
        DateTimeImmutable $maintenant
    ): array {
        if ($utilisateurId <= 0) {
            return ['reussi' => false, 'message' => 'Vous devez être connecté pour réserver.'];
        }

        // 1. On ouvre une transaction : tout réussit, ou rien n'est enregistré
        $this->store->commencerTransaction();

        try {
            // 2. On verrouille la salle : les autres demandes pour cette salle attendent
            $this->store->verrouillerSalle($salleId);

            // 3. On revérifie TOUT (le formulaire a pu mentir, ou quelqu'un a réservé entre-temps)
            $verification = (new VerifierDisponibilite($this->store))
                ->executer($salleId, $date, $heureDebut, $heureFin, $maintenant);

            if (!$verification['libre']) {
                $this->store->annulerTransaction();
                return ['reussi' => false, 'message' => $verification['message']];
            }

            // 4. Libre : on enregistre
            $debut = DateTimeImmutable::createFromFormat('Y-m-d H:i', $date . ' ' . $heureDebut);
            $fin   = DateTimeImmutable::createFromFormat('Y-m-d H:i', $date . ' ' . $heureFin);
            $id    = $this->store->creerReservation($utilisateurId, $salleId, $debut, $fin);

            // 5. On valide la transaction : la salle est libérée pour les autres
            $this->store->validerTransaction();

            return ['reussi' => true, 'message' => 'Réservation confirmée (n° ' . $id . ').', 'id' => $id];
        } catch (Throwable $erreur) {
            // En cas de problème, on annule tout : aucune écriture partielle
            $this->store->annulerTransaction();
            return ['reussi' => false, 'message' => "Une erreur est survenue, la réservation n'a pas été enregistrée."];
        }
    }
}
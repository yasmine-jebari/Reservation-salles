<?php

require_once __DIR__ . '/PlanningStore.php';
require_once __DIR__ . '/../Domain/Creneau.php';
require_once __DIR__ . '/../Domain/ReglesReservation.php';

class ListerMesReservations
{
    private PlanningStore $store;

    public function __construct(PlanningStore $store)
    {
        $this->store = $store;
    }

    public function executer(int $utilisateurId, DateTimeImmutable $maintenant): array
    {
        $aVenir  = [];
        $passees = [];

        foreach ($this->store->reservationsDeUtilisateur($utilisateurId) as $ligne) {
            $debut   = new DateTimeImmutable($ligne['starts_at']);
            $fin     = new DateTimeImmutable($ligne['ends_at']);
            $creneau = new Creneau($debut, $fin);

            // Valeurs par défaut
            $ligne['annulable'] = false;

            if ($ligne['status'] === 'cancelled') {
                $ligne['etat'] = 'Annulée';
                $passees[] = $ligne;
            } elseif ($fin <= $maintenant) {
                $ligne['etat'] = 'Passée';
                $passees[] = $ligne;
            } elseif ($debut <= $maintenant) {
                $ligne['etat'] = 'En cours';
                $aVenir[] = $ligne;
            } else {
                $ligne['etat'] = 'À venir';
                // La règle d'annulation reste dans le métier
                $ligne['annulable'] = ReglesReservation::peutAnnuler(
                    $utilisateurId, $utilisateurId, $creneau, $maintenant
                );
                $aVenir[] = $ligne;
            }
        }

        // Les réservations à venir : la plus proche d'abord
        return ['a_venir' => array_reverse($aVenir), 'passees' => $passees];
    }
}
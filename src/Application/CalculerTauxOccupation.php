<?php

require_once __DIR__ . '/PlanningStore.php';
require_once __DIR__ . '/../Domain/Creneau.php';
require_once __DIR__ . '/../Domain/TauxOccupation.php';

class CalculerTauxOccupation
{
    private PlanningStore $store;

    public function __construct(PlanningStore $store)
    {
        $this->store = $store;
    }

    public function executer(string $role, string $du, string $au): array
    {
        if ($role !== 'admin') {
            return ['reussi' => false, 'message' => "Cette page est réservée à l'administrateur."];
        }

        $debut = DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $du);
        $fin   = DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $au);
        if ($debut === false || $fin === false) {
            return ['reussi' => false, 'message' => 'La période est invalide.'];
        }

        try {
            $periode = new Creneau($debut, $fin);
        } catch (InvalidArgumentException $e) {
            return ['reussi' => false, 'message' => $e->getMessage()];
        }

        if ($fin->getTimestamp() - $debut->getTimestamp() > 366 * 86400) {
            return ['reussi' => false, 'message' => 'La période ne doit pas dépasser 366 jours.'];
        }

        $donnees = $this->store->occupationParSalle($debut, $fin);

        // Un tableau par salle active
        $parSalle = [];
        foreach ($donnees['salles'] as $s) {
            $parSalle[$s['id']] = ['nom' => $s['name'], 'reservations' => [], 'maintenances' => []];
        }
        foreach ($donnees['reservations'] as $r) {
            if (isset($parSalle[$r['room_id']])) {
                $parSalle[$r['room_id']]['reservations'][] = $this->creneau($r);
            }
        }
        foreach ($donnees['maintenances'] as $m) {
            if (isset($parSalle[$m['room_id']])) {
                $parSalle[$m['room_id']]['maintenances'][] = $this->creneau($m);
            }
        }

        $lignes = [];
        $totalOccupe = 0;
        $totalReservable = 0;
        foreach ($parSalle as $salle) {
            $calcul = TauxOccupation::pourUneSalle($periode, $salle['reservations'], $salle['maintenances']);
            $totalOccupe += $calcul['occupe'];
            $totalReservable += $calcul['reservable'];
            $lignes[] = $this->ligne($salle['nom'], $calcul['occupe'], $calcul['reservable']);
        }

        return [
            'reussi' => true,
            'du'     => $du,
            'au'     => $au,
            'salles' => $lignes,
            'global' => $this->ligne('Toutes les salles', $totalOccupe, $totalReservable),
        ];
    }

    private function creneau(array $ligne): Creneau
    {
        return new Creneau(new DateTimeImmutable($ligne['starts_at']), new DateTimeImmutable($ligne['ends_at']));
    }

    private function ligne(string $nom, int $occupe, int $reservable): array
    {
        $taux = TauxOccupation::taux($occupe, $reservable);
        return [
            'nom'        => $nom,
            'occupe'     => $this->duree($occupe),
            'reservable' => $this->duree($reservable),
            'taux'       => $taux === null ? 'Non calculable' : number_format($taux, 1, ',', ' ') . ' %',
        ];
    }

    private function duree(int $minutes): string
    {
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return $m === 0 ? $h . ' h' : sprintf('%d h %02d', $h, $m);
    }
}
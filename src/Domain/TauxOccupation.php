<?php

require_once __DIR__ . '/Creneau.php';

class TauxOccupation
{
    // Minutes d'un créneau qui tombent dans la période
    public static function minutesDansPeriode(Creneau $creneau, Creneau $periode): int
    {
        $debut = max($creneau->debut(), $periode->debut());
        $fin   = min($creneau->fin(), $periode->fin());

        if ($fin <= $debut) {
            return 0;
        }
        return intdiv($fin->getTimestamp() - $debut->getTimestamp(), 60);
    }

    // Pour une salle : minutes occupées et minutes réservables
    public static function pourUneSalle(Creneau $periode, array $reservations, array $maintenances): array
    {
        $total = self::minutesDansPeriode($periode, $periode);

        $enMaintenance = 0;
        foreach ($maintenances as $m) {
            $enMaintenance += self::minutesDansPeriode($m, $periode);
        }

        $occupe = 0;
        foreach ($reservations as $r) {
            $occupe += self::minutesDansPeriode($r, $periode);
        }

        $reservable = max(0, $total - $enMaintenance);

        return ['occupe' => min($occupe, $reservable), 'reservable' => $reservable];
    }

    // Pourcentage, ou null si on ne peut pas le calculer
    public static function taux(int $occupe, int $reservable): ?float
    {
        if ($reservable <= 0) {
            return null;
        }
        return round($occupe / $reservable * 100, 1);
    }
}
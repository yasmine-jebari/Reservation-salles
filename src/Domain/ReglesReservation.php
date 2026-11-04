<?php
require_once __DIR__ . '/Creneau.php';

class ReglesReservation {
    const HEURE_OUVERTURE = '04:00';
    const HEURE_FERMETURE = '23:00';

    public static function verifierHoraires(Creneau $creneau): void {
        $debut = $creneau->debut();
        $fin   = $creneau->fin();

        if ($debut->format('Y-m-d') !== $fin->format('Y-m-d')) {
            throw new InvalidArgumentException(
                'La réservation doit commencer et finir le même jour.'
            );
        }

        if ($debut->format('H:i') < self::HEURE_OUVERTURE
            || $fin->format('H:i') > self::HEURE_FERMETURE) {
            throw new InvalidArgumentException(
                'Les réservations sont possibles de 04h00 à 23h00 uniquement.'
            );
        }
    }

    public static function verifierDebutFutur(Creneau $creneau, DateTimeImmutable $maintenant): void {
        if ($creneau->debut() <= $maintenant) {
            throw new InvalidArgumentException('Le début doit être dans le futur.');
        }
    }

    public static function peutAnnuler(
        int $proprietaireId,
        int $utilisateurId,
        Creneau $creneau,
        DateTimeImmutable $maintenant
    ): bool {
        return $proprietaireId === $utilisateurId
            && $creneau->debut() > $maintenant;
    }
}
<?php
class Creneau {
    private DateTimeImmutable $debut;
    private DateTimeImmutable $fin;

    public function __construct(DateTimeImmutable $debut, DateTimeImmutable $fin) {
        if ($fin <= $debut) {
            throw new InvalidArgumentException(
                'La fin doit être strictement après le début.'
            );
        }
        $this->debut = $debut;
        $this->fin = $fin;
    }

    public function debut(): DateTimeImmutable {
        return $this->debut;
    }

    public function fin(): DateTimeImmutable {
        return $this->fin;
    }

    public function chevauche(Creneau $autre): bool {
        return $this->debut < $autre->fin()
            && $this->fin > $autre->debut();
    }
}
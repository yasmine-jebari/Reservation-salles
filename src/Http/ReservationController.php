<?php
require_once __DIR__ . '/../Application/CreerReservation.php';
require_once __DIR__ . '/../Application/ListerMesReservations.php';
require_once __DIR__ . '/../Application/AnnulerReservation.php';

class ReservationController {
    // Provisoire : sera remplacé par l'utilisateur connecté (login de ta camarade)
    private const UTILISATEUR_PROVISOIRE = 8;

    public function store() {
        $saisie = ['salle' => 0, 'date' => '', 'debut' => '', 'fin' => ''];
        $resultat = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $saisie = [
                'salle' => (int) ($_POST['salle'] ?? 0),
                'date'  => trim($_POST['date'] ?? ''),
                'debut' => trim($_POST['debut'] ?? ''),
                'fin'   => trim($_POST['fin'] ?? ''),
            ];

            $action = new CreerReservation(planningStore());
            $reponse = $action->executer(
                self::UTILISATEUR_PROVISOIRE,
                $saisie['salle'], $saisie['date'], $saisie['debut'], $saisie['fin'],
                new DateTimeImmutable()
            );

            // La vue affiche déjà "libre" en vert et "refus" en rouge
            $resultat = ['libre' => $reponse['reussi'], 'message' => $reponse['message']];
        }
$salles = catalogueStore()->listerSalles();
        require __DIR__ . '/../../views/reservations/form.php';
    }
    public function index() {                                           // ← tu colles ici
        $action = new ListerMesReservations(planningStore());
        $liste  = $action->executer(self::UTILISATEUR_PROVISOIRE, new DateTimeImmutable());

        $aVenir  = $liste['a_venir'];
        $passees = $liste['passees'];

        require __DIR__ . '/../../views/reservations/index.php';
}
    public function annuler() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: mes-reservations');
            exit;
        }

        $action  = new AnnulerReservation(planningStore());
        $reponse = $action->executer(
            self::UTILISATEUR_PROVISOIRE,
            (int) ($_POST['id'] ?? 0),
            new DateTimeImmutable()
        );

        header('Location: mes-reservations?ok=' . ($reponse['reussi'] ? '1' : '0')
            . '&msg=' . urlencode($reponse['message']));
        exit;
    }
}
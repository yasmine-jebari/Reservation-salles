<?php

class AdminController
{
    public function utilisateurs(): void
    {
        requireAdmin();
        $users = userStore()->listAll();
        view('admin/users', compact('users'));
    }

    public function salles(): void
    {
        requireAdmin();
        $salles = catalogueStore()->listerToutesLesSalles();
        view('admin/rooms', compact('salles'));
    }

    public function salleNouvelle(): void
    {
        requireAdmin();
        $mode = 'creation';
        $errors = [];
        $salle = ['name' => '', 'capacity' => '', 'building' => '', 'floor' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();
            [$errors, $salle] = $this->validerSalle();

            if (!$errors) {
                catalogueStore()->creerSalle(
                    $salle['name'],
                    (int) $salle['capacity'],
                    $salle['building'] !== '' ? $salle['building'] : null,
                    $salle['floor'] !== '' ? (int) $salle['floor'] : null
                );
                redirect('admin-salles');
            }
        }

        view('admin/room_form', compact('mode', 'errors', 'salle'));
    }

    public function salleModifier(): void
    {
        requireAdmin();
        $mode = 'modification';
        $id = (int) ($_GET['id'] ?? 0);
        $salle = catalogueStore()->trouverSalleAdmin($id);

        if ($salle === null) {
            http_response_code(404);
            exit('Salle introuvable');
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();
            [$errors, $saisie] = $this->validerSalle();

            if (!$errors) {
                catalogueStore()->modifierSalle(
                    $id,
                    $saisie['name'],
                    (int) $saisie['capacity'],
                    $saisie['building'] !== '' ? $saisie['building'] : null,
                    $saisie['floor'] !== '' ? (int) $saisie['floor'] : null
                );
                redirect('admin-salles');
            }

            // En cas d'erreur : on réaffiche ce que l'admin a tapé
            $salle = array_merge($salle, $saisie);
        }

        view('admin/room_form', compact('mode', 'errors', 'salle'));
    }

    private function validerSalle(): array
    {
        $errors = [];
        $saisie = [
            'name'     => trim($_POST['name'] ?? ''),
            'capacity' => trim($_POST['capacity'] ?? ''),
            'building' => trim($_POST['building'] ?? ''),
            'floor'    => trim($_POST['floor'] ?? ''),
        ];

        if ($saisie['name'] === '' || mb_strlen($saisie['name']) > 100) {
            $errors[] = 'Nom obligatoire (100 caractères maximum).';
        }
        if (filter_var($saisie['capacity'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
            $errors[] = 'La capacité doit être un nombre entier supérieur ou égal à 1.';
        }
        if (mb_strlen($saisie['building']) > 100) {
            $errors[] = 'Bâtiment : 100 caractères maximum.';
        }
        if ($saisie['floor'] !== '' && filter_var($saisie['floor'], FILTER_VALIDATE_INT) === false) {
            $errors[] = "L'étage doit être un nombre entier.";
        }

        return [$errors, $saisie];
    }
}
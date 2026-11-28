<?php
return [
    'disponibilite' => ['DisponibiliteController', 'index'],
    'reservations' => ['ReservationController', 'store'],
        'mes-reservations' => ['ReservationController', 'index'],
            'annuler-reservation' => ['ReservationController', 'annuler'],
              'salles' => ['SalleController', 'catalogue'],
              'admin/maintenance' => ['MaintenanceController', 'index'],
              'admin/calendrier' => ['CalendrierController', 'index'],
              'admin/statistiques' => ['StatistiquesController', 'index'],
                'inscription' => ['AuthController', 'inscription'],
    'connexion'   => ['AuthController', 'connexion'],
    'deconnexion' => ['AuthController', 'deconnexion'],
    'profil'      => ['AuthController', 'profil'],
          'admin-utilisateurs' => ['AdminController', 'utilisateurs'],
        'admin-salles'         => ['AdminController', 'salles'],
    'admin-salle-nouvelle' => ['AdminController', 'salleNouvelle'],
    'admin-salle-modifier' => ['AdminController', 'salleModifier'],
          ];

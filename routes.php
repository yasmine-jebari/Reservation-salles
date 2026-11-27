<?php
return [
    'disponibilite' => ['DisponibiliteController', 'index'],
    'reservations' => ['ReservationController', 'store'],
        'mes-reservations' => ['ReservationController', 'index'],
            'annuler-reservation' => ['ReservationController', 'annuler'],
              'salles' => ['SalleController', 'catalogue'],
              'admin/maintenance' => ['MaintenanceController', 'index'],
            ];
<?php

require_once __DIR__ . '/PlanningStore.php';

class LireCalendrier
{
    private PlanningStore $store;

    public function __construct(PlanningStore $store)
    {
        $this->store = $store;
    }

    public function executer(string $role, string $date, string $vue, DateTimeImmutable $maintenant): array
    {
        if ($role !== 'admin') {
            return ['reussi' => false, 'message' => "Cette page est réservée à l'administrateur."];
        }

        $vue = ($vue === 'semaine') ? 'semaine' : 'jour';

        $jourChoisi = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
        if ($jourChoisi === false) {
            $jourChoisi = $maintenant->setTime(0, 0);
        }

        if ($vue === 'semaine') {
            $debut = $jourChoisi->modify('monday this week');
            $nbJours = 7;
        } else {
            $debut = $jourChoisi;
            $nbJours = 1;
        }
        $fin = $debut->modify("+$nbJours days");

        // Une case par jour de la période, même vide
        $noms = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $jours = [];
        for ($i = 0; $i < $nbJours; $i++) {
            $jour = $debut->modify("+$i days");
            $jours[$jour->format('Y-m-d')] = [
                'libelle'     => $noms[(int) $jour->format('N') - 1] . ' ' . $jour->format('d/m/Y'),
                'occupations' => [],
            ];
        }

        // On range chaque occupation dans son jour
        foreach ($this->store->occupationsDansPeriode($debut, $fin) as $ligne) {
            $cle = substr($ligne['starts_at'], 0, 10);
            if (isset($jours[$cle])) {
                $jours[$cle]['occupations'][] = [
                    'salle'  => $ligne['room_name'],
                    'type'   => $ligne['type'],
                    'debut'  => substr($ligne['starts_at'], 11, 5),
                    'fin'    => substr($ligne['ends_at'], 11, 5),
                    'detail' => $ligne['detail'],
                ];
            }
        }

        return [
            'reussi'     => true,
            'vue'        => $vue,
            'date'       => $jourChoisi->format('Y-m-d'),
            'precedent'  => $debut->modify("-$nbJours days")->format('Y-m-d'),
            'suivant'    => $fin->format('Y-m-d'),
            'jours'      => $jours,
        ];
    }
}
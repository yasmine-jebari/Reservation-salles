
<?php

class SalleController
{
    private CatalogueStore $catalogue;

    public function __construct()
    {
        $this->catalogue = catalogueStore();
    }

    public function catalogue(): void
    {
        $capacite = isset($_GET['capacite']) && $_GET['capacite'] !== ''
            ? (int) $_GET['capacite']
            : null;

        $batiment = isset($_GET['batiment']) && $_GET['batiment'] !== ''
            ? trim($_GET['batiment'])
            : null;

        $equipementId = isset($_GET['equipement']) && $_GET['equipement'] !== ''
            ? (int) $_GET['equipement']
            : null;

        $salles = $this->catalogue->rechercherSalles(
            $capacite,
            $batiment,
            $equipementId
        );

        $equipements = Database::connect()
            ->query("SELECT id, name FROM equipments ORDER BY name")
            ->fetchAll();

        require __DIR__ . '/../../views/salles/catalogue.php';
    }
}

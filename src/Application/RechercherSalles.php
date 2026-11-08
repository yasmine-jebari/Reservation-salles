
<?php

require_once __DIR__ . '/CatalogueStore.php';

class RechercherSalles
{
    private CatalogueStore $catalogue;

    public function __construct(CatalogueStore $catalogue)
    {
        $this->catalogue = $catalogue;
    }

    public function executer(
        ?int $capacite,
        ?string $batiment,
        ?int $equipementId
    ): array {
        return $this->catalogue->rechercherSalles(
            $capacite,
            $batiment,
            $equipementId
        );
    }
}

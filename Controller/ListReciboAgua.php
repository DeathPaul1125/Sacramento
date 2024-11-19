<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListReciboAgua extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Recibos de Agua";
        $data["menu"] = "Agua";
        $data["icon"] = "fas fa-cash-register";
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsReciboAgua();
    }

    protected function createViewsReciboAgua(string $viewName = "ListReciboAgua"): void
    {
        $this->addView($viewName, "ReciboAgua", "Recibos de Agua", "fas fa-cash-register");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addOrderBy($viewName, ["id"], "id", 2);
        // $this->addOrderBy($viewName, ["name"], "name");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        $this->addSearchFields($viewName, ["id", "name", "codlote"]);
        $this->addFilterAutocomplete($viewName, 'codlote', 'codlote', 'codlote', 'codlote');
        
        //filtro por colonia
        $colonia = $this->codeModel->all('colonias', 'id', 'nombre');
        $this->addFilterSelect($viewName, 'colonia', 'colonia', 'colonia', $colonia);
        
        $this->addFilterCheckbox($viewName, 'pagado', 'No Pagado', 'pagado','IS NOT');
    }
}

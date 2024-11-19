<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListReciboLote extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Recibos de Lotes";
        $data["menu"] = "Lotes";
        $data["icon"] = "fas fa-money-check";
        $data['showonmenu'] = false;
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsReciboLote();
    }

    protected function createViewsReciboLote(string $viewName = "ListReciboLote"): void
    {
        $this->addView($viewName, "ReciboLote", "Recibos", "fas fa-money-check");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addOrderBy($viewName, ["id"], "id", 2);
        // $this->addOrderBy($viewName, ["name"], "name");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addSearchFields($viewName, ["id", "name"]);
    }
}

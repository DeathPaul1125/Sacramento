<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListLineaReciboSacramento extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Lineas de recibo";
        $data["menu"] = "lineas";
        $data["icon"] = "fas fa-search";
        $data['showonmenu'] = false;
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsLineaReciboSacramento();
    }

    protected function createViewsLineaReciboSacramento(string $viewName = "ListLineaReciboSacramento"): void
    {
        $this->addView($viewName, "LineaReciboSacramento", "Lineas de recibo");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addOrderBy($viewName, ["id"], "id", 2);
        // $this->addOrderBy($viewName, ["name"], "name");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addSearchFields($viewName, ["id", "name"]);
    }
}

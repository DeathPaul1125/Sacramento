<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListEstadoCuentaLote extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Estado de Cuenta";
        $data["menu"] = "reports";
        $data["icon"] = "fas fa-hand-holding-usd";
        $data['showonmenu'] = false;
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsCalculoInteres();
    }

    protected function createViewsCalculoInteres(string $viewName = "ListEstadoCuentaLote"): void
    {
        $this->addView($viewName, "EstadoCuentaLote", "Estados de Cuenta", "fas fa-hand-holding-usd");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addOrderBy($viewName, ["id"], "id", 2);
        // $this->addOrderBy($viewName, ["name"], "name");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addSearchFields($viewName, ["id", "name"]);
    }
}

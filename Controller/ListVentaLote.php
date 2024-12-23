<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListVentaLote extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Venta de Lotes";
        $data["menu"] = "sales";
        $data["icon"] = "fa-solid fa-house-medical-flag";
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsVentaLote();
    }

    protected function createViewsVentaLote(string $viewName = "ListVentaLote"): void
    {
        $this->addView($viewName, "VentaLote", "Venta de Lotes", "fa-solid fa-house-medical-flag");
    }
}

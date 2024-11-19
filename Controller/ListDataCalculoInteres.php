<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListDataCalculoInteres extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Data Calculo Interes";
        $data["menu"] = "Lotes";
        $data["icon"] = "fas fa-funnel-dollar";
        $data['showonmenu'] = false;
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsDataCalculoInteres();
    }

    protected function createViewsDataCalculoInteres(string $viewName = "ListDataCalculoInteres"): void
    {
        $this->addView($viewName, "DataCalculoInteres", "Data Calculo Interes", "fas fa-funnel-dollar");
        
    }
}

<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;

class EditDataCalculoInteres extends EditController
{
    public function getModelClassName(): string
    {
        return "DataCalculoInteres";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "DataCalculoInteres";
        $data["icon"] = "fas fa-funnel-dollar";
        return $data;
    }
}

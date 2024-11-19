<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;

class EditLote extends EditController
{
    public function getModelClassName(): string
    {
        return "Lote";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Lote";
        $data["icon"] = "fas fa-layer-group";
        return $data;
    }
}

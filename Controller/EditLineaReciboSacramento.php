<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;

class EditLineaReciboSacramento extends EditController
{
    public function getModelClassName(): string
    {
        return "LineaReciboSacramento";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "LineaReciboSacramento";
        $data["icon"] = "fas fa-search";
        return $data;
    }
}

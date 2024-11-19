<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;

class EditDataReciboLote extends EditController
{
    public function getModelClassName(): string
    {
        return "DataReciboLote";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "DataReciboLote";
        $data["icon"] = "fas fa-search";
        return $data;
    }
}

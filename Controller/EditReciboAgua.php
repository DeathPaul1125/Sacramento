<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;

class EditReciboAgua extends EditController
{
    public function getModelClassName(): string
    {
        return "ReciboAgua";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "ReciboAgua";
        $data["icon"] = "fas fa-cash-register";
        return $data;
    }
}
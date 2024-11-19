<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;

class EditLecturaInicial extends EditController
{
    public function getModelClassName(): string
    {
        return "LecturaInicial";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Lectura Inicial";
        $data["icon"] = "fas fa-clock";
        return $data;
    }
}

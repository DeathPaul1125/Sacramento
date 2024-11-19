<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Lib\ExtendedController\EditController;
use FacturaScripts\Dinamic\Model\VentaLote;

class EditVentaLote extends EditController
{
    public function getModelClassName(): string
    {
        return "VentaLote";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Venta de Lote";
        $data["icon"] = "fa-solid fa-house-medical-flag";
        return $data;
    }
    protected function createViews(): void
    {
        parent::createViews();
        $this->createViewsDataRecibo();
        $this->setTabsPosition('bottom');
    }
    public function createViewsDataRecibo(string $viewName = 'ListReciboSacramento'): void
    {
        $this->addListView($viewName, 'ReciboSacramento', 'Data', 'fas fa-border-none');
        $this->setSettings($viewName, 'btnNew', false);
        $this->setSettings($viewName, 'btnDelete', false);
        $this->setSettings($viewName, 'checkBoxes', false);
        //$this->setSettings($viewName, 'clickable', false);
    }
    protected function loadData($viewName, $view): void
    {
        $code = $this->request->get("code");
        $ventalote = new VentaLote();
        if ($ventalote->loadFromCode($code)) {

        }
        switch ($viewName)
        {
            case 'ListReciboSacramento':
                $where = [
                    new DataBaseWhere('codlote', $ventalote->codlote),
                ];
                $view->loadData('', $where);
                break;
            default:
                parent::loadData($viewName, $view);
            break;
        }
    }
}

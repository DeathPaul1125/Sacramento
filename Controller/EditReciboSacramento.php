<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Lib\ExtendedController\EditController;

class EditReciboSacramento extends EditController
{
    public function getModelClassName(): string
    {
        return "ReciboSacramento";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Recibo";
        $data["icon"] = "fa-solid fa-money-check-dollar";
        return $data;
    }
    protected function createViews(): void
    {
        parent::createViews();
        $this->createViewsDataRecibo();
        $this->setTabsPosition('bottom');
    }
    public function createViewsDataRecibo(string $viewName = 'EditLineaReciboSacramento'): void
    {
        $this->addEditListView($viewName, 'LineaReciboSacramento', 'Data', 'fas fa-border-none');
        $this->views[$viewName]->setInLine(true);
    }
    protected function loadData($viewName, $view): void
    {
        switch ($viewName)
        {
            case 'EditLineaReciboSacramento':
                $where = [new DataBaseWhere('codrecibo', $this->getModel()->primaryColumnValue())];
                $view->loadData('', $where);
                break;
            default:
                parent::loadData($viewName, $view);
            break;
        }
    }
}
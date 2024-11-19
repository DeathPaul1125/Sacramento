<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Lib\ExtendedController\EditController;

class EditReciboLote extends EditController
{
    public function getModelClassName(): string
    {
        return "ReciboLote";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "ReciboLote";
        $data["icon"] = "fas fa-search";
        return $data;
    }
    protected function createViews(): void
    {
        parent::createViews();
        $this->createViewsDataRecibo();
        $this->setTabsPosition('bottom');
    }
    public function createViewsDataRecibo(string $viewName = 'EditDataReciboLote'): void
    {
        $this->addEditListView($viewName, 'DataReciboLote', 'Calculo', 'fas fa-border-none');
/*         $this->setSettings($viewName, 'btnNew', false);
        $this->setSettings($viewName, 'btnDelete', false);
        $this->setSettings($viewName, 'checkBoxes', false);
        $this->setSettings($viewName, 'clickable', false); */
    }
    protected function loadData($viewName, $view): void
    {
        switch ($viewName)
        {
            case 'EditDataReciboLote':
                $where = [new DataBaseWhere('codrecibolote', $this->getModel()->primaryColumnValue())];
                $view->loadData('', $where);
                break;
            default:
                parent::loadData($viewName, $view);
            break;
        }
    }
}
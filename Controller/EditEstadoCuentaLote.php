<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Lib\ExtendedController\EditController;
use FacturaScripts\Dinamic\Model\CalculoInteres;

class EditEstadoCuentaLote extends EditController
{
    public function getModelClassName(): string
    {
        return "EstadoCuentaLote";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Estado de Cuenta";
        $data["icon"] = "fas fa-funnel-dollar";
        return $data;
    }
    protected function createViews(): void
    {
        parent::createViews();
        $this->setSettings('EditEstadoCuentaLote', 'btnSave', false);
        $this->setSettings('EditEstadoCuentaLote', 'btnUndo', false);
        $this->setSettings('EditEstadoCuentaLote', 'btnDelete', false);

        $this->createViewsCuotas();
        $this->setTabsPosition('bottom');
    }

    public function createViewsCuotas(string $viewName = 'ListDataCalculoInteres'): void
    {
        $this->addListView($viewName, 'DataCalculoInteres', 'Calculo', 'fas fa-border-none');
        $this->setSettings($viewName, 'btnNew', false);
        $this->setSettings($viewName, 'btnDelete', false);
        $this->setSettings($viewName, 'checkBoxes', false);
        $this->setSettings($viewName, 'clickable', false);
    }
    protected function loadData($viewName, $view): void
    {
        switch ($viewName)
        {
            case 'ListDataCalculoInteres':
                $code = $this->request->get("code");
                $calculo = new CalculoInteres();
                $calculo->loadFromCode($code);
                $where = [
                    new DataBaseWhere('codlote', $calculo->codlote),
                    new DataBaseWhere('pagado', false)
                ];
                $view->loadData('', $where);
                break;
            default:
                parent::loadData($viewName, $view);
            break;
        }
    }
}
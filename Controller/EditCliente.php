<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Controller\EditCliente as DinCliente;

class EditCliente extends DinCliente
{
    protected function createViews(): void
    {
        parent::createViews();
        $this->addListView('ListCalculoInteres', 'CalculoInteres', 'Calculo de Intereses');
    }

    protected function loadData($viewName, $view): void
    {
        switch ($viewName) {
            case 'ListCalculoInteres':
                $codcliente = $this->getViewModelValue('EditCliente', 'codcliente');
                $where = [new DataBaseWhere('cliente', $codcliente)];
                $view->loadData('', $where);
                break;

            default:
                parent::loadData($viewName, $view);
                break;
        }
    }
}
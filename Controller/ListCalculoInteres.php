<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListCalculoInteres extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Proyección de Pagos";
        $data["menu"] = "Lotes";
        $data["icon"] = "fas fa-hand-holding-usd";
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsCalculoInteres();
    }

    protected function createViewsCalculoInteres(string $viewName = "ListCalculoInteres"): void
    {
        $this->addView($viewName, "CalculoInteres", "Reporte de amortizaciones", "fas fa-hand-holding-usd");
        
        $colonias = $this->codeModel->all('colonias', 'id', 'nombre');
        $this->addFilterSelect($viewName, 'colonia', 'Colonias', 'codlote', $colonias);

        //Parameters a buscar
        $this->addSearchFields($viewName, [ 'codlote', 'id', 'cliente']);
   
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addOrderBy($viewName, ["id"], "id", 2);
        // $this->addOrderBy($viewName, ["name"], "name");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addSearchFields($viewName, ["id", "name"]);
    }
}

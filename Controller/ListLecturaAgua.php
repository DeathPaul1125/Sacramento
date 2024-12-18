<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;
use FacturaScripts\Dinamic\Model\Colonia;

class ListLecturaAgua extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Lectura de Contador";
        $data["menu"] = "Agua";
        $data["icon"] = "fas fa-hand-holding-water";
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsLecturaAgua();

    }

    protected function createViewsLecturaAgua(string $viewName = "ListLecturaAgua"): void
    {
        $this->addView($viewName, "LecturaAgua", "Lectura de Contador", "fas fa-hand-holding-water");
        $this->addFilterAutocomplete($viewName, 'codlote', 'Lotes', 'codlote', 'lotes', 'id', 'codlote');

        $this->addOrderBy($viewName, ["fecha"], "fecha");
        $this->addOrderBy($viewName, ["mes"], "mes");
        $this->addFilterPeriod($viewName, 'fecha', 'period', 'fecha');
             
        $colonias = $this->codeModel->all('colonias', 'id', 'nombre');
        $this->addFilterSelect($viewName, 'colonia', 'Colonias', 'codlote', $colonias);

        //Parameters a buscar
        $this->addSearchFields($viewName, ['codlote' , 'codrecibo','id']);

        
    }
    
}

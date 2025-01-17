<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListLecturaInicial extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Lectura Inicial";
        $data["menu"] = "Agua";
        $data["icon"] = "fas fa-clock";
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsLecturaInicial();
    }

    protected function createViewsLecturaInicial(string $viewName = "ListLecturaInicial"): void
    {
        $this->addView($viewName, "LecturaInicial", "Lectura Inicial", "fas fa-clock");
        $this->addFilterPeriod($viewName, 'creation_date', 'period', 'creation_date',true);
        $this->addSearchFields($viewName, ["id", "creation_date", "codlote"]);
        $this->addFilterAutocomplete($viewName, 'codlote', 'Lotes', 'codlote', 'lotes', 'id', 'codlote');

        $this->addOrderBy($viewName, ["creation_date"], "fecha");
        $this->addOrderBy($viewName, ["mes"], "mes");
 
        $colonias = $this->codeModel->all('colonias', 'id', 'nombre');
        $this->addFilterSelect($viewName, 'colonia', 'Colonias', 'codlote', $colonias);

        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addOrderBy($viewName, ["id"], "id", 2);
        // $this->addOrderBy($viewName, ["name"], "name");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addSearchFields($viewName, ["id", "name"]);
    }
}

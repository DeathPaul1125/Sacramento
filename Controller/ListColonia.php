<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListColonia extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Colonias";
        $data["menu"] = "Lotes";
        $data["icon"] = "fas fa-home";
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsColonia();
    }

    protected function createViewsColonia(string $viewName = "ListColonia"): void
    {
        $this->addView($viewName, "Colonia", "Colonias", "fas fa-home");

        $colonias = $this->codeModel->all('colonias', 'id', 'nombre');
        $this->addFilterSelect($viewName, 'id', 'Colonias', 'id', $colonias);
        

        //Parameters a buscar
        $this->addSearchFields($viewName, ["sector", "colonia", "manzana", "lote"]);
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addOrderBy($viewName, ["id"], "id", 2);
        // $this->addOrderBy($viewName, ["name"], "name");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addSearchFields($viewName, ["id", "name"]);
    }
}

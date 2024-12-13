<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListLote extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Lotes";
        $data["menu"] = "Lotes";
        $data["icon"] = "fas fa-layer-group";
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsLote();
    }

    protected function createViewsLote(string $viewName = "ListLote"): void
    {
        $this->addView($viewName, "Lote", "Lotes", "fas fa-layer-group");
        
        $escriturado = [true => "SI", false => "NO"];
        $this->addFilterSelect($viewName, 'escriturado', 'Escriturado', 'escriturado', $escriturado);
        $dispolible = [true => "SI", false => "NO"];
        $this->addFilterSelect($viewName, 'estado', 'Disponible', 'estado', $dispolible);

        
        $colonias = $this->codeModel->all('colonias', 'id', 'nombre');
        $this->addFilterSelect($viewName, 'colonia', 'Colonias', 'colonia', $colonias);

        //Parameters a buscar
        $this->addSearchFields($viewName, ['sector','lote']);
    }
}

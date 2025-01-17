<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListVentaLote extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Venta de Lotes";
        $data["menu"] = "sales";
        $data["icon"] = "fa-solid fa-house-medical-flag";
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsVentaLote();
    }

    protected function createViewsVentaLote(string $viewName = "ListVentaLote"): void
    {
        $this->addView($viewName, "VentaLote", "Venta de Lotes", "fa-solid fa-house-medical-flag");

        $colonias = $this->codeModel->all('colonias', 'id', 'nombre');
        //id es la key del array, colonia es el nombre que se encuentra en el modelo
        $this->addFilterSelect($viewName, 'id', 'Colonias', 'colonia', $colonias);

        $this->addFilterAutocomplete($viewName, 'codlote', 'Lotes', 'codlote', 'lotes', 'id', 'codlote');
        $this->addFilterAutocomplete($viewName, 'cliente', 'Cliente', 'cliente' , 'clientes', 'codcliente', 'nombre');

        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        $this->addSearchFields($viewName, ['codlote', 'cliente']);
             

        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        // $this->addOrderBy($viewName, ["id"], "id", 2);
        $this->addOrderBy($viewName, ["fecha"], "fecha");
        $this->addOrderBy($viewName, ["mes"], "mes");
        $this->addFilterPeriod($viewName, 'fecha', 'period', 'fecha');
    
    }
}

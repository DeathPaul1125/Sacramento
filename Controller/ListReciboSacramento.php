<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListReciboSacramento extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Recibos";
        $data["menu"] = "sales";
        $data["icon"] = "fa-solid fa-money-check-dollar";
        return $data;
    }

    protected function createViews(): void
    {
        $this->createViewsReciboSacramento();
    }

    protected function createViewsReciboSacramento(string $viewName = "ListReciboSacramento"): void
    {
        $this->addView($viewName, "ReciboSacramento", "Recibos Sacramento", "fa-solid fa-money-check-dollar");

        $countries = [
            ['code' => '1', 'description' => 'Amortizacion'],
            ['code' => '2', 'description' => 'Instalacion de Agua'],
            ['code' => '3', 'description' => 'Pago de Escritura'],
            ['code' => '4', 'description' => 'Cancelacion Total del lote'],
            ['code' => '5', 'description' => 'Reposicion del contador'],
            ['code' => '6', 'description' => 'Suspension del servicio de agua'],
            ['code' => '7', 'description' => 'Habilitacion del servicio de agua'],
            ['code' => '8', 'description' => 'Instalacion de Energia Electrica'],
            ['code' => '9', 'description' => 'Remedida de Lote'],
            ['code' => '10', 'description' => 'Sesion de derechos'],
            ['code' => '11', 'description' => 'Cargos'],
            ['code' => '12', 'description' => 'Abonos'],
        ];
        $this->addFilterSelect($viewName, 'tipo', 'tipo', 'tipo', $countries);

        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        $this->addOrderBy($viewName, ["fecha"], "Fecha", 2);
        $this->addOrderBy($viewName, ["codlote"], "Codigo de Lote");
        
        // Esto es un ejemplo ... Debe de cambiarlo según los nombres de campos del modelo
        $this->addSearchFields($viewName, ["codlote", "tipo"]);
    }
}
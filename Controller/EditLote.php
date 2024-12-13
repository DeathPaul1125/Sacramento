<?php
namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;
use FacturaScripts\Dinamic\Model\Lote;
use FacturaScripts\Plugins\Sacramento\Lib\NumeroALetras;


class EditLote extends EditController
{
    public function getModelClassName(): string
    {
        return "Lote";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Lote";
        $data["icon"] = "fas fa-layer-group";
        return $data;
    }

    public function execPreviousAction($action): bool
    {
        $lote = new Lote();
        $code = $this->request->get("code");
        
        if($lote->loadFromCode($code)){
            $hoy = date('d-m-Y');
            $formatter = new NumeroALetras();

            $loteletras = $formatter->toString(intval($lote->lote));
            $fincaletras = $formatter->toString(intval($lote->finca));
            $folioletras = $formatter->toString(intval($lote->folio));
            $libroletras = $formatter->toString(intval($lote->libro));
            $sectorletras = $formatter->toString(intval($lote->sector));   
            $totalmetrosletras = $formatter->toString(intval($lote->totalmetros));

         
            //Guardar el cui en letras
            $lote->loteletras = $loteletras;
            $lote->fincaletras = $fincaletras; 
            $lote->folioletras = $folioletras;
            $lote->libroletras = $libroletras;
            $lote->sectorletras = $sectorletras;
            $lote->totalmetrosletras = $totalmetrosletras;

            


            //Guardar la edad en letras
            $lote->save();
        }
        return parent::execPreviousAction($action);
    }
}

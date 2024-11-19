<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;
use FacturaScripts\Dinamic\Model\Colonia;
use FacturaScripts\Plugins\Sacramento\Lib\NumeroALetras;

class EditColonia extends EditController
{
    public function getModelClassName(): string
    {
        return "Colonia";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Colonia";
        $data["icon"] = "fas fa-home";
        return $data;
    }
    public function execPreviousAction($action): bool
    {
        $colonia = new Colonia();
        $code = $this->request->get("code");
        
        if($colonia->loadFromCode($code)){
            $hoy = date('d-m-Y');
            $formatter = new NumeroALetras();
            $data1 = substr($colonia->cuirepresentante, 0, 4);
            $data2 = substr($colonia->cuirepresentante, 4, 5);
            $data3 = substr($colonia->cuirepresentante, 9, 4);

            $dcui1 = $formatter->toString(intval($data1));
            $dcui2 = $formatter->toString(intval($data2));
            $dcui3 = $formatter->toString(intval($data3));
            $letrascui = $dcui1 .' Y '. $dcui2 .' Y '. $dcui3;
            //Guardar el cui en letras
            $colonia->cuiletras = $letrascui; 
            $diferencia = date_diff(date_create($colonia->fnacimiento), date_create($hoy));
            $colonia->edad = $diferencia->y;
            //Guardar la edad en letras
            $letrasedad = $formatter->toString(intval($colonia->edad));
            $colonia->edadletras = $letrasedad;
            $colonia->save();
        }
        return parent::execPreviousAction($action);
    }
}
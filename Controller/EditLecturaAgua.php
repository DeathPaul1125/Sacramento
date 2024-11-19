<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use Exception;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Lib\ExtendedController\EditController;
use FacturaScripts\Core\Model\Contacto;
use FacturaScripts\Core\Tools;
use FacturaScripts\Dinamic\Model\CalculoInteres;
use FacturaScripts\Dinamic\Model\Colonia;
use FacturaScripts\Dinamic\Model\LecturaAgua;
use FacturaScripts\Dinamic\Model\LecturaInicial;
use FacturaScripts\Dinamic\Model\Lote;
use FacturaScripts\Dinamic\Model\ReciboAgua;
use FacturaScripts\Dinamic\Model\VentaLote;

class EditLecturaAgua extends EditController
{
    public function getModelClassName(): string
    {
        return "LecturaAgua";
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Lectura de Agua";
        $data["icon"] = "fas fa-hand-holding-water";
        return $data;
    }

    /**
     * @throws Exception
     */
    protected function createViews(): void
    {
        parent::createViews();
        $this->addButton('EditLecturaAgua', 
        [
            'action' => 'crea-recibo-agua',
            'icon' => 'fas fa-cash-register',
            'color' => 'success',
            'label' => 'Crear Recibo de Agua'
        ]);
    }
    protected function execAfterAction($action): void
    {
        if($action === 'crea-recibo-agua') {
            $this->CreaReciboAgua();
            return;
        }
        parent::execAfterAction($action);
    }
    public function CreaReciboAgua(): void
    {
        $code = $this->request->get("code");
        $lectura = new LecturaAgua();
        $lectura->loadFromCode($code);
        
        $inicial = new LecturaInicial();
        $inicial->loadFromCode('', [new DataBaseWhere('codlote', $lectura->codlote)]);
        
        $data = new CalculoInteres();
        $data->loadFromCode('', [new DataBaseWhere('codlote', $lectura->codlote)]);
        
        $lote = new Lote();
        $lote->loadFromCode($lectura->codlote);
        
        $colonia = new Colonia();
        $colonia->loadFromCode('', [new DataBaseWhere('id', $lote->colonia)]);
        
        $cliente = new Contacto();
        $cliente->loadFromCode('', [new DataBaseWhere('codcliente', $data->cliente)]);

        $ventalote = new VentaLote();
        $ventalote->loadFromCode('', [new DataBaseWhere('codlote', $lectura->codlote)]);
        
        $reciboagua = new ReciboAgua();
        $reciboagua->valor = $colonia->vagua;
        //mora validators si la lectura excede a los metros según la colonia
        $metrospermitidos = $colonia->derechom3;
        $lecturacontador = $lectura->lectura - $inicial->lecturat;

        if($lecturacontador >= $metrospermitidos)
        {
            $exceso = $lecturacontador - $metrospermitidos;
            $reciboagua->exceso = $exceso;
            $reciboagua->mora = $exceso * $colonia->vexceso;
        }
        $reciboagua->colonia = $colonia->id;
        $reciboagua->codlote = $lectura->codlote;
        $reciboagua->cliente = $cliente->codcliente;
        $reciboagua->lectura = $lecturacontador;
        $reciboagua->lecturacontador = $lectura->lectura;
        $reciboagua->flectura = $lectura->fecha;
        $reciboagua->total = $reciboagua->mora + $reciboagua->valor;
        $reciboagua->codrecibo = "A-".$lectura->mes ." ". $lectura->codlote;
        $reciboagua->save();
        Tools::log()->notice('<a href="https://sistema.sacramentocgsa.com/EditReciboAgua?code='.$reciboagua->id.'"> Se ha creado el recibo A-'.$lectura->mes ." ". $lectura->codlote .'</a>');
        $inicial->lecturat = $lectura->lectura;
        $inicial->save();
    }
}
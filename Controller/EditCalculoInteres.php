<?php

namespace FacturaScripts\Plugins\Sacramento\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Dinamic\Model\CalculoInteres;
use FacturaScripts\Dinamic\Model\Colonia;
use FacturaScripts\Dinamic\Model\DataCalculoInteres;
use FacturaScripts\Dinamic\Model\Lote;
use FacturaScripts\Core\Tools;
use FacturaScripts\Dinamic\Model\DfDocumento;
use FacturaScripts\Dinamic\Model\LineaReciboSacramento;
use FacturaScripts\Dinamic\Model\VentaLote;
use FacturaScripts\Plugins\Sacramento\Model\ReciboLote;
use FacturaScripts\Plugins\Sacramento\Model\ReciboSacramento;
use FacturaScripts\Plugins\Sacramento\Lib\NumeroALetras;

class EditCalculoInteres extends EditController
{
    public function getModelClassName(): string
    {
        return "CalculoInteres";
    }
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data["title"] = "Proyección de Pagos";
        $data["icon"] = "fas fa-hand-holding-usd";
        return $data;
    }

    /**
     * @throws \Exception
     */
    protected function createViews()
    {
        parent::createViews();
        $this->addButton('EditCalculoInteres', 
            [
                'action' => 'CalculaData',
                'icon' => 'fas fa-calculator',
                'label' => 'Calcular Cuotas',
                'color' => 'success'
            ]);
            if($this->lote()->estado == 1){
                $this->addButton('EditCalculoInteres',
            [
                'action' => 'VendeLote',
                'icon' => 'fa-solid fa-file-invoice-dollar',
                'label' => 'Vender Lote',
                'color' => 'danger'
            ]);
            }
        $this->createViewsCuotas();
        $this->setTabsPosition('bottom');
    }
    public function createViewsCuotas(string $viewName = 'ListDataCalculoInteres'): void
    {
        $this->addListView($viewName, 'DataCalculoInteres', 'Calculo', 'fas fa-border-none');
        $this->setSettings($viewName, 'btnNew', false);
        $this->setSettings($viewName, 'btnDelete', false);
        $this->setSettings($viewName, 'checkBoxes', false);
        $this->setSettings($viewName, 'clickable', false);
    }
    protected function loadData($viewName, $view): void
    {
        switch ($viewName)
        {
            case 'ListDataCalculoInteres':
                $code = $this->request->get("code");
                $calculo = new CalculoInteres();
                $calculo->loadFromCode($code);
                $where = [new DataBaseWhere('codlote', $calculo->id)];
                $view->loadData('', $where);
                break;
            default:
                parent::loadData($viewName, $view);
            break;
        }
    }
    public function lote(): Lote
    {
        $code = $this->request->get('code');
        //Datos del calculo
        $datalote = new CalculoInteres();
        $datalote->loadFromCode($code);
        
        $lote = new Lote();
        $lote->loadFromCode($datalote->codlote);
        return $lote;
    }
    public function calculo(): CalculoInteres
    {
        $code = $this->request->get('code');
        $calculo = new CalculoInteres();
        $calculo->loadFromCode($code);
        return $calculo;
    }
    protected function execPreviousAction($action): ?bool
    {     
        $calculointeres = new CalculoInteres();
        $code = $this->request->get("code");
        
        if($calculointeres->loadFromCode($code)){
            $hoy = date('d-m-Y');
            $formatter = new NumeroALetras();

            $cuotasletras = $formatter->toString(intval($calculointeres->cuotas));
            $costoloteletras = $formatter->toString(intval($calculointeres->costolote));
            $engancheletras = $formatter->toString(intval($calculointeres->enganche));
            $cuotaletras = $formatter->toString(intval($calculointeres->cuota));
            $saldoconintletras = $formatter->toString(intval($calculointeres->saldoconint));


         
            //Guardar el cui en letras
            $calculointeres->cuotasletras = $cuotasletras;
            $calculointeres->costoloteletras = $costoloteletras;
            $calculointeres->engancheletras = $engancheletras;
            $calculointeres->cuotaletras = $cuotaletras;
            $calculointeres->saldoconintletras = $saldoconintletras;

            Tools::log()->info(json_encode($calculointeres));

            //Guardar la edad en letras
            $calculointeres->save();
        }        
        
        switch($action)
        {
            case 'CalculaData':
                return $this->CalculaData();
            case 'BorraData':
                return $this->BorraData();
            case 'CreaRecibos':
                return $this->CreaRecibos();
            case 'VendeLote':
                return $this->VendeLote();
        }
        return parent::execPreviousAction($action);
    }
    protected function CalculaData()
    {
        $calculo = new CalculoInteres();
        $codlote = $this->request->get("code");

        $this->BorraData();

        if (!$calculo->loadFromCode($codlote)){
            return false;
        }
        //Intereses por anio
        $r = $calculo->tasaint/12;
        //Saldo
        $saldo = number_format($calculo->saldosinint, 2, ".", "");
        $saldot = 0;

        if($calculo->cuotas == 0){
            Tools::log()->error('Falta la cantidad de cuotas');
        }

        switch($calculo->tipoint){
            case '1' :
                $this->BorraData();
                //Tools::log()->error('Entrando Opcion 1');
                //Cuota Nivelada
                $base1 = $saldo*$r;
                $base2 = 1+$r;
                $base3 = pow($base2, -$calculo->cuotas);
                $base4 = 1-$base3;

                $cuota = ($base1/$base4);
                $calculo->cuota = number_format($cuota, 2, ".", "");
                $calculo->save();
                $totalinteres = 0;
                //var_dump($totalinteres);
                for ($contador = 1; $contador <= $calculo->cuotas; $contador++) {
                    $cont = $contador - 1;
                    $nextdate = date("d-m-Y", strtotime($calculo->fechacuota . " +".$cont ." month"));
                    $datacalculo = new DataCalculoInteres();
                    //Capital
                    $capital = $calculo->cuota;
                    //Intereses
                    $interes = $saldo * $r;
                    $interestotal = $saldo * $r;
                    $int2 = $calculo->tasaint/12; 
                    $interes = $saldo * $int2;
                    $capital = $cuota - $interes;
                    //numero
                    $datacalculo->numero = $contador;
                    //fecha
                    $datacalculo->fecha = $nextdate;
                    //cuota
                    $datacalculo->cuota = $calculo->cuota;
                    //interes
                    $datacalculo->interes = $interes; 
                    //capital
                    $datacalculo->capital = $capital;
                    //saldo
                    $datacalculo->saldo = $saldo;
                    $saldo = $saldo - $capital;
                    //Saldototal
                    $saldot = $saldot + $saldo;
                    //datos para hace rla relacion
                    $datacalculo->recibot = $calculo->codlote . '-' . $contador;
                    $datacalculo->codlote = $calculo->id;
                    $datacalculo->cliente = $calculo->cliente;
                    $datacalculo->save();
                    $totalinteres = $totalinteres + $interes;
                    // var_dump($totalinteres);
                }
                 //var_dump('Hola');
                // var_dump($totalinteres);
                $calculo->intereses = $totalinteres;
                $calculo->saldoconint = number_format($calculo->saldosinint + $saldot, 2, ".", "");
                $calculo->save();
                break;
            case '2' :
                //$this->BorraData();
                //Tools::log()->error('Entrando Opcion 2');
                //Cuota sobre Saldo
                $amortizacion = number_format($calculo->saldosinint / $calculo->cuotas, 2, ".", "");
                    $calculo->cuota = number_format($amortizacion, 2, ".", "");
                    $interestotal = 0;
                    $interest = 0;
                    for ($contador = 1; $contador <= $calculo->cuotas; $contador++) {
                        $cont = $contador - 1;
                        $nextdate = date("d-m-Y", strtotime($calculo->fechacuota . " +".$cont ." month"));
                        $datacalculo = new DataCalculoInteres();
                        //Capital
                        $capital = number_format($calculo->cuota, 2, ".", "");
                        //Intereses
                        $interes = $saldo * $r;
                        $interestotal = $interestotal + $interes;
                        $interestotal = $saldo * $r;
                        //numero
                        $datacalculo->numero = $contador;
                        //fecha
                        $datacalculo->fecha = $nextdate;
                        //cuota
                        $cuota = number_format($interes + $capital, 2, ".", "");
                        $datacalculo->cuota = $cuota;
                        $saldo = number_format($saldo - $capital, 2, ".", "");
                        //interes
                        $datacalculo->interes = number_format($interes, 2, ".", "");
                        //saldo
                        $datacalculo->saldo = number_format($saldo, 2, ".", "");
                        //Ingresamos capital
                        $datacalculo->capital = number_format($capital, 2, ".", "");
                        //datos para hace rla relacion
                        $datacalculo->recibot = $calculo->codlote . '-' . $contador;
                        $datacalculo->codlote = $calculo->id;
                        $datacalculo->cliente = $calculo->cliente;
                        $datacalculo->save();
                        $interest = $interest + $interes;
                        $saldot = $saldot + $saldo;
                        $calculo->intereses = $interest;
                        $calculo->save();
                    }
                break;
        }
    }
    public function BorraData(): void
    {
        $calculo = new CalculoInteres();
        $codlote = $this->request->get("code");
        if($calculo->loadFromCode($codlote)){
            $datacalculo = new DataCalculoInteres();
            $where = [new DataBaseWhere('codlote', $calculo->id)];
            foreach($datacalculo->all($where, [], 0, 0) as $data) {
                $data->delete();
                //Tools::log()->warning('Registros Borrados exitosamente');
            }
        }
    }
    public function CreaRecibos(): void
    {
        $code = $this->request->get('code');
        //Datos del calculo
        $datalote = new CalculoInteres();
        $datalote->loadFromCode($code);
        
        //datos dentro del calculo de interes
        $datacalculo = new DataCalculoInteres();
        $where = [new DataBaseWhere('codlote', $code)];
        $datan = $datacalculo->all($where, [], 0, 0);

        $lote = new Lote();
        $lote->loadFromCode('', [new DataBaseWhere('codlote', $datalote->codlote)]);

        $contador = 0;
        foreach($datan as $dat)
        {
            $fecha = strtotime($dat->fecha);
            $mes = date('m', $fecha);
            $anio = date('y', $fecha);
            $contador = $contador + 1;
            $recibo = new ReciboSacramento();
            $codlote = $lote->codlote;
            $recibo->codlote = $codlote;
            //agregar el codigo de lote
            $recibo->cliente = $dat->cliente;
            $recibo->fecha = $dat->fecha;
            $recibo->colonia = $lote->colonia;
            $recibo->codrecibo = $codlote .'-'. $mes .'-'. $anio;
            $recibo->save();
            //Agregando data al recibo
            if ($dat->capital == $dat->capital) {
                $linear = new LineaReciboSacramento();
                $linear->codrecibo = $recibo->newCode() - 1;
                $linear->valor = $dat->capital;
                $linear->tipo = 13;
                $linear->save();
            }
            if ($dat->interes == $dat->interes) {
                $linear = new LineaReciboSacramento();
                $linear->codrecibo = $recibo->newCode() - 1;
                $linear->valor = $dat->interes;
                $linear->tipo = 14;
                $linear->save();
            }
        }
    }
    public function VendeLote(): void
    {
        $venta = new VentaLote();
        $venta->cliente = $this->calculo()->cliente;
        $venta->colonia = $this->lote()->colonia;
        $venta->codlote = $this->lote()->codlote;
        $venta->save();
        //Creando Recibos
        $this->CreaRecibos();
        //Crear el contrato segun el lote y el cliente
        $this->CreaContrato();
        Tools::log()->warning('Lote ' . $this->lote()->codlote . ' Vendido');
    }
    public function CreaContrato(): void
    {
        $contrato = new DfDocumento();
        $contrato->idplantilla = 1;
        $contrato->codcliente = $this->calculo()->cliente;
        $contrato->codlote = $this->lote()->codlote;
        $contrato->save();
    }
}
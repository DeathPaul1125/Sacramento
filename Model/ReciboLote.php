<?php

namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Model\Cliente;
use FacturaScripts\Core\Model\Contacto;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Session;

class ReciboLote extends ModelClass
{
    use ModelTrait;

    /** @var float */
    public $cargomora;
    /** @var int */
    public $cliente;
    /** @var string */
    public $codlote;
    /** @var string */
    public $creation_date;
    /** @var int */
    public $diasmora;
    /** @var string */
    public $direccion;
    /** @var string */
    public $emision;
    /** @var int */
    public $formapago;
    /** @var string */
    public $fpago;
    /** @var int */
    public $id;
    /** @var string */
    public $last_nick;
    /** @var string */
    public $last_update;
    /** @var string */
    public $name;
    /** @var string */
    public $nick;
    /** @var float */
    public $numcuota;
    /** @var int */
    public $tipopago;
    /** @var float */
    public $totalpago;
    /** @var float */
    public $vaolorcuota;
    public $recibolote;
    public $pagado;
    public $code;

    public function clear(): void
    {
        parent::clear();
        $this->cargomora = 0.0;
        $this->cliente = 0;
        $this->diasmora = 0;
        $this->emision = date(self::DATE_STYLE);
        $this->formapago = 4;
        $this->numcuota = 0.0;
        $this->tipopago = 0;
        $this->pagado = 0;
        $this->totalpago = 0.0;
        $this->vaolorcuota = 0.0;
    }

    public static function primaryColumn(): string
    {
        return "recibolote";
    }

    public function primaryDescription(): string
    {
        return "Sector " . $this->getLote()->sector . " Manzana " . $this->getLote()->manzana . " Lote " . $this->getLote()->lote;   
    }

     //Cargamos la información del lote
     public function getLote(): Lote
     {
         $lote = new Lote();
         $lote->loadFromCode($this->codlote);
         return $lote;
     }
     
    public static function tableName(): string
    {
        return "reciboslotes";
    }

    public function test(): bool
    {
        if (empty($this->primaryColumnValue())) {
            $this->creation_date = Tools::dateTime();
            $this->last_nick = null;
            $this->last_update = null;
            $this->nick = Session::user()->nick;
        } else {
            $this->creation_date = $this->creationdate ?? Tools::dateTime();
            $this->last_nick = Session::user()->nick;
            $this->last_update = Tools::dateTime();
            $this->nick = $this->nick ?? Session::user()->nick;
        }

        $this->codlote = Tools::noHtml($this->codlote);
        $this->direccion = Tools::noHtml($this->direccion);
        $this->name = Tools::noHtml($this->name);
        return parent::test();
    }
    public function loadFromData(array $data = [], array $exclude = []): void
    {
        parent::loadFromData($data, $exclude);
        $data = new DataCalculoInteres();
        $data->loadFromCode('', [new DataBaseWhere('recibot', $this->recibolote)]);

        $lote = new Lote();
        $lote->loadFromCode('', [new DataBaseWhere('codlote', $data->codlote)]);
        
        $colonia = new Colonia();
        $colonia->loadFromCode('', [new DataBaseWhere('id', $lote->colonia)]);

        $cliente = new Contacto();
        $cliente->loadFromCode('', [new DataBaseWhere('codcliente', $data->cliente)]);

        //validar el calculo del cobro de mora por días
        //Guardamos los datos del pago del lote
        $this->cliente = $data->cliente;
        $this->codlote = $data->codlote;
        $this->numcuota = $data->numero;
        $this->direccion = $cliente->direccion;
        $this->fpago = $data->fecha;
        $diasmora = date_diff(date_create($this->emision), date_create($data->fecha));
        $this->diasmora = $diasmora->d;
        $this->vaolorcuota = number_format($data->cuota, 2, ".", "");
        //Calculos de mora a pagar
        if ($this->diasmora > 0 && $this->diasmora < 30) {
            $this->cargomora = $this->vaolorcuota * 0.05;
        }elseif ($this->diasmora > 30 && $this->diasmora < 60){
            $this->cargomora = $this->vaolorcuota * 0.1;
        }elseif($this->diasmora > 60 && $this->diasmora < 90){
            $this->cargomora = $this->vaolorcuota * 0.15;
        }elseif($this->diasmora > 90 && $this->diasmora < 120){
            $this->cargomora = $this->vaolorcuota * 0.2;
        }elseif($this->diasmora > 120 && $this->diasmora < 150){
            $this->cargomora = $this->vaolorcuota * 0.25;
        }elseif($this->diasmora > 150 && $this->diasmora < 180){
            $this->cargomora = $this->vaolorcuota * 0.3;
        }elseif($this->diasmora > 180 && $this->diasmora < 210){
            $this->cargomora = $this->vaolorcuota * 0.32;
        }
        //Total a pagar
        $this->totalpago = $this->vaolorcuota + $this->cargomora;
        $this->save(); 
        if ($this->pagado == 1) {
            $data->pagado = 1;
            $data->save();
        }elseif ($this->pagado == 0) {
            $data->pagado = 0;
            $data->save();
        }
    }
    public function url(string $type = 'auto', string $list = 'ListCalculoInteres?List'): string
    {
        return parent::url($type, $list);
    }
}
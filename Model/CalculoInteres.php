<?php

namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Base\DataBase;
use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Session;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;

class CalculoInteres extends ModelClass
{
    use ModelTrait;

    /** @var string */
    public $codlote;
    /** @var float */
    public $costolote;
    /** @var string */
    public $creation_date;
    /** @var float */
    public $cuota;
    /** @var int */
    public $cuotas;
    /** @var float */
    public $descuento;
    /** @var float */
    public $enganche;
    /** @var string */
    public $fechacuota;
    /** @var float */
    public $fondo;
    /** @var float */
    public $frente;
    /** @var int */
    public $id;
    /** @var float */
    public $intereses;
    /** @var string */
    public $last_nick;
    /** @var string */
    public $last_update;
    /** @var float */
    public $metros2;
    /** @var string */
    public $name;
    /** @var string */
    public $nick;
    /** @var float */
    public $preciom2;
    /** @var float */
    public $saldoconint;
    /** @var float */
    public $saldosinint;
    /** @var float */
    public $tasaint;
    /** @var int */
    public $tipoint;
    /** @var float */
    public $varas2;
    public $cliente;
    public $colonia;
    public $cliente2;
    public $costototal;

    public function clear(): void
    {
        parent::clear();
        $this->costolote = 0.0;
        $this->cuota = 0.0;
        $this->cuotas = 0;
        $this->descuento = 0.0;
        $this->enganche = 0.0;
        $this->fondo = 0.0;
        $this->frente = 0.0;
        $this->intereses = 0.0;
        $this->metros2 = 0.0;
        $this->preciom2 = 0.0;
        $this->saldoconint = 0.0;
        $this->saldosinint = 0.0;
        $this->tasaint = 0.0;
        $this->tipoint = 0;
        $this->varas2 = 0.0;
    }

    public static function primaryColumn(): string
    {
        return "id";
    }

    public static function tableName(): string
    {
        return "calculosintereses";
    }
    //Cargamos la información del lote
    public function getLote(): Lote
    {
        $lote = new Lote();
        $lote->loadFromCode($this->codlote);
        return $lote;
    }
    //Cargamos la información de la Colonia
    public function getColonia(): Colonia
    {
        $colonia = new Colonia();
        $colonia->loadFromCode('',[new DataBaseWhere('codcolonia', $this->getLote()->colonia)]);
        return $colonia;
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
        $this->name = Tools::noHtml($this->name);
        return parent::test();
    }
    public function loadFromData(array $data = [], array $exclude = []): void
    {
        parent::loadFromData($data, $exclude);
        
        $this->frente = $this->getLote()->frente;
        $this->fondo = $this->getLote()->fondo;
        $this->metros2 = $this->getLote()->totalmetros;
        $this->varas2 = $this->getLote()->varas2;
        $this->preciom2 = $this->getLote()->preciom;
        $this->costolote = $this->getLote()->costo;
        $this->tasaint = $this->getColonia()->interes;

        $r = $this->tasaint /12 ;
        $base = pow(1 + $r, $this->cuotas);
        $tiempo = $this->cuotas/12;
        //saldo sin interes
        //resta del costo total del lote - enganche
        $this->saldosinint = $this->costolote - ($this->descuento + $this->enganche);

        $this->costototal = $this->saldoconint + $this->enganche;

        $this->saldoconint = $this->intereses + $this->saldosinint;

    }
    public function primaryDescriptionColumn(): string
    {
        return 'codlote';
    }
}
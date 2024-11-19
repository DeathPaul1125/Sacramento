<?php

namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Session;

class EstadoCuentaLote extends ModelClass
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

    public function clear() 
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
    public function primaryDescriptionColumn(): string
    {
        return 'codlote';
    }
}
<?php

namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Session;

class DataEstadoCuentaLote extends ModelClass
{
    use ModelTrait;

    /** @var float */
    public $capital;
    /** @var int */
    public $cliente;
    /** @var string */
    public $codlote;
    /** @var string */
    public $creation_date;
    /** @var float */
    public $cuota;
    /** @var int */
    public $id;
    /** @var float */
    public $interes;
    /** @var string */
    public $last_nick;
    /** @var string */
    public $last_update;
    /** @var string */
    public $name;
    /** @var string */
    public $nick;
    /** @var int */
    public $numero;
    /** @var float */
    public $saldo;
    public $fecha;
    public $recibot;
    public $pagado;

    public function clear() 
    {
        parent::clear();
        $this->capital = 0.0;
        $this->cuota = 0.0;
        $this->interes = 0.0;
        $this->numero = 0;
        $this->saldo = 0.0;
        $this->pagado = false;
    }

    public static function primaryColumn(): string
    {
        return "id";
    }

    public static function tableName(): string
    {
        return "datacalculosinteres";
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
}
<?php

namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Session;

class VentaLote extends ModelClass
{
    use ModelTrait;

    /** @var string */
    public $codlote;
    /** @var int */
    public $colonia;
    /** @var string */
    public $creation_date;
    /** @var string */
    public $fecha;
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
    public $saldo;
    /** @var int */
    public $tipo;
    /** @var float */
    public $total;
    public $cliente;
    public $pagado;

    public function clear(): void
    {
        parent::clear();
        $this->colonia = 0;
        $this->fecha = date(self::DATE_STYLE);
        $this->saldo = 0.0;
        $this->tipo = 0;
        $this->total = 0.0;
    }

    public static function primaryColumn(): string
    {
        return "id";
    }

    public static function tableName(): string
    {
        return "lotesvendidos";
    }

    public function primaryDescriptionColumn(): string
    {
        return 'codlote';
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

        $lote = new Lote();
        $lote->loadFromCode('', [new DataBaseWhere('codlote', $this->codlote)]);

        $calculointeres = new CalculoInteres();
        $calculointeres->loadFromCode('', [new DataBaseWhere('codlote', $this->codlote)]);
        
        $this->total = $calculointeres->saldoconint;
        
        //Lo que se ha pagado
        $recibos = new ReciboSacramento();
        $where = [new DataBaseWhere("codlote", $this->codlote)];
        $pagado = 0;
        foreach ($recibos->all($where) as $recibo) {
            $pagado += $recibo->total;
        }
        $this->pagado = $pagado;

        //diferencia
        $this->saldo = $this->total - $this->pagado;
    }
}
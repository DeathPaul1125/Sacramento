<?php

namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Session;


class ReciboSacramento extends ModelClass
{
    use ModelTrait;

    public  $cliente;
    public  $codlote;
    public  $codrecibo;
    public  $creation_date;
    public  $fecha;
    public  $id;
    public  $last_nick;
    public  $last_update;
    public  $mes;
    public  $name;
    public  $nick;
    public  $notas;
    public  $tipo;
    public  $total;
    public $colonia;
    public $tipopago;

    public function clear(): void
    {
        parent::clear();
        $this->fecha = date(self::DATE_STYLE);
        $this->mes = 0;
        $this->tipo = 0;
        $this->total = 0.0;
    }
    public static function primaryColumn(): string
    {
        //aca tenía codlote y lo cambie por id
        return "id";
    }

    public static function tableName(): string
    {
        return "recibossacramento";
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

        $this->cliente = Tools::noHtml($this->cliente);
        $this->codlote = Tools::noHtml($this->codlote);
        $this->codrecibo = Tools::noHtml($this->codrecibo);
        $this->name = Tools::noHtml($this->name);
        $this->notas = Tools::noHtml($this->notas);
        return parent::test();
    }
    public function loadFromData(array $data = [], array $exclude = []): void
    {
        parent::loadFromData($data, $exclude);
        $lineas = new LineaReciboSacramento();
        $where = [new DataBaseWhere("codrecibo", $this->id)];
        $total = 0;
        foreach ($lineas->all($where) as $linea) {
            $total += $linea->valor;
        }
        $this->total = $total;
    }

    protected function saveInsert(array $values = []): bool
    {
        if (empty($this->id)) {
            $this->id = (string)$this->newCode();
        }

        $return = parent::saveInsert($values);
        $id = $this->id;
       // $idplan->save();
       
       
        return $return;
    }
   
}
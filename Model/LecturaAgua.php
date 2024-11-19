<?php

namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Model\Contacto;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Session;

class LecturaAgua extends ModelClass
{
    use ModelTrait;

    /** @var string */
    public $codlote;
    /** @var string */
    public $codrecibo;
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
    /** @var int */
    public $lectura;
    /** @var int */
    public $mes;
    /** @var string */
    public $name;
    /** @var string */
    public $nick;
    public $lecturacontador;

    public function clear() 
    {
        parent::clear();
        $this->fecha = date(self::DATE_STYLE);
        $this->lectura = 0;
        $this->mes = date("m", strtotime($this->fecha));
    }

    public static function primaryColumn(): string
    {
        return "id";
    }

    public static function tableName(): string
    {
        return "lecturasagua";
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
        $this->codrecibo = Tools::noHtml($this->codrecibo);
        $this->name = Tools::noHtml($this->name);
        return parent::test();
    }
    public function loadFromData(array $data = [], array $exclude = [])
    {
        parent::loadFromData($data, $exclude);

        $lote = new Lote();
        $lote->loadFromCode('', [new DataBaseWhere('codlote', $this->codlote)]);

/*         if($lote->pagua == 0){
            Tools::log()->error('El cliente NO paga Agua');
            return false;
        } */
        if($lote->cortado == 1){
            Tools::log()->error('El cliente Tiene Cortado el servicio de Agua');
            return false;
        }
        if($lote->suspendido == 1){
            Tools::log()->error('El cliente Tiene El servicio de Agua Suspendido');
        }
        $this->codrecibo = $this->codlote ."-". date("m", strtotime($this->fecha)) ."-". date("Y", strtotime($this->fecha));
    }
}
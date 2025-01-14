<?php

namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Session;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;

class LecturaInicial extends ModelClass
{
    use ModelTrait;

    /** @var string */
    public $codlote;
    /** @var string */
    public $creation_date;
    /** @var int */
    public $id;
    /** @var string */
    public $last_nick;
    /** @var string */
    public $last_update;
    /** @var int */
    public $lecturai;
    /** @var int */
    public $lecturat;
    /** @var string */
    public $name;
    /** @var string */
    public $nick;

    public function clear(): void
    {
        parent::clear();
    }

    public static function primaryColumn(): string
    {
        return "id";
    }

    public static function tableName(): string
    {
        return "lecturasiniciales";
    }
    public function primaryDescription(): string
    {
        return "Sector " . $this->getLote()->sector . " Manzana " . $this->getLote()->manzana . " Lote " . $this->getLote()->lote . " Colonia " . $this->getColonia()->nombre;   
    }

     //Cargamos la información del lote
     public function getLote(): Lote
     {
         $lote = new Lote();
         $lote->loadFromCode($this->codlote);
         return $lote;
     }
     public function getColonia(): Colonia
     {
         $colonia = new Colonia();
         $colonia->loadFromCode('',[new DataBaseWhere('id', $this->getLote()->colonia)]);
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

        if($this->lecturai > $this->lecturat){ 
           $this->lecturat = $this->lecturai;
        }

        $this->codlote = Tools::noHtml($this->codlote);
        $this->name = Tools::noHtml($this->name);
        return parent::test();
    }
    public function loadFromData(array $data = [], array $exclude = [])
    {
        parent::loadFromData($data, $exclude);
        //$this->lecturat = $this->lecturai;
    }
}
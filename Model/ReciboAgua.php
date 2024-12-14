<?php

namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Session;

class ReciboAgua extends ModelClass
{
    use ModelTrait;

    /** @var int */
    public $cliente;
    /** @var string */
    public $codlote;
    /** @var string */
    public $codrecibo;
    /** @var string */
    public $creation_date;
    /** @var string */
    public $flectura;
    /** @var string */
    public $fpago;
    /** @var int */
    public $id;
    /** @var string */
    public $last_nick;
    /** @var string */
    public $last_update;
    /** @var int */
    public $lectura;
    /** @var float */
    public $mora;
    /** @var string */
    public $name;
    /** @var string */
    public $nick;
    /** @var bool */
    public $pagado;
    /** @var float */
    public $total;
    /** @var float */
    public $valor;
    public $exceso;
    public $colonia;
    public $lecturacontador;
    public $observaciones;

    public function clear(): void
    {
        parent::clear();
        $this->cliente = 0;
        $this->fpago = date(self::DATE_STYLE);
        $this->lectura = 0;
        $this->mora = 0.0;
        $this->pagado = false;
        $this->total = 0.0;
        $this->valor = 0.0;
        $this->exceso = 0;
    }

    public static function primaryColumn(): string
    {
        return "id";
    }

    public static function tableName(): string
    {
        return "recibosagua";
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
    public function delete(): bool
    {
        if (false === parent::delete()) {
            return false;
        }

        $this->CorregirLecturaTotal();
        return true;
    }
    public function CorregirLecturaTotal(): void
    {
        $lecturai = new LecturaInicial();
        $where = [new DataBaseWhere('codlote', $this->codlote)];
        $lecturai->loadFromCode('', $where);
        $resta = $lecturai->lecturat - $this->lectura;
        $lecturai->lecturat = $resta;
        $lecturai->save();
    }
}
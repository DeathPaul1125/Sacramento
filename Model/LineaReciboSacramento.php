<?php
namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Session;

class LineaReciboSacramento extends ModelClass
{
    use ModelTrait;

    /** @var int */
    public $codrecibo;

    /** @var string */
    public $creation_date;

    /** @var string */
    public $descripcion;

    /** @var string */
    public $documento;

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

    /** @var string */
    public $tipo;

    /** @var float */
    public $valor;

    public function clear() 
    {
        parent::clear();
        $this->codrecibo = 0;
        $this->valor = 0.0;
    }

    public static function primaryColumn(): string
    {
        return "id";
    }

    public static function tableName(): string
    {
        return "lineasrecibossacramento";
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

        $this->descripcion = Tools::noHtml($this->descripcion);
        $this->documento = Tools::noHtml($this->documento);
        $this->name = Tools::noHtml($this->name);
        $this->tipo = Tools::noHtml($this->tipo);
        return parent::test();
    }
}

<?php

namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Session;

class Colonia extends ModelClass
{
    use ModelTrait;

    /** @var float */
    public $comisionv;
    /** @var string */
    public $creationdate;
    /** @var int */
    public $dcobro;
    /** @var float */
    public $derechom3;
    /** @var string */
    public $direccion;
    /** @var int */
    public $id;
    /** @var float */
    public $interes;
    /** @var float */
    public $interesmora;
    /** @var string */
    public $lastnick;
    /** @var string */
    public $lastupdate;
    /** @var float */
    public $moraagua;
    /** @var string */
    public $name;
    /** @var string */
    public $nick;
    /** @var string */
    public $nombre;
    /** @var int */
    public $ultimodpago;
    /** @var float */
    public $vagua;
    /** @var float */
    public $valm2;
    /** @var float */
    public $vexceso;
    public $reconexion;
    public $representante;
    public $cuirepresentante;
    public $codcolonia;
    
    public $edad;
    public $estadocivil;
    public $profesion;
    public $departamento;
    public $cuiletras;
    public $fnacimiento;
    public $edadletras;

    public function clear() 
    {
        parent::clear();
        $this->comisionv = 0.20;
        $this->dcobro = 0;
        $this->derechom3 = 0.0;
        $this->interes = 0.20;
        $this->interesmora = 0.0;
        $this->moraagua = 0.0;
        $this->ultimodpago = 0;
        $this->vagua = 0.0;
        $this->valm2 = 0.0;
        $this->vexceso = 0.0;
    }

    public static function primaryColumn(): string
    {
        return "id";
    }

    public static function tableName(): string
    {
        return "colonias";
    }
    public function primaryDescriptionColumn(): string
    {
        return 'nombre';
    }

    public function test(): bool
    {
        if ($this->exists()) {
            $this->lastnick = Session::user()->nick;
            $this->lastupdate = date(self::DATETIME_STYLE);
        } else {
            $this->creationdate = date(self::DATETIME_STYLE);
            $this->lastnick = null;
            $this->lastupdate = null;
            $this->nick = Session::user()->nick;
        }

        $this->direccion = $this->toolBox()->utils()->noHtml($this->direccion);
        $this->name = $this->toolBox()->utils()->noHtml($this->name);
        $this->nombre = $this->toolBox()->utils()->noHtml($this->nombre);
        return parent::test();
    }
}
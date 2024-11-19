<?php

namespace FacturaScripts\Plugins\Sacramento\Model;

use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Session;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;

class Lote extends ModelClass
{
    use ModelTrait;

    public $colonia;
    public $contador;
    public $cortado;
    public $costo;
    public $creation_date;
    public $direccion;
    public $escriturado;
    public $estado;
    public $finca;
    public $finstalacion;
    public $folio;
    public $fondo;
    public $frente;
    public $id;
    public $last_nick;
    public $last_update;
    public $libro;
    public $lote;
    public $manzana;
    public $medidagen;
    public $name;
    public $nick;
    public $observaciones;
    public $pagua;
    public $preciom;
    public $sector;
    public $sefactura;
    public $suspendido;
    public $tipo;
    public $totalmetros;
    public $varas2;
    public $codlote;

    public function clear(): void
    {
        parent::clear();
        $this->cortado = 0;
        $this->costo = 0.0;
        $this->escriturado = 0;
        $this->estado = 1;
        $this->finca = 0;
        $this->folio = 0;
        $this->fondo = 0.0;
        $this->frente = 0.0;
        $this->lote = 0;
        $this->manzana = 0;
        $this->preciom = 0.0;
        $this->sector = 0;
        $this->totalmetros = 0.0;
        $this->varas2 = 0.0;
    }

    public static function primaryColumn(): string
    {
        return "id";
    }
    public static function tableName(): string
    {
        return "lotes";
    }
    public function getColonia(): Colonia
    {
        $colonia = new Colonia();
        $colonia->loadFromCode('',[new DataBaseWhere('id', $this->colonia)]);
        return $colonia;
    }
    public function primaryDescription(): string
    {
        return "Sector " . $this->sector . " Manzana " . $this->manzana . " Lote " . $this->lote;
    }
    public function test(): bool
    {
        if (empty($this->primaryColumnValue()))
        {
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

        $this->contador = Tools::noHtml($this->contador);
        $this->direccion = Tools::noHtml($this->direccion);
        $this->libro = Tools::noHtml($this->libro);
        $this->medidagen = Tools::noHtml($this->medidagen);
        $this->name = Tools::noHtml($this->name);
        $this->observaciones = Tools::noHtml($this->observaciones);
        return parent::test();
    }
    public function loadFromData(array $data = [], array $exclude = []): void
    {
        parent::loadFromData($data, $exclude);

        $codcolonia = $this->getColonia()->codcolonia;
        //Codigo de lote
        $this->codlote = $codcolonia . $this->sector . $this->manzana . $this->lote;
        //calculo del total de metros
        $this->totalmetros = $this->frente * $this->fondo;
        //calculo de varas cuadradas
        $this->varas2 = $this->totalmetros * 1.4311;
        //Calculo de precio por metro
        if ($this->totalmetros > 0){
            $this->preciom = $this->costo / $this->totalmetros;
        }
    }
}
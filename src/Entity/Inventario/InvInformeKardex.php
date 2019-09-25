<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_informe_kardex")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvInformeKardexRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvInformeKardex
{
    public $infoLog = [
        "primaryKey" => "codigoInformeKardexPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_informe_kardex_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoInformeKardexPk;

    /**
     * @ORM\Column(name="codigo_movimiento_detalle", type="integer", nullable=true)
     */
    private $codigoMovimientoDetalle;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="nombre_documento", type="string", length=50, nullable=true)
     */
    private $nombreDocumento;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="nombreItem", type="string", length=400, nullable=true)
     */
    private $nombreItem;

    /**
     * @ORM\Column(name="codigo_bodega_fk", type="string", length=10, nullable=true)
     */
    private $codigoBodegaFk;

    /**
     * @ORM\Column(name="referencia", type="string",length=50, nullable=true)
     */
    private $referencia;

    /**
     * @ORM\Column(name="lote_fk", type="string", length=40, nullable=true)
     */
    private $loteFk;

    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */
    private $fechaVencimiento;

    /**
     * @ORM\Column(name="cantidad", type="integer", options={"default" : 0})
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="cantidad_operada", type="integer", options={"default" : 0})
     */
    private $cantidadOperada = 0;

    /**
     * @ORM\Column(name="saldo", type="integer", options={"default" : 0})
     */
    private $saldo = 0;

    /**
     * @ORM\Column(name="operacion_inventario", type="smallint", nullable=true, options={"default" : 0})
     */
    private $operacionInventario = 0;

    /**
     * @ORM\Column(name="codigo_remision_detalle_fk", type="integer", nullable=true)
     */
    private $codigoRemisionDetalleFk;

    /**
     * @ORM\Column(name="vr_costo", type="float", options={"default" : 0})
     */
    private $vrCosto = 0;

    /**
     * @ORM\Column(name="vr_precio", type="float", options={"default" : 0})
     */
    private $vrPrecio = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @return mixed
     */
    public function getCodigoInformeKardexPk()
    {
        return $this->codigoInformeKardexPk;
    }

    /**
     * @param mixed $codigoInformeKardexPk
     */
    public function setCodigoInformeKardexPk($codigoInformeKardexPk): void
    {
        $this->codigoInformeKardexPk = $codigoInformeKardexPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoMovimientoDetalle()
    {
        return $this->codigoMovimientoDetalle;
    }

    /**
     * @param mixed $codigoMovimientoDetalle
     */
    public function setCodigoMovimientoDetalle($codigoMovimientoDetalle): void
    {
        $this->codigoMovimientoDetalle = $codigoMovimientoDetalle;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getNombreDocumento()
    {
        return $this->nombreDocumento;
    }

    /**
     * @param mixed $nombreDocumento
     */
    public function setNombreDocumento($nombreDocumento): void
    {
        $this->nombreDocumento = $nombreDocumento;
    }

    /**
     * @return mixed
     */
    public function getCodigoItemFk()
    {
        return $this->codigoItemFk;
    }

    /**
     * @param mixed $codigoItemFk
     */
    public function setCodigoItemFk($codigoItemFk): void
    {
        $this->codigoItemFk = $codigoItemFk;
    }

    /**
     * @return mixed
     */
    public function getNombreItem()
    {
        return $this->nombreItem;
    }

    /**
     * @param mixed $nombreItem
     */
    public function setNombreItem($nombreItem): void
    {
        $this->nombreItem = $nombreItem;
    }

    /**
     * @return mixed
     */
    public function getCodigoBodegaFk()
    {
        return $this->codigoBodegaFk;
    }

    /**
     * @param mixed $codigoBodegaFk
     */
    public function setCodigoBodegaFk($codigoBodegaFk): void
    {
        $this->codigoBodegaFk = $codigoBodegaFk;
    }

    /**
     * @return mixed
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * @param mixed $referencia
     */
    public function setReferencia($referencia): void
    {
        $this->referencia = $referencia;
    }

    /**
     * @return mixed
     */
    public function getLoteFk()
    {
        return $this->loteFk;
    }

    /**
     * @param mixed $loteFk
     */
    public function setLoteFk($loteFk): void
    {
        $this->loteFk = $loteFk;
    }

    /**
     * @return mixed
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * @param mixed $fechaVencimiento
     */
    public function setFechaVencimiento($fechaVencimiento): void
    {
        $this->fechaVencimiento = $fechaVencimiento;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return mixed
     */
    public function getCantidadOperada()
    {
        return $this->cantidadOperada;
    }

    /**
     * @param mixed $cantidadOperada
     */
    public function setCantidadOperada($cantidadOperada): void
    {
        $this->cantidadOperada = $cantidadOperada;
    }

    /**
     * @return mixed
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * @param mixed $saldo
     */
    public function setSaldo($saldo): void
    {
        $this->saldo = $saldo;
    }

    /**
     * @return mixed
     */
    public function getOperacionInventario()
    {
        return $this->operacionInventario;
    }

    /**
     * @param mixed $operacionInventario
     */
    public function setOperacionInventario($operacionInventario): void
    {
        $this->operacionInventario = $operacionInventario;
    }

    /**
     * @return mixed
     */
    public function getCodigoRemisionDetalleFk()
    {
        return $this->codigoRemisionDetalleFk;
    }

    /**
     * @param mixed $codigoRemisionDetalleFk
     */
    public function setCodigoRemisionDetalleFk($codigoRemisionDetalleFk): void
    {
        $this->codigoRemisionDetalleFk = $codigoRemisionDetalleFk;
    }

    /**
     * @return mixed
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * @param mixed $vrCosto
     */
    public function setVrCosto($vrCosto): void
    {
        $this->vrCosto = $vrCosto;
    }

    /**
     * @return mixed
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * @param mixed $vrPrecio
     */
    public function setVrPrecio($vrPrecio): void
    {
        $this->vrPrecio = $vrPrecio;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }



}

<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_dotacion_detalle")
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuDotacionDetalleRepository")
 */
class RhuDotacionDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dotacion_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDotacionDetallePk;

    /**
     * @ORM\Column(name="codigo_dotacion_fk", type="integer", nullable=true)
     */
    private $codigoDotacionFk;

    /**
     * @ORM\Column(name="codigo_dotacion_elemento_fk", type="integer", nullable=true)
     */
    private $codigoDotacionElementoFk;

//    /**
//     * @ORM\Column(name="codigo_inv_item_fk", type="integer", nullable=true)
//     */
//    private $codigoInvItemFk;
//
//    /**
//     * @ORM\Column(name="codigo_dotacion_detalle_enlace_fk", type="integer", nullable=true)
//     */
//    private $codigoDotacionDetalleEnlaceFk;
//
//    /**
//     * @ORM\Column(name="codigo_movimiento_detalle_fk", type="integer", nullable=true)
//     */
//    private $codigoMovimientoDetalleFk;

    /**
     * @ORM\Column(name="cantidad_asignada", type="integer", nullable=true)
     */
    private $cantidadAsignada = 0;

    /**
     * @ORM\Column(name="cantidad_devuelta", type="integer", nullable=true)
     */
    private $cantidadDevuelta = 0;

//    /**
//     * @ORM\Column(name="nombre_item_inventario", type="string",length=100, nullable=true)
//     */
//    private $nombreItemInventario = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuDotacion", inversedBy="dotacionesDetallesDotacionRel")
     * @ORM\JoinColumn(name="codigo_dotacion_fk", referencedColumnName="codigo_dotacion_pk")
     */
    protected $dotacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuDotacionElemento", inversedBy="elementosDotacionesDetalleDotacionElementoRel")
     * @ORM\JoinColumn(name="codigo_dotacion_elemento_fk", referencedColumnName="codigo_dotacion_elemento_pk")
     */
    protected $dotacionElementoRel;

    /**
     * @return mixed
     */
    public function getCodigoDotacionDetallePk()
    {
        return $this->codigoDotacionDetallePk;
    }

    /**
     * @param mixed $codigoDotacionDetallePk
     */
    public function setCodigoDotacionDetallePk($codigoDotacionDetallePk): void
    {
        $this->codigoDotacionDetallePk = $codigoDotacionDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDotacionFk()
    {
        return $this->codigoDotacionFk;
    }

    /**
     * @param mixed $codigoDotacionFk
     */
    public function setCodigoDotacionFk($codigoDotacionFk): void
    {
        $this->codigoDotacionFk = $codigoDotacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDotacionElementoFk()
    {
        return $this->codigoDotacionElementoFk;
    }

    /**
     * @param mixed $codigoDotacionElementoFk
     */
    public function setCodigoDotacionElementoFk($codigoDotacionElementoFk): void
    {
        $this->codigoDotacionElementoFk = $codigoDotacionElementoFk;
    }

    /**
     * @return mixed
     */
    public function getCantidadAsignada()
    {
        return $this->cantidadAsignada;
    }

    /**
     * @param mixed $cantidadAsignada
     */
    public function setCantidadAsignada($cantidadAsignada): void
    {
        $this->cantidadAsignada = $cantidadAsignada;
    }

    /**
     * @return mixed
     */
    public function getCantidadDevuelta()
    {
        return $this->cantidadDevuelta;
    }

    /**
     * @param mixed $cantidadDevuelta
     */
    public function setCantidadDevuelta($cantidadDevuelta): void
    {
        $this->cantidadDevuelta = $cantidadDevuelta;
    }

    /**
     * @return mixed
     */
    public function getDotacionRel()
    {
        return $this->dotacionRel;
    }

    /**
     * @param mixed $dotacionRel
     */
    public function setDotacionRel($dotacionRel): void
    {
        $this->dotacionRel = $dotacionRel;
    }

    /**
     * @return mixed
     */
    public function getDotacionElementoRel()
    {
        return $this->dotacionElementoRel;
    }

    /**
     * @param mixed $dotacionElementoRel
     */
    public function setDotacionElementoRel($dotacionElementoRel): void
    {
        $this->dotacionElementoRel = $dotacionElementoRel;
    }



}
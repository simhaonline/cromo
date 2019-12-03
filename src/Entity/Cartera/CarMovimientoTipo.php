<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarMovimientoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoMovimientoTipoPk"},message="Ya existe un registro con el mismo codigo")
 */
class CarMovimientoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoMovimientoTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_movimiento_tipo_pk", type="string", length=10)
     */
    private $codigoMovimientoTipoPk;

    /**
     * @ORM\Column(name="nombre" ,type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo" ,type="integer")
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="orden" ,type="integer")
     */
    private $orden = 0;

    /**
     * @ORM\Column(name="codigo_comprobante_fk" , type="string" , nullable=true)
     */
    private $codigoComprobanteFk = null;

    /**
     * @ORM\Column(name="codigo_movimiento_clase_fk" , type="string" , length=10, nullable=true)
     */
    private $codigoMovimientoClaseFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarMovimientoClase" , inversedBy="movimientosTiposMovimientoClaseRel")
     * @ORM\JoinColumn(name="codigo_movimiento_clase_fk" , referencedColumnName="codigo_movimiento_clase_pk")
     */
    private $movimientoClaseRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarMovimiento" ,mappedBy="movimientoTipoRel")
     */
    private $movimientosMovimientoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoMovimientoTipoPk()
    {
        return $this->codigoMovimientoTipoPk;
    }

    /**
     * @param mixed $codigoMovimientoTipoPk
     */
    public function setCodigoMovimientoTipoPk($codigoMovimientoTipoPk): void
    {
        $this->codigoMovimientoTipoPk = $codigoMovimientoTipoPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return int
     */
    public function getConsecutivo(): int
    {
        return $this->consecutivo;
    }

    /**
     * @param int $consecutivo
     */
    public function setConsecutivo(int $consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * @return int
     */
    public function getOrden(): int
    {
        return $this->orden;
    }

    /**
     * @param int $orden
     */
    public function setOrden(int $orden): void
    {
        $this->orden = $orden;
    }

    /**
     * @return null
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * @param null $codigoComprobanteFk
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk): void
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;
    }

    /**
     * @return mixed
     */
    public function getMovimientosMovimientoTipoRel()
    {
        return $this->movimientosMovimientoTipoRel;
    }

    /**
     * @param mixed $movimientosMovimientoTipoRel
     */
    public function setMovimientosMovimientoTipoRel($movimientosMovimientoTipoRel): void
    {
        $this->movimientosMovimientoTipoRel = $movimientosMovimientoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoMovimientoClaseFk()
    {
        return $this->codigoMovimientoClaseFk;
    }

    /**
     * @param mixed $codigoMovimientoClaseFk
     */
    public function setCodigoMovimientoClaseFk($codigoMovimientoClaseFk): void
    {
        $this->codigoMovimientoClaseFk = $codigoMovimientoClaseFk;
    }

    /**
     * @return mixed
     */
    public function getMovimientoClaseRel()
    {
        return $this->movimientoClaseRel;
    }

    /**
     * @param mixed $movimientoClaseRel
     */
    public function setMovimientoClaseRel($movimientoClaseRel): void
    {
        $this->movimientoClaseRel = $movimientoClaseRel;
    }
    

}

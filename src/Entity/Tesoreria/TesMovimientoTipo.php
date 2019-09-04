<?php


namespace App\Entity\Tesoreria;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Tesoreria\TesMovimientoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TesMovimientoTipo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_movimiento_tipo_pk",type="integer")
     */
    private $codigoMovimientoTipoPK;

    /**
     * @ORM\Column(name="nombre" ,type="string")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TesMovimiento", mappedBy="movimientoTipoRel")
     */
    private $movimientosMovimientoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoMovimientoTipoPK()
    {
        return $this->codigoMovimientoTipoPK;
    }

    /**
     * @param mixed $codigoMovimientoTipoPK
     */
    public function setCodigoMovimientoTipoPK($codigoMovimientoTipoPK): void
    {
        $this->codigoMovimientoTipoPK = $codigoMovimientoTipoPK;
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

}
<?php


namespace App\Entity\Tesoreria;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Tesoreria\TesMovimientoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TesMovimiento
{
    public $infoLog = [
        "primaryKey" => "codigoMovimientoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_movimiento_pk",type="integer")
     */
    private $codigoMovimientoPk;

    /**
     * @ORM\Column(name="codigo_movimiento_tipo_fk" , type="integer", nullable=true)
     */
    private $codigoMovimientoTipoFk;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="TesMovimientoTipo" ,inversedBy="movimientosMovimientoTipoRel")
     * @ORM\JoinColumn(name="codigo_movimiento_tipo_fk" , referencedColumnName="codigo_movimiento_tipo_pk")
     */
    private $movimientoTipoRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoMovimientoPk()
    {
        return $this->codigoMovimientoPk;
    }

    /**
     * @param mixed $codigoMovimientoPk
     */
    public function setCodigoMovimientoPk($codigoMovimientoPk): void
    {
        $this->codigoMovimientoPk = $codigoMovimientoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoMovimientoTipoFk()
    {
        return $this->codigoMovimientoTipoFk;
    }

    /**
     * @param mixed $codigoMovimientoTipoFk
     */
    public function setCodigoMovimientoTipoFk($codigoMovimientoTipoFk): void
    {
        $this->codigoMovimientoTipoFk = $codigoMovimientoTipoFk;
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
    public function getMovimientoTipoRel()
    {
        return $this->movimientoTipoRel;
    }

    /**
     * @param mixed $movimientoTipoRel
     */
    public function setMovimientoTipoRel($movimientoTipoRel): void
    {
        $this->movimientoTipoRel = $movimientoTipoRel;
    }

}
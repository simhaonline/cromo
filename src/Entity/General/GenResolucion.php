<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenResolucionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenResolucion
{
    public $infoLog = [
        "primaryKey" => "codigoResolucionPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_resolucion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoResolucionPk;

    /**
     * @ORM\Column(name="numero", type="float", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="prefijo", type="string",length=5, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\Column(name="numero_desde", type="float", nullable=true)
     */
    private $numeroDesde;

    /**
     * @ORM\Column(name="numero_hasta", type="float", nullable=true)
     */
    private $numeroHasta;

    /**
     * @ORM\Column(name="llave_tecnica", type="string",length=500, nullable=true)
     */
    private $llaveTecnica;

    /**
     * @ORM\Column(name="pin", type="string",length=20, nullable=true)
     */
    private $pin;

    /**
     * @ORM\Column(name="ambiente", type="string",length=10, nullable=true)
     */
    private $ambiente;

    /**
     * @ORM\Column(name="set_pruebas", type="string",length=500, nullable=true)
     */
    private $setPruebas;

    /**
     * @ORM\Column(name="codigo_empresa_fk", type="string",length=10, nullable=true)
     */
    private $codigoEmpresaFk;

    /**
     * @ORM\Column(name="prueba", type="boolean", options={"default":false})
     */
    private $prueba = false;

//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvMovimiento", mappedBy="resolucionRel")
//     */
//    protected $movimientosResolucionRel;

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
    public function getCodigoResolucionPk()
    {
        return $this->codigoResolucionPk;
    }

    /**
     * @param mixed $codigoResolucionPk
     */
    public function setCodigoResolucionPk($codigoResolucionPk): void
    {
        $this->codigoResolucionPk = $codigoResolucionPk;
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
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * @param mixed $fechaDesde
     */
    public function setFechaDesde($fechaDesde): void
    {
        $this->fechaDesde = $fechaDesde;
    }

    /**
     * @return mixed
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * @param mixed $fechaHasta
     */
    public function setFechaHasta($fechaHasta): void
    {
        $this->fechaHasta = $fechaHasta;
    }

    /**
     * @return mixed
     */
    public function getPrefijo()
    {
        return $this->prefijo;
    }

    /**
     * @param mixed $prefijo
     */
    public function setPrefijo($prefijo): void
    {
        $this->prefijo = $prefijo;
    }

    /**
     * @return mixed
     */
    public function getNumeroDesde()
    {
        return $this->numeroDesde;
    }

    /**
     * @param mixed $numeroDesde
     */
    public function setNumeroDesde($numeroDesde): void
    {
        $this->numeroDesde = $numeroDesde;
    }

    /**
     * @return mixed
     */
    public function getNumeroHasta()
    {
        return $this->numeroHasta;
    }

    /**
     * @param mixed $numeroHasta
     */
    public function setNumeroHasta($numeroHasta): void
    {
        $this->numeroHasta = $numeroHasta;
    }

    /**
     * @return mixed
     */
    public function getLlaveTecnica()
    {
        return $this->llaveTecnica;
    }

    /**
     * @param mixed $llaveTecnica
     */
    public function setLlaveTecnica($llaveTecnica): void
    {
        $this->llaveTecnica = $llaveTecnica;
    }

    /**
     * @return mixed
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @param mixed $pin
     */
    public function setPin($pin): void
    {
        $this->pin = $pin;
    }

    /**
     * @return mixed
     */
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    /**
     * @param mixed $ambiente
     */
    public function setAmbiente($ambiente): void
    {
        $this->ambiente = $ambiente;
    }

    /**
     * @return mixed
     */
    public function getSetPruebas()
    {
        return $this->setPruebas;
    }

    /**
     * @param mixed $setPruebas
     */
    public function setSetPruebas($setPruebas): void
    {
        $this->setPruebas = $setPruebas;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmpresaFk()
    {
        return $this->codigoEmpresaFk;
    }

    /**
     * @param mixed $codigoEmpresaFk
     */
    public function setCodigoEmpresaFk($codigoEmpresaFk): void
    {
        $this->codigoEmpresaFk = $codigoEmpresaFk;
    }

    /**
     * @return bool
     */
    public function isPrueba(): bool
    {
        return $this->prueba;
    }

    /**
     * @param bool $prueba
     */
    public function setPrueba(bool $prueba): void
    {
        $this->prueba = $prueba;
    }

    /**
     * @return mixed
     */
    public function getMovimientosResolucionFacturaRel()
    {
        return $this->movimientosResolucionFacturaRel;
    }

    /**
     * @param mixed $movimientosResolucionFacturaRel
     */
    public function setMovimientosResolucionFacturaRel($movimientosResolucionFacturaRel): void
    {
        $this->movimientosResolucionFacturaRel = $movimientosResolucionFacturaRel;
    }


}


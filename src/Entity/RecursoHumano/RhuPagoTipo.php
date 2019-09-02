<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPagoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoPagoTipoPk"},message="Ya existe el código del pago tipo")
 *
 */
class RhuPagoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoPagoTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_tipo_pk", type="string", length=10)
     */
    private $codigoPagoTipoPk;

    /**
     * @ORM\Column(name="codigo_cuenta_pagar_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCuentaPagarTipoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=10, nullable=true)
     */
    private $codigoCuentaFk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */         
    private $nombre;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden;

    /**
     * @ORM\Column(name="genera_tesoreria", type="boolean", options={"default":false})
     */
    private $generaTesoreria = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesCuentaPagarTipo", inversedBy="pagosTipoCuentaPagarTipoRel")
     * @ORM\JoinColumn(name="codigo_cuenta_pagar_tipo_fk",referencedColumnName="codigo_cuenta_pagar_tipo_pk")
     */
    protected $cuentaPagarTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacion", mappedBy="pagoTipoRel")
     */
    protected $programacionesPagoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="pagoTipoRel")
     */
    protected $pagosPagoTipoRel;

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
    public function getCodigoPagoTipoPk()
    {
        return $this->codigoPagoTipoPk;
    }

    /**
     * @param mixed $codigoPagoTipoPk
     */
    public function setCodigoPagoTipoPk($codigoPagoTipoPk): void
    {
        $this->codigoPagoTipoPk = $codigoPagoTipoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaPagarTipoFk()
    {
        return $this->codigoCuentaPagarTipoFk;
    }

    /**
     * @param mixed $codigoCuentaPagarTipoFk
     */
    public function setCodigoCuentaPagarTipoFk($codigoCuentaPagarTipoFk): void
    {
        $this->codigoCuentaPagarTipoFk = $codigoCuentaPagarTipoFk;
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
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     */
    public function setOrden($orden): void
    {
        $this->orden = $orden;
    }

    /**
     * @return mixed
     */
    public function getCuentaPagarTipoRel()
    {
        return $this->cuentaPagarTipoRel;
    }

    /**
     * @param mixed $cuentaPagarTipoRel
     */
    public function setCuentaPagarTipoRel($cuentaPagarTipoRel): void
    {
        $this->cuentaPagarTipoRel = $cuentaPagarTipoRel;
    }

    /**
     * @return mixed
     */
    public function getProgramacionesPagoTipoRel()
    {
        return $this->programacionesPagoTipoRel;
    }

    /**
     * @param mixed $programacionesPagoTipoRel
     */
    public function setProgramacionesPagoTipoRel($programacionesPagoTipoRel): void
    {
        $this->programacionesPagoTipoRel = $programacionesPagoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getPagosPagoTipoRel()
    {
        return $this->pagosPagoTipoRel;
    }

    /**
     * @param mixed $pagosPagoTipoRel
     */
    public function setPagosPagoTipoRel($pagosPagoTipoRel): void
    {
        $this->pagosPagoTipoRel = $pagosPagoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getGeneraTesoreria()
    {
        return $this->generaTesoreria;
    }

    /**
     * @param mixed $generaTesoreria
     */
    public function setGeneraTesoreria($generaTesoreria): void
    {
        $this->generaTesoreria = $generaTesoreria;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * @param mixed $codigoCuentaFk
     */
    public function setCodigoCuentaFk($codigoCuentaFk): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
    }



}
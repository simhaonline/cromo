<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAdicionalRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuIncapacidadTipo
{
    public $infoLog = [
        "primaryKey" => "codigoIncapacidadTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncapacidadTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="abreviatura", type="string", length=20, nullable=true)
     */
    private $abreviatura;

    /**
     * @ORM\Column(name="tipo", type="integer")
     */
    private $tipo = 0;

    /**
     * @ORM\Column(name="genera_pago", type="boolean")
     */
    private $generaPago = false;

    /**
     * @ORM\Column(name="genera_ibc", type="boolean")
     */
    private $generaIbc = false;

    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */
    private $codigoPagoConceptoFk;

    /**
     * @ORM\Column(name="codigo_pago_concepto_empresa_fk", type="integer", nullable=true)
     */
    private $codigoPagoConceptoEmpresaFk;

    /**
     * @ORM\Column(name="tipo_novedad_turno", type="string", length=5, nullable=true)
     */
    private $tipoNovedadTurno;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="incapacidadTipoRel")
     */
    protected $incapacidadesIncapacidadTipoRel;

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
    public function getCodigoIncapacidadTipoPk()
    {
        return $this->codigoIncapacidadTipoPk;
    }

    /**
     * @param mixed $codigoIncapacidadTipoPk
     */
    public function setCodigoIncapacidadTipoPk($codigoIncapacidadTipoPk): void
    {
        $this->codigoIncapacidadTipoPk = $codigoIncapacidadTipoPk;
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
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * @param mixed $abreviatura
     */
    public function setAbreviatura($abreviatura): void
    {
        $this->abreviatura = $abreviatura;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getGeneraPago()
    {
        return $this->generaPago;
    }

    /**
     * @param mixed $generaPago
     */
    public function setGeneraPago($generaPago): void
    {
        $this->generaPago = $generaPago;
    }

    /**
     * @return mixed
     */
    public function getGeneraIbc()
    {
        return $this->generaIbc;
    }

    /**
     * @param mixed $generaIbc
     */
    public function setGeneraIbc($generaIbc): void
    {
        $this->generaIbc = $generaIbc;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoConceptoFk()
    {
        return $this->codigoPagoConceptoFk;
    }

    /**
     * @param mixed $codigoPagoConceptoFk
     */
    public function setCodigoPagoConceptoFk($codigoPagoConceptoFk): void
    {
        $this->codigoPagoConceptoFk = $codigoPagoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPagoConceptoEmpresaFk()
    {
        return $this->codigoPagoConceptoEmpresaFk;
    }

    /**
     * @param mixed $codigoPagoConceptoEmpresaFk
     */
    public function setCodigoPagoConceptoEmpresaFk($codigoPagoConceptoEmpresaFk): void
    {
        $this->codigoPagoConceptoEmpresaFk = $codigoPagoConceptoEmpresaFk;
    }

    /**
     * @return mixed
     */
    public function getTipoNovedadTurno()
    {
        return $this->tipoNovedadTurno;
    }

    /**
     * @param mixed $tipoNovedadTurno
     */
    public function setTipoNovedadTurno($tipoNovedadTurno): void
    {
        $this->tipoNovedadTurno = $tipoNovedadTurno;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadesIncapacidadTipoRel()
    {
        return $this->incapacidadesIncapacidadTipoRel;
    }

    /**
     * @param mixed $incapacidadesIncapacidadTipoRel
     */
    public function setIncapacidadesIncapacidadTipoRel($incapacidadesIncapacidadTipoRel): void
    {
        $this->incapacidadesIncapacidadTipoRel = $incapacidadesIncapacidadTipoRel;
    }



}

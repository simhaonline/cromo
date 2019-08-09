<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuIncapacidadTipoRepository")
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
     * @ORM\Column(name="codigo_concepto_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="codigo_concepto_empresa_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoEmpresaFk;

    /**
     * @ORM\Column(name="tipo_novedad_turno", type="string", length=5, nullable=true)
     */
    private $tipoNovedadTurno;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="incapacidadTipoRel")
     */
    protected $incapacidadesIncapacidadTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="incapacidadesTiposConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */

    protected $conceptoRel;
    /**
     * @ORM\ManyToOne(targetEntity="RhuConcepto", inversedBy="incapacidadesTiposConceptoEmpresaRel")
     * @ORM\JoinColumn(name="codigo_concepto_empresa_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoEmpresaRel;

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

    /**
     * @return mixed
     */
    public function getCodigoConceptoFk()
    {
        return $this->codigoConceptoFk;
    }

    /**
     * @param mixed $codigoConceptoFk
     */
    public function setCodigoConceptoFk($codigoConceptoFk): void
    {
        $this->codigoConceptoFk = $codigoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoEmpresaFk()
    {
        return $this->codigoConceptoEmpresaFk;
    }

    /**
     * @param mixed $codigoConceptoEmpresaFk
     */
    public function setCodigoConceptoEmpresaFk($codigoConceptoEmpresaFk): void
    {
        $this->codigoConceptoEmpresaFk = $codigoConceptoEmpresaFk;
    }

    /**
     * @return mixed
     */
    public function getConceptoRel()
    {
        return $this->conceptoRel;
    }

    /**
     * @param mixed $conceptoRel
     */
    public function setConceptoRel($conceptoRel): void
    {
        $this->conceptoRel = $conceptoRel;
    }

    /**
     * @return mixed
     */
    public function getConceptoEmpresaRel()
    {
        return $this->conceptoEmpresaRel;
    }

    /**
     * @param mixed $conceptoEmpresaRel
     */
    public function setConceptoEmpresaRel($conceptoEmpresaRel): void
    {
        $this->conceptoEmpresaRel = $conceptoEmpresaRel;
    }



}

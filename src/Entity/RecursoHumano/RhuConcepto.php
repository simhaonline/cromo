<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConceptoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoPagoConceptoPk"},message="Ya existe el código del pago concepto")
 */
class RhuConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_concepto_pk", type="string", length=10)
     */
    private $codigoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="porcentaje", type="float", options={"default":0} , nullable=true)
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="genera_ingreso_base_prestacion" ,options={"default":false} ,type="boolean", nullable=true)
     */
    private $generaIngresoBasePrestacion = false;

    /**
     * @ORM\Column(name="genera_ingreso_base_cotizacion",options={"default":false} , type="boolean", nullable=true)
     */
    private $generaIngresoBaseCotizacion = false;

    /**
     * @ORM\Column(name="operacion", type="integer",options={"default":0} , nullable=true)
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="adicional",options={"default":false} , type="boolean", nullable=true)
     */
    private $adicional = false;

    /**
     * BON=Bonificacion, DES=Descuento, COM=Comision
     * @ORM\Column(name="adicional_tipo", type="string", length=3, nullable=true)
     */
    private $adicionalTipo = 1;

    /**
     * @ORM\Column(name="auxilio_transporte",options={"default":false} , type="boolean", nullable=true)
     */
    private $auxilioTransporte = false;

    /**
     * @ORM\Column(name="incapacidad",options={"default":false} , type="boolean", nullable=true)
     */
    private $incapacidad = false;

    /**
     * @ORM\Column(name="incapacidad_entidad",options={"default":false} , type="boolean", nullable=true)
     */
    private $incapacidadEntidad = false;

    /**
     * @ORM\Column(name="pension",options={"default":false} , type="boolean", nullable=true)
     */
    private $pension = false;

    /**
     * @ORM\Column(name="salud",options={"default":false} , type="boolean", nullable=true)
     */
    private $salud = false;

    /**
     * @ORM\Column(name="vacacion",options={"default":false} , type="boolean", nullable=true)
     */
    private $vacacion = false;

    /**
     * @ORM\Column(name="comision",options={"default":false} , type="boolean", nullable=true)
     */
    private $comision = false;

    /**
     * @ORM\Column(name="cesantia",options={"default":false} , type="boolean", nullable=true)
     */
    private $cesantia = false;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\Column(name="numero_dian", type="integer", nullable=true)
     */
    private $numeroDian;

    /**
     * @ORM\Column(name="recargo_nocturno",options={"default":false} ,type="boolean", nullable=true)
     */
    private $recargoNocturno = false;

    /**
     * @ORM\Column(name="fondo_solidaridad_pensional",options={"default":false} , type="boolean", nullable=true)
     */
    private $fondoSolidaridadPensional = false;

    /**
     * @ORM\OneToMany(targetEntity="RhuNovedadTipo", mappedBy="conceptoRel")
     */
    protected $novedadesConceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoConceptoPk()
    {
        return $this->codigoConceptoPk;
    }

    /**
     * @param mixed $codigoConceptoPk
     */
    public function setCodigoConceptoPk($codigoConceptoPk): void
    {
        $this->codigoConceptoPk = $codigoConceptoPk;
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
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje($porcentaje): void
    {
        $this->porcentaje = $porcentaje;
    }

    /**
     * @return mixed
     */
    public function getGeneraIngresoBasePrestacion()
    {
        return $this->generaIngresoBasePrestacion;
    }

    /**
     * @param mixed $generaIngresoBasePrestacion
     */
    public function setGeneraIngresoBasePrestacion($generaIngresoBasePrestacion): void
    {
        $this->generaIngresoBasePrestacion = $generaIngresoBasePrestacion;
    }

    /**
     * @return mixed
     */
    public function getGeneraIngresoBaseCotizacion()
    {
        return $this->generaIngresoBaseCotizacion;
    }

    /**
     * @param mixed $generaIngresoBaseCotizacion
     */
    public function setGeneraIngresoBaseCotizacion($generaIngresoBaseCotizacion): void
    {
        $this->generaIngresoBaseCotizacion = $generaIngresoBaseCotizacion;
    }

    /**
     * @return mixed
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * @param mixed $operacion
     */
    public function setOperacion($operacion): void
    {
        $this->operacion = $operacion;
    }

    /**
     * @return mixed
     */
    public function getAdicional()
    {
        return $this->adicional;
    }

    /**
     * @param mixed $adicional
     */
    public function setAdicional($adicional): void
    {
        $this->adicional = $adicional;
    }

    /**
     * @return mixed
     */
    public function getAdicionalTipo()
    {
        return $this->adicionalTipo;
    }

    /**
     * @param mixed $adicionalTipo
     */
    public function setAdicionalTipo($adicionalTipo): void
    {
        $this->adicionalTipo = $adicionalTipo;
    }

    /**
     * @return mixed
     */
    public function getAuxilioTransporte()
    {
        return $this->auxilioTransporte;
    }

    /**
     * @param mixed $auxilioTransporte
     */
    public function setAuxilioTransporte($auxilioTransporte): void
    {
        $this->auxilioTransporte = $auxilioTransporte;
    }

    /**
     * @return mixed
     */
    public function getIncapacidad()
    {
        return $this->incapacidad;
    }

    /**
     * @param mixed $incapacidad
     */
    public function setIncapacidad($incapacidad): void
    {
        $this->incapacidad = $incapacidad;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadEntidad()
    {
        return $this->incapacidadEntidad;
    }

    /**
     * @param mixed $incapacidadEntidad
     */
    public function setIncapacidadEntidad($incapacidadEntidad): void
    {
        $this->incapacidadEntidad = $incapacidadEntidad;
    }

    /**
     * @return mixed
     */
    public function getPension()
    {
        return $this->pension;
    }

    /**
     * @param mixed $pension
     */
    public function setPension($pension): void
    {
        $this->pension = $pension;
    }

    /**
     * @return mixed
     */
    public function getSalud()
    {
        return $this->salud;
    }

    /**
     * @param mixed $salud
     */
    public function setSalud($salud): void
    {
        $this->salud = $salud;
    }

    /**
     * @return mixed
     */
    public function getVacacion()
    {
        return $this->vacacion;
    }

    /**
     * @param mixed $vacacion
     */
    public function setVacacion($vacacion): void
    {
        $this->vacacion = $vacacion;
    }

    /**
     * @return mixed
     */
    public function getComision()
    {
        return $this->comision;
    }

    /**
     * @param mixed $comision
     */
    public function setComision($comision): void
    {
        $this->comision = $comision;
    }

    /**
     * @return mixed
     */
    public function getCesantia()
    {
        return $this->cesantia;
    }

    /**
     * @param mixed $cesantia
     */
    public function setCesantia($cesantia): void
    {
        $this->cesantia = $cesantia;
    }

    /**
     * @return mixed
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * @param mixed $codigoInterface
     */
    public function setCodigoInterface($codigoInterface): void
    {
        $this->codigoInterface = $codigoInterface;
    }

    /**
     * @return mixed
     */
    public function getNumeroDian()
    {
        return $this->numeroDian;
    }

    /**
     * @param mixed $numeroDian
     */
    public function setNumeroDian($numeroDian): void
    {
        $this->numeroDian = $numeroDian;
    }

    /**
     * @return mixed
     */
    public function getRecargoNocturno()
    {
        return $this->recargoNocturno;
    }

    /**
     * @param mixed $recargoNocturno
     */
    public function setRecargoNocturno($recargoNocturno): void
    {
        $this->recargoNocturno = $recargoNocturno;
    }

    /**
     * @return mixed
     */
    public function getFondoSolidaridadPensional()
    {
        return $this->fondoSolidaridadPensional;
    }

    /**
     * @param mixed $fondoSolidaridadPensional
     */
    public function setFondoSolidaridadPensional($fondoSolidaridadPensional): void
    {
        $this->fondoSolidaridadPensional = $fondoSolidaridadPensional;
    }

    /**
     * @return mixed
     */
    public function getNovedadesConceptoRel()
    {
        return $this->novedadesConceptoRel;
    }

    /**
     * @param mixed $novedadesConceptoRel
     */
    public function setNovedadesConceptoRel($novedadesConceptoRel): void
    {
        $this->novedadesConceptoRel = $novedadesConceptoRel;
    }
}
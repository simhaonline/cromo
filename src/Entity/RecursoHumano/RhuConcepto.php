<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConceptoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoConceptoPk"},message="Ya existe el código del pago concepto")
 */
class RhuConcepto
{
    public $infoLog = [
        "primaryKey" => "codigoConceptoPk",
        "todos"     => true,
    ];
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
     * @ORM\Column(name="genera_ingreso_base_prestacion_vacacion" ,options={"default":false} ,type="boolean", nullable=true)
     */
    private $generaIngresoBasePrestacionVacacion = false;

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
    private $adicionalTipo;

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
     * @ORM\Column(name="credito",options={"default":false} , type="boolean", nullable=true)
     */
    private $credito = false;

    /**
     * @ORM\Column(name="embargo",options={"default":false} , type="boolean", nullable=true)
     */
    private $embargo = false;

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
     * @ORM\Column(name="porcentaje_vacaciones", type="float", nullable=true)
     */
    private $porcentajeVacaciones = 0;

    /**
     * @ORM\Column(name="orden", type="integer", options={"default":0} , nullable=true)
     */
    private $orden = 0;

    /**
     * @ORM\OneToMany(targetEntity="RhuNovedadTipo", mappedBy="conceptoRel")
     */
    protected $novedadesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuCreditoTipo", mappedBy="conceptoRel")
     */
    protected $creditosTiposConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuEmbargoTipo", mappedBy="conceptoRel")
     */
    protected $embargosTiposConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAdicional", mappedBy="conceptoRel")
     */
    protected $adicionalesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoDetalle", mappedBy="conceptoRel")
     */
    protected $pagosDetallesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuConceptoHora", mappedBy="conceptoRel")
     */
    protected $conceptosHorasConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSalud", mappedBy="conceptoRel")
     */
    protected $saludesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuPension", mappedBy="conceptoRel")
     */
    protected $pensionesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuConfiguracion", mappedBy="conceptoAuxilioTransporteRel")
     */
    protected $configuracionConceptoAuxilioTransporteRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuConfiguracion", mappedBy="conceptoCesantiaRel")
     */
    protected $configuracionConceptoCesantiaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuConfiguracion", mappedBy="conceptoInteresCesantiaRel")
     */
    protected $configuracionConceptoInteresCesantiaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuConfiguracion", mappedBy="conceptoVacacionRel")
     */
    protected $configuracionConceptoVacacionRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuConfiguracion", mappedBy="conceptoFondoSolidaridadRel")
     */
    protected $configuracionConceptoFondoSolidaridadRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacionAdicional", mappedBy="conceptoRel")
     */
    protected $liquidacionesAdicionalesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidadTipo", mappedBy="conceptoRel")
     */
    protected $incapacidadesTiposConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidadTipo", mappedBy="conceptoEmpresaRel")
     */
    protected $incapacidadesTiposConceptoEmpresaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuVacacionAdicional", mappedBy="conceptoRel")
     */
    protected $vacacionesAdicionalesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuConceptoCuenta", mappedBy="conceptoRel")
     */
    protected $conceptosCuentasConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuLicenciaTipo", mappedBy="conceptoRel")
     */
    protected $conceptosLicenciasTiposRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuVacacionTipo", mappedBy="conceptoDisfrutadaRel")
     */
    protected $vacacionesTiposConceptoDisfrutadaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuVacacionTipo", mappedBy="conceptoDineroRel")
     */
    protected $vacacionesTiposConceptoDineroRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurPuestoAdicional", mappedBy="conceptoRel")
     */
    protected $puestoAdicionalConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuFacturaDetalle", mappedBy="conceptoRel")
     */
    protected $facturasDetallesConceptoRel;

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
    public function getGeneraIngresoBasePrestacionVacacion()
    {
        return $this->generaIngresoBasePrestacionVacacion;
    }

    /**
     * @param mixed $generaIngresoBasePrestacionVacacion
     */
    public function setGeneraIngresoBasePrestacionVacacion($generaIngresoBasePrestacionVacacion): void
    {
        $this->generaIngresoBasePrestacionVacacion = $generaIngresoBasePrestacionVacacion;
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
    public function getCredito()
    {
        return $this->credito;
    }

    /**
     * @param mixed $credito
     */
    public function setCredito($credito): void
    {
        $this->credito = $credito;
    }

    /**
     * @return mixed
     */
    public function getEmbargo()
    {
        return $this->embargo;
    }

    /**
     * @param mixed $embargo
     */
    public function setEmbargo($embargo): void
    {
        $this->embargo = $embargo;
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
    public function getPorcentajeVacaciones()
    {
        return $this->porcentajeVacaciones;
    }

    /**
     * @param mixed $porcentajeVacaciones
     */
    public function setPorcentajeVacaciones($porcentajeVacaciones): void
    {
        $this->porcentajeVacaciones = $porcentajeVacaciones;
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

    /**
     * @return mixed
     */
    public function getCreditosTiposConceptoRel()
    {
        return $this->creditosTiposConceptoRel;
    }

    /**
     * @param mixed $creditosTiposConceptoRel
     */
    public function setCreditosTiposConceptoRel($creditosTiposConceptoRel): void
    {
        $this->creditosTiposConceptoRel = $creditosTiposConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getEmbargosTiposConceptoRel()
    {
        return $this->embargosTiposConceptoRel;
    }

    /**
     * @param mixed $embargosTiposConceptoRel
     */
    public function setEmbargosTiposConceptoRel($embargosTiposConceptoRel): void
    {
        $this->embargosTiposConceptoRel = $embargosTiposConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getAdicionalesConceptoRel()
    {
        return $this->adicionalesConceptoRel;
    }

    /**
     * @param mixed $adicionalesConceptoRel
     */
    public function setAdicionalesConceptoRel($adicionalesConceptoRel): void
    {
        $this->adicionalesConceptoRel = $adicionalesConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getPagosDetallesConceptoRel()
    {
        return $this->pagosDetallesConceptoRel;
    }

    /**
     * @param mixed $pagosDetallesConceptoRel
     */
    public function setPagosDetallesConceptoRel($pagosDetallesConceptoRel): void
    {
        $this->pagosDetallesConceptoRel = $pagosDetallesConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getConceptosHorasConceptoRel()
    {
        return $this->conceptosHorasConceptoRel;
    }

    /**
     * @param mixed $conceptosHorasConceptoRel
     */
    public function setConceptosHorasConceptoRel($conceptosHorasConceptoRel): void
    {
        $this->conceptosHorasConceptoRel = $conceptosHorasConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getSaludesConceptoRel()
    {
        return $this->saludesConceptoRel;
    }

    /**
     * @param mixed $saludesConceptoRel
     */
    public function setSaludesConceptoRel($saludesConceptoRel): void
    {
        $this->saludesConceptoRel = $saludesConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getPensionesConceptoRel()
    {
        return $this->pensionesConceptoRel;
    }

    /**
     * @param mixed $pensionesConceptoRel
     */
    public function setPensionesConceptoRel($pensionesConceptoRel): void
    {
        $this->pensionesConceptoRel = $pensionesConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getConfiguracionConceptoAuxilioTransporteRel()
    {
        return $this->configuracionConceptoAuxilioTransporteRel;
    }

    /**
     * @param mixed $configuracionConceptoAuxilioTransporteRel
     */
    public function setConfiguracionConceptoAuxilioTransporteRel($configuracionConceptoAuxilioTransporteRel): void
    {
        $this->configuracionConceptoAuxilioTransporteRel = $configuracionConceptoAuxilioTransporteRel;
    }

    /**
     * @return mixed
     */
    public function getConfiguracionConceptoVacacionRel()
    {
        return $this->configuracionConceptoVacacionRel;
    }

    /**
     * @param mixed $configuracionConceptoVacacionRel
     */
    public function setConfiguracionConceptoVacacionRel($configuracionConceptoVacacionRel): void
    {
        $this->configuracionConceptoVacacionRel = $configuracionConceptoVacacionRel;
    }

    /**
     * @return mixed
     */
    public function getConfiguracionConceptoFondoSolidaridadRel()
    {
        return $this->configuracionConceptoFondoSolidaridadRel;
    }

    /**
     * @param mixed $configuracionConceptoFondoSolidaridadRel
     */
    public function setConfiguracionConceptoFondoSolidaridadRel($configuracionConceptoFondoSolidaridadRel): void
    {
        $this->configuracionConceptoFondoSolidaridadRel = $configuracionConceptoFondoSolidaridadRel;
    }

    /**
     * @return mixed
     */
    public function getLiquidacionesAdicionalesConceptoRel()
    {
        return $this->liquidacionesAdicionalesConceptoRel;
    }

    /**
     * @param mixed $liquidacionesAdicionalesConceptoRel
     */
    public function setLiquidacionesAdicionalesConceptoRel($liquidacionesAdicionalesConceptoRel): void
    {
        $this->liquidacionesAdicionalesConceptoRel = $liquidacionesAdicionalesConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadesTiposConceptoRel()
    {
        return $this->incapacidadesTiposConceptoRel;
    }

    /**
     * @param mixed $incapacidadesTiposConceptoRel
     */
    public function setIncapacidadesTiposConceptoRel($incapacidadesTiposConceptoRel): void
    {
        $this->incapacidadesTiposConceptoRel = $incapacidadesTiposConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadesTiposConceptoEmpresaRel()
    {
        return $this->incapacidadesTiposConceptoEmpresaRel;
    }

    /**
     * @param mixed $incapacidadesTiposConceptoEmpresaRel
     */
    public function setIncapacidadesTiposConceptoEmpresaRel($incapacidadesTiposConceptoEmpresaRel): void
    {
        $this->incapacidadesTiposConceptoEmpresaRel = $incapacidadesTiposConceptoEmpresaRel;
    }

    /**
     * @return mixed
     */
    public function getVacacionesAdicionalesConceptoRel()
    {
        return $this->vacacionesAdicionalesConceptoRel;
    }

    /**
     * @param mixed $vacacionesAdicionalesConceptoRel
     */
    public function setVacacionesAdicionalesConceptoRel($vacacionesAdicionalesConceptoRel): void
    {
        $this->vacacionesAdicionalesConceptoRel = $vacacionesAdicionalesConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getConceptosCuentasConceptoRel()
    {
        return $this->conceptosCuentasConceptoRel;
    }

    /**
     * @param mixed $conceptosCuentasConceptoRel
     */
    public function setConceptosCuentasConceptoRel($conceptosCuentasConceptoRel): void
    {
        $this->conceptosCuentasConceptoRel = $conceptosCuentasConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getConceptosLicenciasTiposRel()
    {
        return $this->conceptosLicenciasTiposRel;
    }

    /**
     * @param mixed $conceptosLicenciasTiposRel
     */
    public function setConceptosLicenciasTiposRel($conceptosLicenciasTiposRel): void
    {
        $this->conceptosLicenciasTiposRel = $conceptosLicenciasTiposRel;
    }

    /**
     * @return mixed
     */
    public function getVacacionesTiposConceptoDisfrutadaRel()
    {
        return $this->vacacionesTiposConceptoDisfrutadaRel;
    }

    /**
     * @param mixed $vacacionesTiposConceptoDisfrutadaRel
     */
    public function setVacacionesTiposConceptoDisfrutadaRel($vacacionesTiposConceptoDisfrutadaRel): void
    {
        $this->vacacionesTiposConceptoDisfrutadaRel = $vacacionesTiposConceptoDisfrutadaRel;
    }

    /**
     * @return mixed
     */
    public function getVacacionesTiposConceptoDineroRel()
    {
        return $this->vacacionesTiposConceptoDineroRel;
    }

    /**
     * @param mixed $vacacionesTiposConceptoDineroRel
     */
    public function setVacacionesTiposConceptoDineroRel($vacacionesTiposConceptoDineroRel): void
    {
        $this->vacacionesTiposConceptoDineroRel = $vacacionesTiposConceptoDineroRel;
    }

    /**
     * @return mixed
     */
    public function getPuestoAdicionalConceptoRel()
    {
        return $this->puestoAdicionalConceptoRel;
    }

    /**
     * @param mixed $puestoAdicionalConceptoRel
     */
    public function setPuestoAdicionalConceptoRel($puestoAdicionalConceptoRel): void
    {
        $this->puestoAdicionalConceptoRel = $puestoAdicionalConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasDetallesConceptoRel()
    {
        return $this->facturasDetallesConceptoRel;
    }

    /**
     * @param mixed $facturasDetallesConceptoRel
     */
    public function setFacturasDetallesConceptoRel($facturasDetallesConceptoRel): void
    {
        $this->facturasDetallesConceptoRel = $facturasDetallesConceptoRel;
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
     * @return mixed
     */
    public function getConfiguracionConceptoCesantiaRel()
    {
        return $this->configuracionConceptoCesantiaRel;
    }

    /**
     * @param mixed $configuracionConceptoCesantiaRel
     */
    public function setConfiguracionConceptoCesantiaRel($configuracionConceptoCesantiaRel): void
    {
        $this->configuracionConceptoCesantiaRel = $configuracionConceptoCesantiaRel;
    }

    /**
     * @return mixed
     */
    public function getConfiguracionConceptoInteresCesantiaRel()
    {
        return $this->configuracionConceptoInteresCesantiaRel;
    }

    /**
     * @param mixed $configuracionConceptoInteresCesantiaRel
     */
    public function setConfiguracionConceptoInteresCesantiaRel($configuracionConceptoInteresCesantiaRel): void
    {
        $this->configuracionConceptoInteresCesantiaRel = $configuracionConceptoInteresCesantiaRel;
    }


}
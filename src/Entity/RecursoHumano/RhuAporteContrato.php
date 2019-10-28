<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAporteContratoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAporteContrato
{
    public $infoLog = [
        "primaryKey" => "codigoAporteContratoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aporte_contrato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAporteContratoPk;

    /**
     * @ORM\Column(name="codigo_aporte_fk", type="integer")
     */
    private $codigoAporteFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_sucursal_fk", length=10, nullable=true)
     */
    private $codigoSucursalFk;

    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="salario_integral", type="string", length=1)
     */
    private $salarioIntegral = ' ';

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="ibc", type="float")
     */
    private $ibc = 0;

    /**
     * @ORM\Column(name="vrVacaciones", type="float")
     */
    private $vrVacaciones = 0;

    /**
     * @ORM\Column(name="dias_licencia", type="integer")
     */
    private $diasLicencia = 0;

    /**
     * @ORM\Column(name="dias_incapacidad", type="integer", nullable=true)
     */
    private $diasIncapacidad = 0;

    /**
     * @ORM\Column(name="dias_vacaciones", type="integer")
     */
    private $diasVacaciones = 0;

    /**
     * @ORM\Column(name="ibc_fondo_solidaridad", type="float")
     */
    private $IbcFondoSolidaridad = 0;

    /**
     * @ORM\Column(name="vr_pension", type="float", options={"default" : 0})
     */
    private $vrPension = 0;

    /**
     * @ORM\Column(name="vr_salud", type="float", options={"default" : 0})
     */
    private $vrSalud = 0;

    /**
     * @ORM\Column(name="vr_caja", type="float", options={"default" : 0})
     */
    private $vrCaja = 0;

    /**
     * @ORM\Column(name="vr_riesgos", type="float", options={"default" : 0})
     */
    private $vrRiesgos = 0;

    /**
     * @ORM\Column(name="vr_sena", type="float", options={"default" : 0})
     */
    private $vrSena = 0;

    /**
     * @ORM\Column(name="vr_icbf", type="float", options={"default" : 0})
     */
    private $vrIcbf = 0;

    /**
     * @ORM\Column(name="vr_pension_empleado", type="float", options={"default" : 0})
     */
    private $vrPensionEmpleado = 0;

    /**
     * @ORM\Column(name="vr_salud_empleado", type="float", options={"default" : 0})
     */
    private $vrSaludEmpleado = 0;

    /**
     * @ORM\Column(name="vr_pension_cotizacion", type="float", options={"default" : 0})
     */
    private $vrPensionCotizacion = 0;

    /**
     * @ORM\Column(name="vr_salud_cotizacion", type="float", options={"default" : 0})
     */
    private $vrSaludCotizacion = 0;

    /**
     * @ORM\Column(name="codigo_entidad_pension_fk", type="integer", nullable=true)
     */
    private $codigoEntidadPensionFk;

    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */
    private $codigoEntidadSaludFk;

    /**
     * @ORM\Column(name="codigo_entidad_riesgo_fk", type="integer", nullable=true)
     */
    private $codigoEntidadRiesgoFk;

    /**
     * @ORM\Column(name="codigo_entidad_caja_fk", type="integer", nullable=true)
     */
    private $codigoEntidadCajaFk;

    /**
     * @ORM\ManyToOne(targetEntity="RhuAporte", inversedBy="aportesContratosAporteRel")
     * @ORM\JoinColumn(name="codigo_aporte_fk",referencedColumnName="codigo_aporte_pk")
     */
    protected $aporteRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="aportesContratosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk",referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="aportesContratosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk",referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSucursal", inversedBy="aportesContratosSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk",referencedColumnName="codigo_sucursal_pk")
     */
    protected $sucursalRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="aportesContratosEntidadPensionRel")
     * @ORM\JoinColumn(name="codigo_entidad_pension_fk",referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadPensionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="aportesContratosEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk",referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadSaludRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="aportesContratosEntidadRiesgosRel")
     * @ORM\JoinColumn(name="codigo_entidad_riesgo_fk",referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadRiesgosRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="aportesContratosEntidadCajaRel")
     * @ORM\JoinColumn(name="codigo_entidad_caja_fk",referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadCajaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteSoporte", mappedBy="aporteContratoRel")
     */
    protected $aportesSoportesAporteContratoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteDetalle", mappedBy="aporteContratoRel")
     */
    protected $aportesDetallesAporteContratoRel;

    /**
     * @return mixed
     */
    public function getCodigoAporteContratoPk()
    {
        return $this->codigoAporteContratoPk;
    }

    /**
     * @param mixed $codigoAporteContratoPk
     */
    public function setCodigoAporteContratoPk($codigoAporteContratoPk): void
    {
        $this->codigoAporteContratoPk = $codigoAporteContratoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAporteFk()
    {
        return $this->codigoAporteFk;
    }

    /**
     * @param mixed $codigoAporteFk
     */
    public function setCodigoAporteFk($codigoAporteFk): void
    {
        $this->codigoAporteFk = $codigoAporteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * @param mixed $codigoContratoFk
     */
    public function setCodigoContratoFk($codigoContratoFk): void
    {
        $this->codigoContratoFk = $codigoContratoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * @param mixed $codigoEmpleadoFk
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk): void
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSucursalFk()
    {
        return $this->codigoSucursalFk;
    }

    /**
     * @param mixed $codigoSucursalFk
     */
    public function setCodigoSucursalFk($codigoSucursalFk): void
    {
        $this->codigoSucursalFk = $codigoSucursalFk;
    }

    /**
     * @return mixed
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
    }

    /**
     * @return mixed
     */
    public function getSalarioIntegral()
    {
        return $this->salarioIntegral;
    }

    /**
     * @param mixed $salarioIntegral
     */
    public function setSalarioIntegral($salarioIntegral): void
    {
        $this->salarioIntegral = $salarioIntegral;
    }

    /**
     * @return mixed
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * @param mixed $vrSalario
     */
    public function setVrSalario($vrSalario): void
    {
        $this->vrSalario = $vrSalario;
    }

    /**
     * @return mixed
     */
    public function getIbc()
    {
        return $this->ibc;
    }

    /**
     * @param mixed $ibc
     */
    public function setIbc($ibc): void
    {
        $this->ibc = $ibc;
    }

    /**
     * @return mixed
     */
    public function getVrVacaciones()
    {
        return $this->vrVacaciones;
    }

    /**
     * @param mixed $vrVacaciones
     */
    public function setVrVacaciones($vrVacaciones): void
    {
        $this->vrVacaciones = $vrVacaciones;
    }

    /**
     * @return mixed
     */
    public function getDiasLicencia()
    {
        return $this->diasLicencia;
    }

    /**
     * @param mixed $diasLicencia
     */
    public function setDiasLicencia($diasLicencia): void
    {
        $this->diasLicencia = $diasLicencia;
    }

    /**
     * @return mixed
     */
    public function getDiasIncapacidad()
    {
        return $this->diasIncapacidad;
    }

    /**
     * @param mixed $diasIncapacidad
     */
    public function setDiasIncapacidad($diasIncapacidad): void
    {
        $this->diasIncapacidad = $diasIncapacidad;
    }

    /**
     * @return mixed
     */
    public function getDiasVacaciones()
    {
        return $this->diasVacaciones;
    }

    /**
     * @param mixed $diasVacaciones
     */
    public function setDiasVacaciones($diasVacaciones): void
    {
        $this->diasVacaciones = $diasVacaciones;
    }

    /**
     * @return mixed
     */
    public function getIbcFondoSolidaridad()
    {
        return $this->IbcFondoSolidaridad;
    }

    /**
     * @param mixed $IbcFondoSolidaridad
     */
    public function setIbcFondoSolidaridad($IbcFondoSolidaridad): void
    {
        $this->IbcFondoSolidaridad = $IbcFondoSolidaridad;
    }

    /**
     * @return mixed
     */
    public function getVrPension()
    {
        return $this->vrPension;
    }

    /**
     * @param mixed $vrPension
     */
    public function setVrPension($vrPension): void
    {
        $this->vrPension = $vrPension;
    }

    /**
     * @return mixed
     */
    public function getVrSalud()
    {
        return $this->vrSalud;
    }

    /**
     * @param mixed $vrSalud
     */
    public function setVrSalud($vrSalud): void
    {
        $this->vrSalud = $vrSalud;
    }

    /**
     * @return mixed
     */
    public function getVrCaja()
    {
        return $this->vrCaja;
    }

    /**
     * @param mixed $vrCaja
     */
    public function setVrCaja($vrCaja): void
    {
        $this->vrCaja = $vrCaja;
    }

    /**
     * @return mixed
     */
    public function getVrRiesgos()
    {
        return $this->vrRiesgos;
    }

    /**
     * @param mixed $vrRiesgos
     */
    public function setVrRiesgos($vrRiesgos): void
    {
        $this->vrRiesgos = $vrRiesgos;
    }

    /**
     * @return mixed
     */
    public function getVrPensionEmpleado()
    {
        return $this->vrPensionEmpleado;
    }

    /**
     * @param mixed $vrPensionEmpleado
     */
    public function setVrPensionEmpleado($vrPensionEmpleado): void
    {
        $this->vrPensionEmpleado = $vrPensionEmpleado;
    }

    /**
     * @return mixed
     */
    public function getVrSaludEmpleado()
    {
        return $this->vrSaludEmpleado;
    }

    /**
     * @param mixed $vrSaludEmpleado
     */
    public function setVrSaludEmpleado($vrSaludEmpleado): void
    {
        $this->vrSaludEmpleado = $vrSaludEmpleado;
    }

    /**
     * @return mixed
     */
    public function getVrPensionCotizacion()
    {
        return $this->vrPensionCotizacion;
    }

    /**
     * @param mixed $vrPensionCotizacion
     */
    public function setVrPensionCotizacion($vrPensionCotizacion): void
    {
        $this->vrPensionCotizacion = $vrPensionCotizacion;
    }

    /**
     * @return mixed
     */
    public function getVrSaludCotizacion()
    {
        return $this->vrSaludCotizacion;
    }

    /**
     * @param mixed $vrSaludCotizacion
     */
    public function setVrSaludCotizacion($vrSaludCotizacion): void
    {
        $this->vrSaludCotizacion = $vrSaludCotizacion;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadPensionFk()
    {
        return $this->codigoEntidadPensionFk;
    }

    /**
     * @param mixed $codigoEntidadPensionFk
     */
    public function setCodigoEntidadPensionFk($codigoEntidadPensionFk): void
    {
        $this->codigoEntidadPensionFk = $codigoEntidadPensionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadSaludFk()
    {
        return $this->codigoEntidadSaludFk;
    }

    /**
     * @param mixed $codigoEntidadSaludFk
     */
    public function setCodigoEntidadSaludFk($codigoEntidadSaludFk): void
    {
        $this->codigoEntidadSaludFk = $codigoEntidadSaludFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadRiesgoFk()
    {
        return $this->codigoEntidadRiesgoFk;
    }

    /**
     * @param mixed $codigoEntidadRiesgoFk
     */
    public function setCodigoEntidadRiesgoFk($codigoEntidadRiesgoFk): void
    {
        $this->codigoEntidadRiesgoFk = $codigoEntidadRiesgoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadCajaFk()
    {
        return $this->codigoEntidadCajaFk;
    }

    /**
     * @param mixed $codigoEntidadCajaFk
     */
    public function setCodigoEntidadCajaFk($codigoEntidadCajaFk): void
    {
        $this->codigoEntidadCajaFk = $codigoEntidadCajaFk;
    }

    /**
     * @return mixed
     */
    public function getAporteRel()
    {
        return $this->aporteRel;
    }

    /**
     * @param mixed $aporteRel
     */
    public function setAporteRel($aporteRel): void
    {
        $this->aporteRel = $aporteRel;
    }

    /**
     * @return mixed
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * @param mixed $contratoRel
     */
    public function setContratoRel($contratoRel): void
    {
        $this->contratoRel = $contratoRel;
    }

    /**
     * @return mixed
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * @param mixed $empleadoRel
     */
    public function setEmpleadoRel($empleadoRel): void
    {
        $this->empleadoRel = $empleadoRel;
    }

    /**
     * @return mixed
     */
    public function getSucursalRel()
    {
        return $this->sucursalRel;
    }

    /**
     * @param mixed $sucursalRel
     */
    public function setSucursalRel($sucursalRel): void
    {
        $this->sucursalRel = $sucursalRel;
    }

    /**
     * @return mixed
     */
    public function getEntidadPensionRel()
    {
        return $this->entidadPensionRel;
    }

    /**
     * @param mixed $entidadPensionRel
     */
    public function setEntidadPensionRel($entidadPensionRel): void
    {
        $this->entidadPensionRel = $entidadPensionRel;
    }

    /**
     * @return mixed
     */
    public function getEntidadSaludRel()
    {
        return $this->entidadSaludRel;
    }

    /**
     * @param mixed $entidadSaludRel
     */
    public function setEntidadSaludRel($entidadSaludRel): void
    {
        $this->entidadSaludRel = $entidadSaludRel;
    }

    /**
     * @return mixed
     */
    public function getEntidadRiesgosRel()
    {
        return $this->entidadRiesgosRel;
    }

    /**
     * @param mixed $entidadRiesgosRel
     */
    public function setEntidadRiesgosRel($entidadRiesgosRel): void
    {
        $this->entidadRiesgosRel = $entidadRiesgosRel;
    }

    /**
     * @return mixed
     */
    public function getEntidadCajaRel()
    {
        return $this->entidadCajaRel;
    }

    /**
     * @param mixed $entidadCajaRel
     */
    public function setEntidadCajaRel($entidadCajaRel): void
    {
        $this->entidadCajaRel = $entidadCajaRel;
    }

    /**
     * @return mixed
     */
    public function getAportesSoportesAporteContratoRel()
    {
        return $this->aportesSoportesAporteContratoRel;
    }

    /**
     * @param mixed $aportesSoportesAporteContratoRel
     */
    public function setAportesSoportesAporteContratoRel($aportesSoportesAporteContratoRel): void
    {
        $this->aportesSoportesAporteContratoRel = $aportesSoportesAporteContratoRel;
    }

    /**
     * @return mixed
     */
    public function getAportesDetallesAporteContratoRel()
    {
        return $this->aportesDetallesAporteContratoRel;
    }

    /**
     * @param mixed $aportesDetallesAporteContratoRel
     */
    public function setAportesDetallesAporteContratoRel($aportesDetallesAporteContratoRel): void
    {
        $this->aportesDetallesAporteContratoRel = $aportesDetallesAporteContratoRel;
    }

    /**
     * @return mixed
     */
    public function getVrSena()
    {
        return $this->vrSena;
    }

    /**
     * @param mixed $vrSena
     */
    public function setVrSena($vrSena): void
    {
        $this->vrSena = $vrSena;
    }

    /**
     * @return mixed
     */
    public function getVrIcbf()
    {
        return $this->vrIcbf;
    }

    /**
     * @param mixed $vrIcbf
     */
    public function setVrIcbf($vrIcbf): void
    {
        $this->vrIcbf = $vrIcbf;
    }



}

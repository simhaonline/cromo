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
     * @ORM\Column(name="codigo_entidad_pension_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadPensionPertenece;

    /**
     * @ORM\Column(name="codigo_entidad_salud_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadSaludPertenece;

    /**
     * @ORM\Column(name="codigo_entidad_caja_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadCajaPertenece;

    /**
     * @ORM\Column(name="ibc_fondo_solidaridad", type="float")
     */
    private $IbcFondoSolidaridad = 0;

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
     * @ORM\OneToMany(targetEntity="RhuAporteSoporte", mappedBy="aporteContratoRel")
     */
    protected $aportesSoportesAporteContratoRel;

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
    public function getCodigoEntidadPensionPertenece()
    {
        return $this->codigoEntidadPensionPertenece;
    }

    /**
     * @param mixed $codigoEntidadPensionPertenece
     */
    public function setCodigoEntidadPensionPertenece($codigoEntidadPensionPertenece): void
    {
        $this->codigoEntidadPensionPertenece = $codigoEntidadPensionPertenece;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadSaludPertenece()
    {
        return $this->codigoEntidadSaludPertenece;
    }

    /**
     * @param mixed $codigoEntidadSaludPertenece
     */
    public function setCodigoEntidadSaludPertenece($codigoEntidadSaludPertenece): void
    {
        $this->codigoEntidadSaludPertenece = $codigoEntidadSaludPertenece;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadCajaPertenece()
    {
        return $this->codigoEntidadCajaPertenece;
    }

    /**
     * @param mixed $codigoEntidadCajaPertenece
     */
    public function setCodigoEntidadCajaPertenece($codigoEntidadCajaPertenece): void
    {
        $this->codigoEntidadCajaPertenece = $codigoEntidadCajaPertenece;
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



}

<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuTrasladoPensionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuTrasladoPension
{
    public $infoLog = [
        "primaryKey" => "codigoTrasladoPensionPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_traslado_pension_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTrasladoPensionPk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_entidad_pension_anterior_fk", type="integer")
     */
    private $codigoEntidadPensionAnteriorFk;

    /**
     * @ORM\Column(name="codigo_entidad_pension_nueva_fk", type="integer")
     */
    private $codigoEntidadPensionNuevaFk;

    /**
     * @ORM\Column(name="tipo", type="integer", nullable=true)
     */
    private $tipo;

    /**
     * @ORM\Column(name="estado_afiliado", type="boolean")
     */
    private $estadoAfiliado = false;

    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\Column(name="fecha_fosyga", type="date", nullable=true)
     */
    private $fechaFosyga;

    /**
     * @ORM\Column(name="fecha_cambio_afiliacion", type="date", nullable=true)
     */
    private $fechaCambioAfiliacion;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="trasladosPensionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="trasladosPensionesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="trasladosPensionesEntidadPensionAnteriorRel")
     * @ORM\JoinColumn(name="codigo_entidad_pension_anterior_fk", referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadPensionAnteriorRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="trasladosPensionesEntidadPensionNuevaRel")
     * @ORM\JoinColumn(name="codigo_entidad_pension_nueva_fk", referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadPensionNuevaRel;

    /**
     * @return mixed
     */
    public function getCodigoTrasladoPensionPk()
    {
        return $this->codigoTrasladoPensionPk;
    }

    /**
     * @param mixed $codigoTrasladoPensionPk
     */
    public function setCodigoTrasladoPensionPk($codigoTrasladoPensionPk): void
    {
        $this->codigoTrasladoPensionPk = $codigoTrasladoPensionPk;
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
    public function getCodigoEntidadPensionAnteriorFk()
    {
        return $this->codigoEntidadPensionAnteriorFk;
    }

    /**
     * @param mixed $codigoEntidadPensionAnteriorFk
     */
    public function setCodigoEntidadPensionAnteriorFk($codigoEntidadPensionAnteriorFk): void
    {
        $this->codigoEntidadPensionAnteriorFk = $codigoEntidadPensionAnteriorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadPensionNuevaFk()
    {
        return $this->codigoEntidadPensionNuevaFk;
    }

    /**
     * @param mixed $codigoEntidadPensionNuevaFk
     */
    public function setCodigoEntidadPensionNuevaFk($codigoEntidadPensionNuevaFk): void
    {
        $this->codigoEntidadPensionNuevaFk = $codigoEntidadPensionNuevaFk;
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
    public function getEstadoAfiliado()
    {
        return $this->estadoAfiliado;
    }

    /**
     * @param mixed $estadoAfiliado
     */
    public function setEstadoAfiliado($estadoAfiliado): void
    {
        $this->estadoAfiliado = $estadoAfiliado;
    }

    /**
     * @return mixed
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param mixed $detalle
     */
    public function setDetalle($detalle): void
    {
        $this->detalle = $detalle;
    }

    /**
     * @return mixed
     */
    public function getFechaFosyga()
    {
        return $this->fechaFosyga;
    }

    /**
     * @param mixed $fechaFosyga
     */
    public function setFechaFosyga($fechaFosyga): void
    {
        $this->fechaFosyga = $fechaFosyga;
    }

    /**
     * @return mixed
     */
    public function getFechaCambioAfiliacion()
    {
        return $this->fechaCambioAfiliacion;
    }

    /**
     * @param mixed $fechaCambioAfiliacion
     */
    public function setFechaCambioAfiliacion($fechaCambioAfiliacion): void
    {
        $this->fechaCambioAfiliacion = $fechaCambioAfiliacion;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
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
    public function getEntidadPensionAnteriorRel()
    {
        return $this->entidadPensionAnteriorRel;
    }

    /**
     * @param mixed $entidadPensionAnteriorRel
     */
    public function setEntidadPensionAnteriorRel($entidadPensionAnteriorRel): void
    {
        $this->entidadPensionAnteriorRel = $entidadPensionAnteriorRel;
    }

    /**
     * @return mixed
     */
    public function getEntidadPensionNuevaRel()
    {
        return $this->entidadPensionNuevaRel;
    }

    /**
     * @param mixed $entidadPensionNuevaRel
     */
    public function setEntidadPensionNuevaRel($entidadPensionNuevaRel): void
    {
        $this->entidadPensionNuevaRel = $entidadPensionNuevaRel;
    }



}

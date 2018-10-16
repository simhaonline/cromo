<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCierreSeleccionMotivoRepository")
 */
class RhuEmpleadoTipo
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_tipo_pk", type="string", length=10)
     */
    private $codigoEmpleadoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;                      
    
    /**
     * @ORM\Column(name="operativo", type="boolean")
     */    
    private $operativo = false;
    
    /**
     * @ORM\Column(name="interfaz", type="string", length=10, nullable=true)
     */    
    private $interfaz;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="empleadoTipoRel")
     */
    protected $empleadosEmpleadoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoEmpleadoTipoPk()
    {
        return $this->codigoEmpleadoTipoPk;
    }

    /**
     * @param mixed $codigoEmpleadoTipoPk
     */
    public function setCodigoEmpleadoTipoPk($codigoEmpleadoTipoPk): void
    {
        $this->codigoEmpleadoTipoPk = $codigoEmpleadoTipoPk;
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
    public function getOperativo()
    {
        return $this->operativo;
    }

    /**
     * @param mixed $operativo
     */
    public function setOperativo($operativo): void
    {
        $this->operativo = $operativo;
    }

    /**
     * @return mixed
     */
    public function getInterfaz()
    {
        return $this->interfaz;
    }

    /**
     * @param mixed $interfaz
     */
    public function setInterfaz($interfaz): void
    {
        $this->interfaz = $interfaz;
    }

    /**
     * @return mixed
     */
    public function getEmpleadosEmpleadoTipoRel()
    {
        return $this->empleadosEmpleadoTipoRel;
    }

    /**
     * @param mixed $empleadosEmpleadoTipoRel
     */
    public function setEmpleadosEmpleadoTipoRel($empleadosEmpleadoTipoRel): void
    {
        $this->empleadosEmpleadoTipoRel = $empleadosEmpleadoTipoRel;
    }
}

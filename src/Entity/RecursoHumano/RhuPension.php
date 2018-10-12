<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPensionRepository")
 */
class RhuPension
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pension_pk", type="string", length=10)
     */
    private $codigoPensionPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      

    /**
     * @ORM\Column(name="porcentaje_empleado", type="float")
     */    
    private $porcentajeEmpleado = 0;        
    
    /**
     * @ORM\Column(name="porcentaje_empleador", type="float")
     */    
    private $porcentajeEmpleador = 0;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="pensionRel")
     */
    protected $contratosPensionRel;

    /**
     * @return mixed
     */
    public function getCodigoPensionPk()
    {
        return $this->codigoPensionPk;
    }

    /**
     * @param mixed $codigoPensionPk
     */
    public function setCodigoPensionPk($codigoPensionPk): void
    {
        $this->codigoPensionPk = $codigoPensionPk;
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
    public function getPorcentajeEmpleado()
    {
        return $this->porcentajeEmpleado;
    }

    /**
     * @param mixed $porcentajeEmpleado
     */
    public function setPorcentajeEmpleado($porcentajeEmpleado): void
    {
        $this->porcentajeEmpleado = $porcentajeEmpleado;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeEmpleador()
    {
        return $this->porcentajeEmpleador;
    }

    /**
     * @param mixed $porcentajeEmpleador
     */
    public function setPorcentajeEmpleador($porcentajeEmpleador): void
    {
        $this->porcentajeEmpleador = $porcentajeEmpleador;
    }

    /**
     * @return mixed
     */
    public function getContratosPensionRel()
    {
        return $this->contratosPensionRel;
    }

    /**
     * @param mixed $contratosPensionRel
     */
    public function setContratosPensionRel($contratosPensionRel): void
    {
        $this->contratosPensionRel = $contratosPensionRel;
    }
}


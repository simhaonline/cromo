<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuDepartamentoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoDepartamentoPk"},message="Ya existe el código")
 */
class RhuDepartamento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_departamento_pk", type="string", length=10)
     */        
    private $codigoDepartamentoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="departamentoRel")
     */
    protected $empleadosDepartamentoRel;

    /**
     * @return mixed
     */
    public function getCodigoDepartamentoPk()
    {
        return $this->codigoDepartamentoPk;
    }

    /**
     * @param mixed $codigoDepartamentoPk
     */
    public function setCodigoDepartamentoPk($codigoDepartamentoPk): void
    {
        $this->codigoDepartamentoPk = $codigoDepartamentoPk;
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
    public function getEmpleadosDepartamentoRel()
    {
        return $this->empleadosDepartamentoRel;
    }

    /**
     * @param mixed $empleadosDepartamentoRel
     */
    public function setEmpleadosDepartamentoRel($empleadosDepartamentoRel): void
    {
        $this->empleadosDepartamentoRel = $empleadosDepartamentoRel;
    }



}

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
}


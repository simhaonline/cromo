<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuEmpleadoRepository")
 */
class RhuEmpleado
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoEmpleadoPk;

    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer", nullable=true)
     */
    private $codigoClasificacionRiesgoFk;

    /**
     * @ORM\Column(name="codigo_identificacion_tipo_fk", type="integer", nullable=true)
     */
    private $codigoIdentificacionTipoFk;

}

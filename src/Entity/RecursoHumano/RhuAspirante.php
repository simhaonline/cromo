<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\RhuAspiranteRepository")
 */
class RhuAspirante
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aspirante_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoAspirantePk;

    /**
     * @ORM\Column(name="codigo_identificacion_tipo_fk", type="integer", nullable=true)
     */
    private $codigoIdentificacionTipoFk;

    /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    private $codigoEstadoCivilFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_ciudad_nacimiento_fk", type="integer", nullable=true)
     */
    private $codigoCiudadNacimientoFk;

    /**
     * @ORM\Column(name="codigo_ciudad_expedicion_fk", type="integer", nullable=true)
     */
    private $codigoCiudadExpedicionFk;


}

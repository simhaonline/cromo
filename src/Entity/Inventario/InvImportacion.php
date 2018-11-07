<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvImportacionRepository")
 */
class InvImportacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_importacion_pk",type="integer")
     */
    private $codigoImportacionPk;

    /**
     * @ORM\Column(name="codigo_tercero_fk" , type="integer")
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="numero_importacion" , type="string" , nullable=true)
     */
    private $numeroImportacion;

    /**
     * @ORM\Column(name="fecha_importacion" , type="date", nullable=true)
     */
    private $fechaImportacion;

    /**
     * @ORM\Column(name="fecha_llegada" ,type="date" , nullable=true)
     */
    private $fechaLlegada;

    /**
     * @ORM\Column(name="comentarios" ,type="string")
     */
    private $comentarios;
}

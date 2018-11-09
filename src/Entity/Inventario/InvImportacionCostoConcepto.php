<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvImportacionCostoConceptoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoImportacionCostoConceptoPk"},message="Ya existe el código de la bodega")
 */
class InvImportacionCostoConcepto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_importacion_costo_concepto_pk" , type="string", length=10)
     */
    private $codigoImportacionCostoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre = 0;
}

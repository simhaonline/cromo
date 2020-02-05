<?php

namespace App\Entity\Seguridad;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Seguridad\SegGrupoProcesoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class SegGrupoProceso
{
    public $infoLog = [
        "primaryKey" => "codigoGrupoProcesoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_grupo_proceso_pk")
     */
    private $codigoGrupoProcesoPk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="integer")
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="codigo_proceso_fk", type="string", length=10)
     */
    private $codigoProcesoFk;


}

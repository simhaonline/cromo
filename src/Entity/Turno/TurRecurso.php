<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurRecursoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurRecurso
{
    public $infoLog = [
        "primaryKey" => "codigoRecursoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recurso_pk", type="integer")
     */
    private $codigoRecursoPk;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=120, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="comentario", type="string", length=200, nullable=true)
     */
    private $comentario;




}


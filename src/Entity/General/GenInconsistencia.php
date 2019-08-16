<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenInconsistenciaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenInconsistencia
{
    public $infoLog = [
        "primaryKey" => "codigoInconsistenciaPk",
        "todos"     => false,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_inconsistencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoInconsistenciaPk;

    /**
     * @ORM\Column(name="codigo_modelo_fk", type="string", length=80)
     */
    private $codigoModeloFk;

    /**
     * @ORM\Column(name="codigo", type="integer")
     */
    private $codigo;

    /**
     * @ORM\Column(name="descripcion", type="string", length=200, nullable=true)
     */
    private $descripcion;

    /**
     * @return mixed
     */
    public function getCodigoInconsistenciaPk()
    {
        return $this->codigoInconsistenciaPk;
    }

    /**
     * @param mixed $codigoInconsistenciaPk
     */
    public function setCodigoInconsistenciaPk($codigoInconsistenciaPk): void
    {
        $this->codigoInconsistenciaPk = $codigoInconsistenciaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoModeloFk()
    {
        return $this->codigoModeloFk;
    }

    /**
     * @param mixed $codigoModeloFk
     */
    public function setCodigoModeloFk($codigoModeloFk): void
    {
        $this->codigoModeloFk = $codigoModeloFk;
    }

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo): void
    {
        $this->codigo = $codigo;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }



}

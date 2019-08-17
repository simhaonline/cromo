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
     * @ORM\Column(name="codigo_modelo", type="integer")
     */
    private $codigoModelo;

    /**
     * @ORM\Column(name="codigo_referencia", type="string", length=100, nullable=true)
     */
    private $codigoReferencia;

    /**
     * @ORM\Column(name="referencia", type="string", length=100, nullable=true)
     */
    private $referencia;

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
    public function getCodigoModelo()
    {
        return $this->codigoModelo;
    }

    /**
     * @param mixed $codigoModelo
     */
    public function setCodigoModelo($codigoModelo): void
    {
        $this->codigoModelo = $codigoModelo;
    }

    /**
     * @return mixed
     */
    public function getCodigoReferencia()
    {
        return $this->codigoReferencia;
    }

    /**
     * @param mixed $codigoReferencia
     */
    public function setCodigoReferencia($codigoReferencia): void
    {
        $this->codigoReferencia = $codigoReferencia;
    }

    /**
     * @return mixed
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * @param mixed $referencia
     */
    public function setReferencia($referencia): void
    {
        $this->referencia = $referencia;
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

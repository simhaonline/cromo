<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEstudioTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuEstudioTipo
{
    public $infoLog = [
        "primaryKey" => "codigoEstudioTipoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_estudio_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEstudioTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\Column(name="validar_vencimiento", type="boolean",options={"default" : false}, nullable=true)
     */
    private $validarVencimiento = false;

    /**
     * @ORM\OneToMany(targetEntity="RhuEstudio", mappedBy="estudioTipoRel")
     */
    protected $estudiosEstudioTipoRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoEstudioTipoPk()
    {
        return $this->codigoEstudioTipoPk;
    }

    /**
     * @param mixed $codigoEstudioTipoPk
     */
    public function setCodigoEstudioTipoPk($codigoEstudioTipoPk): void
    {
        $this->codigoEstudioTipoPk = $codigoEstudioTipoPk;
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
    public function getValidarVencimiento()
    {
        return $this->validarVencimiento;
    }

    /**
     * @param mixed $validarVencimiento
     */
    public function setValidarVencimiento($validarVencimiento): void
    {
        $this->validarVencimiento = $validarVencimiento;
    }

    /**
     * @return mixed
     */
    public function getEstudiosEstudioTipoRel()
    {
        return $this->estudiosEstudioTipoRel;
    }

    /**
     * @param mixed $estudiosEstudioTipoRel
     */
    public function setEstudiosEstudioTipoRel($estudiosEstudioTipoRel): void
    {
        $this->estudiosEstudioTipoRel = $estudiosEstudioTipoRel;
    }



}

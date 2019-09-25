<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPruebaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuPruebaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoPruebaTipoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_prueba_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPruebaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuPrueba", mappedBy="pruebaTipoRel")
     */
    protected $pruebasPruebaTipoRel;

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
    public function getCodigoPruebaTipoPk()
    {
        return $this->codigoPruebaTipoPk;
    }

    /**
     * @param mixed $codigoPruebaTipoPk
     */
    public function setCodigoPruebaTipoPk($codigoPruebaTipoPk): void
    {
        $this->codigoPruebaTipoPk = $codigoPruebaTipoPk;
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
    public function getPruebasPruebaTipoRel()
    {
        return $this->pruebasPruebaTipoRel;
    }

    /**
     * @param mixed $pruebasPruebaTipoRel
     */
    public function setPruebasPruebaTipoRel($pruebasPruebaTipoRel): void
    {
        $this->pruebasPruebaTipoRel = $pruebasPruebaTipoRel;
    }



}

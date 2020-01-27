<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSeleccionPruebaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSeleccionPrueba
{
    public $infoLog = [
        "primaryKey" => "codigoSeleccionPruebaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_prueba_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionPruebaPk;

    /**
     * @ORM\Column(name="codigo_seleccion_fk", type="integer")
     */
    private $codigoSeleccionFk;

    /**
     * @ORM\Column(name="codigo_seleccion_prueba_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoSeleccionPruebaTipoFk;

    /**
     * @ORM\Column(name="fecha" , type="datetime" , nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="resultado", type="string", length=50, nullable=true)
     */
    private $resultado;

    /**
     * @ORM\Column(name="resultado_cuantitativo", type="integer", nullable=true)
     */
    private $resultadoCuantitativo;

    /**
     * @ORM\Column(name="nombre_quien_hace_prueba", type="string", length=100, nullable=true)
     */
    private $nombreQuienHacePrueba;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccion", inversedBy="seleccionesPruebasSeleccionRel")
     * @ORM\JoinColumn(name="codigo_seleccion_fk", referencedColumnName="codigo_seleccion_pk")
     */
    protected $seleccionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionPruebaTipo", inversedBy="seleccionesPruebasSelecionPruebaTipoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_prueba_tipo_fk", referencedColumnName="codigo_seleccion_prueba_tipo_pk")
     */
    protected $seleccionPruebaTipoRel;

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
    public function getCodigoSeleccionPruebaPk()
    {
        return $this->codigoSeleccionPruebaPk;
    }

    /**
     * @param mixed $codigoSeleccionPruebaPk
     */
    public function setCodigoSeleccionPruebaPk($codigoSeleccionPruebaPk): void
    {
        $this->codigoSeleccionPruebaPk = $codigoSeleccionPruebaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSeleccionFk()
    {
        return $this->codigoSeleccionFk;
    }

    /**
     * @param mixed $codigoSeleccionFk
     */
    public function setCodigoSeleccionFk($codigoSeleccionFk): void
    {
        $this->codigoSeleccionFk = $codigoSeleccionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSeleccionPruebaTipoFk()
    {
        return $this->codigoSeleccionPruebaTipoFk;
    }

    /**
     * @param mixed $codigoSeleccionPruebaTipoFk
     */
    public function setCodigoSeleccionPruebaTipoFk($codigoSeleccionPruebaTipoFk): void
    {
        $this->codigoSeleccionPruebaTipoFk = $codigoSeleccionPruebaTipoFk;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * @param mixed $resultado
     */
    public function setResultado($resultado): void
    {
        $this->resultado = $resultado;
    }

    /**
     * @return mixed
     */
    public function getResultadoCuantitativo()
    {
        return $this->resultadoCuantitativo;
    }

    /**
     * @param mixed $resultadoCuantitativo
     */
    public function setResultadoCuantitativo($resultadoCuantitativo): void
    {
        $this->resultadoCuantitativo = $resultadoCuantitativo;
    }

    /**
     * @return mixed
     */
    public function getNombreQuienHacePrueba()
    {
        return $this->nombreQuienHacePrueba;
    }

    /**
     * @param mixed $nombreQuienHacePrueba
     */
    public function setNombreQuienHacePrueba($nombreQuienHacePrueba): void
    {
        $this->nombreQuienHacePrueba = $nombreQuienHacePrueba;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
    }

    /**
     * @return mixed
     */
    public function getSeleccionRel()
    {
        return $this->seleccionRel;
    }

    /**
     * @param mixed $seleccionRel
     */
    public function setSeleccionRel($seleccionRel): void
    {
        $this->seleccionRel = $seleccionRel;
    }

    /**
     * @return mixed
     */
    public function getSeleccionPruebaTipoRel()
    {
        return $this->seleccionPruebaTipoRel;
    }

    /**
     * @param mixed $seleccionPruebaTipoRel
     */
    public function setSeleccionPruebaTipoRel($seleccionPruebaTipoRel): void
    {
        $this->seleccionPruebaTipoRel = $seleccionPruebaTipoRel;
    }


}

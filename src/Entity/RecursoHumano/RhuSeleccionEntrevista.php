<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSeleccionEntrevistaRepository")
 */
class RhuSeleccionEntrevista
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_entrevista_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionEntrevistaPk;

    /**
     * @ORM\Column(name="codigo_seleccion_fk", type="integer")
     */
    private $codigoSeleccionFk;

    /**
     * @ORM\Column(name="codigo_seleccion_entrevista_tipo_fk", type="integer")
     */
    private $codigoSeleccionEntrevistaTipoFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
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
     * @ORM\Column(name="nombre_quien_entrevista", type="string", length=100, nullable=true)
     */
    private $nombreQuienEntrevista;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccion", inversedBy="seleccionesEntrevistasSeleccionRel")
     * @ORM\JoinColumn(name="codigo_seleccion_fk", referencedColumnName="codigo_seleccion_pk")
     */
    protected $seleccionRel;


    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionEntrevistaTipo", inversedBy="seleccionesEntrevistasSelecionEntrevistaTipoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_entrevista_tipo_fk", referencedColumnName="codigo_seleccion_entrevista_tipo_pk")
     */
    protected $seleccionEntrevistaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoSeleccionEntrevistaPk()
    {
        return $this->codigoSeleccionEntrevistaPk;
    }

    /**
     * @param mixed $codigoSeleccionEntrevistaPk
     */
    public function setCodigoSeleccionEntrevistaPk($codigoSeleccionEntrevistaPk): void
    {
        $this->codigoSeleccionEntrevistaPk = $codigoSeleccionEntrevistaPk;
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
    public function getCodigoSeleccionEntrevistaTipoFk()
    {
        return $this->codigoSeleccionEntrevistaTipoFk;
    }

    /**
     * @param mixed $codigoSeleccionEntrevistaTipoFk
     */
    public function setCodigoSeleccionEntrevistaTipoFk($codigoSeleccionEntrevistaTipoFk): void
    {
        $this->codigoSeleccionEntrevistaTipoFk = $codigoSeleccionEntrevistaTipoFk;
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
    public function getNombreQuienEntrevista()
    {
        return $this->nombreQuienEntrevista;
    }

    /**
     * @param mixed $nombreQuienEntrevista
     */
    public function setNombreQuienEntrevista($nombreQuienEntrevista): void
    {
        $this->nombreQuienEntrevista = $nombreQuienEntrevista;
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
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * @param mixed $codigoUsuario
     */
    public function setCodigoUsuario($codigoUsuario): void
    {
        $this->codigoUsuario = $codigoUsuario;
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
    public function getSeleccionEntrevistaTipoRel()
    {
        return $this->seleccionEntrevistaTipoRel;
    }

    /**
     * @param mixed $seleccionEntrevistaTipoRel
     */
    public function setSeleccionEntrevistaTipoRel($seleccionEntrevistaTipoRel): void
    {
        $this->seleccionEntrevistaTipoRel = $seleccionEntrevistaTipoRel;
    }


}
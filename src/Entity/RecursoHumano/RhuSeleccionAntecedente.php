<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSeleccionAntecedenteRepository")
 */
class RhuSeleccionAntecedente
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_antecedente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionAntecedentePk;

    /**
     * @ORM\Column(name="codigo_seleccion_fk", type="integer", nullable=true)
     */
    private $codigoSeleccionFk;


    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="nombre_quien_suministra", type="string", length=100, nullable=true)
     */
    private $nombreQuienSuministra;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="estado_verificado", type="boolean", options={"default":false}, nullable=true)
     */
    private $estadoVerificado = false;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccion", inversedBy="seleccionesAntecedentesSeleccionRel")
     * @ORM\JoinColumn(name="codigo_seleccion_fk", referencedColumnName="codigo_seleccion_pk")
     */
    protected $seleccionRel;

    /**
     * @return mixed
     */
    public function getCodigoSeleccionAntecedentePk()
    {
        return $this->codigoSeleccionAntecedentePk;
    }

    /**
     * @param mixed $codigoSeleccionAntecedentePk
     */
    public function setCodigoSeleccionAntecedentePk($codigoSeleccionAntecedentePk): void
    {
        $this->codigoSeleccionAntecedentePk = $codigoSeleccionAntecedentePk;
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
    public function getNombreQuienSuministra()
    {
        return $this->nombreQuienSuministra;
    }

    /**
     * @param mixed $nombreQuienSuministra
     */
    public function setNombreQuienSuministra($nombreQuienSuministra): void
    {
        $this->nombreQuienSuministra = $nombreQuienSuministra;
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
    public function getEstadoVerificado()
    {
        return $this->estadoVerificado;
    }

    /**
     * @param mixed $estadoVerificado
     */
    public function setEstadoVerificado($estadoVerificado): void
    {
        $this->estadoVerificado = $estadoVerificado;
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



}
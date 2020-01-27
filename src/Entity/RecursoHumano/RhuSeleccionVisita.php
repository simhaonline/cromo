<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSeleccionVisitaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSeleccionVisita
{
    public $infoLog = [
        "primaryKey" => "codigoSeleccionVisitaPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_visita_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionVisitaPk;

    /**
     * @ORM\Column(name="codigo_seleccion_fk", type="integer")
     */
    private $codigoSeleccionFk;

    /**
     * @ORM\Column(name="fecha" , type="datetime" , nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="nombre_quien_visita", type="string", length=100, nullable=true)
     */
    private $nombreQuienVisita;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="cliente_referencia", type="string", length=100, nullable=true)
     */
    private $clienteReferencia;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccion", inversedBy="seleccionesVisitasSeleccionRel")
     * @ORM\JoinColumn(name="codigo_seleccion_fk", referencedColumnName="codigo_seleccion_pk")
     */
    protected $seleccionRel;

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
    public function getCodigoSeleccionVisitaPk()
    {
        return $this->codigoSeleccionVisitaPk;
    }

    /**
     * @param mixed $codigoSeleccionVisitaPk
     */
    public function setCodigoSeleccionVisitaPk($codigoSeleccionVisitaPk): void
    {
        $this->codigoSeleccionVisitaPk = $codigoSeleccionVisitaPk;
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
    public function getNombreQuienVisita()
    {
        return $this->nombreQuienVisita;
    }

    /**
     * @param mixed $nombreQuienVisita
     */
    public function setNombreQuienVisita($nombreQuienVisita): void
    {
        $this->nombreQuienVisita = $nombreQuienVisita;
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
    public function getClienteReferencia()
    {
        return $this->clienteReferencia;
    }

    /**
     * @param mixed $clienteReferencia
     */
    public function setClienteReferencia($clienteReferencia): void
    {
        $this->clienteReferencia = $clienteReferencia;
    }



}

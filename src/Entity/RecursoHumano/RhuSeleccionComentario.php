<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSeleccionComentarioRepository")
 */
class RhuSeleccionComentario
{
    public $infoLog = [
        "primaryKey" => "codigoSeleccionComentarioPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_comentario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionComentarioPk;

    /**
     * @ORM\Column(name="codigo_seleccion_fk", type="integer", nullable=true)
     */
    private $codigoSeleccionFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccion", inversedBy="seleccionesAntecedentesSeleccionRel")
     * @ORM\JoinColumn(name="codigo_seleccion_fk", referencedColumnName="codigo_seleccion_pk")
     */
    protected $seleccionRel;

    /**
     * @return mixed
     */
    public function getCodigoSeleccionComentarioPk()
    {
        return $this->codigoSeleccionComentarioPk;
    }

    /**
     * @param mixed $codigoSeleccionComentarioPk
     */
    public function setCodigoSeleccionComentarioPk($codigoSeleccionComentarioPk): void
    {
        $this->codigoSeleccionComentarioPk = $codigoSeleccionComentarioPk;
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


}
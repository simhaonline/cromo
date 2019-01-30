<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenComentarioModeloRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenComentarioModelo
{
    public $infoLog = [
        "primaryKey" => "codigoComentarioModeloPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_comentario_modelo_pk")
     */
    private $codigoComentarioModeloPk;

    /**
     * @ORM\Column(name="codigo", type="integer", nullable=true)
     */
    private $codigo;

    /**
     * @ORM\Column(name="codigo_modelo_fk", length=80, type="string", nullable=true)
     */
    private $codigoModeloFk;

    /**
     * @ORM\Column(name="comentario", type="text", nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_usuario", length=80, type="string", nullable=true)
     */
    private $codigoUsuario;

    /**
     * @return mixed
     */
    public function getCodigoComentarioModeloPk()
    {
        return $this->codigoComentarioModeloPk;
    }

    /**
     * @param mixed $codigoComentarioModeloPk
     */
    public function setCodigoComentarioModeloPk( $codigoComentarioModeloPk ): void
    {
        $this->codigoComentarioModeloPk = $codigoComentarioModeloPk;
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
    public function setCodigo( $codigo ): void
    {
        $this->codigo = $codigo;
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
    public function setCodigoModeloFk( $codigoModeloFk ): void
    {
        $this->codigoModeloFk = $codigoModeloFk;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario( $comentario ): void
    {
        $this->comentario = $comentario;
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
    public function setFecha( $fecha ): void
    {
        $this->fecha = $fecha;
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
    public function setCodigoUsuario( $codigoUsuario ): void
    {
        $this->codigoUsuario = $codigoUsuario;
    }



}


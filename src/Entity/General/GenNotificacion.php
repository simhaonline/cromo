<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenNotificacionRepository")
 */
class GenNotificacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_notificacion_pk", unique=true, nullable=false)
     */
    private $codigoNotificacionPk;

    /**
     * @ORM\Column(name="codigo_notificacion_tipo_fk", type="integer", nullable=false)
     */
    private $codigoNotificacionTipoFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @ORM\Column(name="descripcion", type="string", name="descripcion",length=1000, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="codigo_usuario_receptor_fk", type="string", nullable=false)
     */
    private $codigoUsuarioReceptorFk;

    /**
     * @ORM\Column(name="codigo_usuario_emisor_fk", type="string", nullable=true)
     */
    private $codigoUsuarioEmisorFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenNotificacionTipo", inversedBy="notificacionesNotificacionTipoRel")
     * @ORM\JoinColumn(name="codigo_notificacion_tipo_fk", referencedColumnName="codigo_notificacion_tipo_pk")
     */
    protected $notificacionTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoNotificacionPk()
    {
        return $this->codigoNotificacionPk;
    }

    /**
     * @param mixed $codigoNotificacionPk
     */
    public function setCodigoNotificacionPk( $codigoNotificacionPk ): void
    {
        $this->codigoNotificacionPk = $codigoNotificacionPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoNotificacionTipoFk()
    {
        return $this->codigoNotificacionTipoFk;
    }

    /**
     * @param mixed $codigoNotificacionTipoFk
     */
    public function setCodigoNotificacionTipoFk( $codigoNotificacionTipoFk ): void
    {
        $this->codigoNotificacionTipoFk = $codigoNotificacionTipoFk;
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
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion( $descripcion ): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuarioReceptorFk()
    {
        return $this->codigoUsuarioReceptorFk;
    }

    /**
     * @param mixed $codigoUsuarioReceptorFk
     */
    public function setCodigoUsuarioReceptorFk( $codigoUsuarioReceptorFk ): void
    {
        $this->codigoUsuarioReceptorFk = $codigoUsuarioReceptorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuarioEmisorFk()
    {
        return $this->codigoUsuarioEmisorFk;
    }

    /**
     * @param mixed $codigoUsuarioEmisorFk
     */
    public function setCodigoUsuarioEmisorFk( $codigoUsuarioEmisorFk ): void
    {
        $this->codigoUsuarioEmisorFk = $codigoUsuarioEmisorFk;
    }

    /**
     * @return mixed
     */
    public function getNotificacionTipoRel()
    {
        return $this->notificacionTipoRel;
    }

    /**
     * @param mixed $notificacionTipoRel
     */
    public function setNotificacionTipoRel( $notificacionTipoRel ): void
    {
        $this->notificacionTipoRel = $notificacionTipoRel;
    }



}

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
     * @ORM\Column(name="codigo_usuario_fk", type="integer", nullable=false)
     */
    private $codigoUsuarioFk;

    /**
     * @return mixed
     */
    public function getCodigoNotificacionPk()
    {
        return $this->codigoNotificacionPk;
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
    public function setCodigoNotificacionTipoFk($codigoNotificacionTipoFk)
    {
        $this->codigoNotificacionTipoFk = $codigoNotificacionTipoFk;
        return $this;
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
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * @param mixed $codigoUsuarioFk
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;
        return $this;
    }


}

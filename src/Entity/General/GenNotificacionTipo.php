<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenNotificacionTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenNotificacionTipo
{
    public $infoLog = [
        "primaryKey" => "codigoNotificacionTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer", name="codigo_notificacion_tipo_pk", unique=true, nullable=false)
     */
    private $codigoNotificacionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=200)
     */
    private $nombre;

    /**
     * @ORM\Column(name="notificacion", type="string", length=200)
     */
    private $notificacion;

    /**
     * @ORM\Column(name="usuarios", type="string", nullable=true, length=2048)
     */
    private $usuarios;

    /**
     * @ORM\Column(name="estado_activo", type="boolean", nullable=true, options={"default":false})
     */
    private $estadoActivo=false;

    /**
     * @ORM\Column(type="string", name="codigo_modulo_pk",length=80, nullable=true)
     */
    private $codigoModuloFk;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\GenNotificacion", mappedBy="notificacionTipoRel")
     */
    protected $notificacionesNotificacionTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoNotificacionTipoPk()
    {
        return $this->codigoNotificacionTipoPk;
    }

    /**
     * @param mixed $codigoNotificacionTipoPk
     */
    public function setCodigoNotificacionTipoPk( $codigoNotificacionTipoPk ): void
    {
        $this->codigoNotificacionTipoPk = $codigoNotificacionTipoPk;
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
    public function setNombre( $nombre ): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getNotificacion()
    {
        return $this->notificacion;
    }

    /**
     * @param mixed $notificacion
     */
    public function setNotificacion( $notificacion ): void
    {
        $this->notificacion = $notificacion;
    }

    /**
     * @return mixed
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * @param mixed $usuarios
     */
    public function setUsuarios( $usuarios ): void
    {
        $this->usuarios = $usuarios;
    }

    /**
     * @return mixed
     */
    public function getEstadoActivo()
    {
        return $this->estadoActivo;
    }

    /**
     * @param mixed $estadoActivo
     */
    public function setEstadoActivo( $estadoActivo ): void
    {
        $this->estadoActivo = $estadoActivo;
    }

    /**
     * @return mixed
     */
    public function getCodigoModuloFk()
    {
        return $this->codigoModuloFk;
    }

    /**
     * @param mixed $codigoModuloFk
     */
    public function setCodigoModuloFk( $codigoModuloFk ): void
    {
        $this->codigoModuloFk = $codigoModuloFk;
    }

    /**
     * @return mixed
     */
    public function getNotificacionesNotificacionTipoRel()
    {
        return $this->notificacionesNotificacionTipoRel;
    }

    /**
     * @param mixed $notificacionesNotificacionTipoRel
     */
    public function setNotificacionesNotificacionTipoRel( $notificacionesNotificacionTipoRel ): void
    {
        $this->notificacionesNotificacionTipoRel = $notificacionesNotificacionTipoRel;
    }




}

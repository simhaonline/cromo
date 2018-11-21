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
     * @ORM\Column(name="nombre", type="string", length=10)
     */
    private $nombre;

    /**
     * @ORM\Column(name="usuarios", type="string", nullable=true, length=2048)
     */
    private $usuarios;

    /**
     * @ORM\Column(name="estado_activo", type="boolean", nullable=true, options={"default":true})
     */
    private $estadoActivo;

    /**
     * @ORM\Column(name="codigo_modelo_fk",  type="string",length=80, nullable=false)
     */
    private $codigoModeloFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenModelo", inversedBy="notificacionTipoModeloRel")
     * @ORM\JoinColumn(name="codigo_modelo_fk", referencedColumnName="codigo_modelo_pk")
     */
    protected $modeloRel;

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
    public function setCodigoNotificacionTipoPk($codigoNotificacionTipoPk): void
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
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
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
    public function setUsuarios($usuarios): void
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
    public function setEstadoActivo($estadoActivo): void
    {
        $this->estadoActivo = $estadoActivo;
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
    public function setCodigoModeloFk($codigoModeloFk): void
    {
        $this->codigoModeloFk = $codigoModeloFk;
    }

    /**
     * @return mixed
     */
    public function getModeloRel()
    {
        return $this->modeloRel;
    }

    /**
     * @param mixed $modeloRel
     */
    public function setModeloRel($modeloRel): void
    {
        $this->modeloRel = $modeloRel;
    }
}

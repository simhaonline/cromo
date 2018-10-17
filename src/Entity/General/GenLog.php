<?php
/**
 * Created by PhpStorm.
 * User: jako
 * Date: 9/03/18
 * Time: 10:51 AM
 */

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenLogRepository")
 */
class GenLog
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_log_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLogPk;

    /**
     * @ORM\Column(name="codigo_registro_pk", type="string", nullable=true)
     */
    private $codigoRegistroPk;

    /**
     * @ORM\Column(name="accion", type="string", length=20, nullable=true)
     */
    private $accion;

    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer", nullable=true)
     */
    private $codigoUsuarioFk;

    /**
     * @ORM\Column(name="nombre_usuario", type="string", nullable=true)
     */
    private $nombreUsuario;

    /**
     * @ORM\Column(name="campos_seguimiento", type="text", nullable=true)
     */
    private $camposSeguimiento;

    /**
     * @ORM\Column(name="nombre_entidad", type="string", length=500, nullable=true)
     */
    private $nombreEntidad;

    /**
     * @ORM\Column(name="namespace_entidad", type="string", length=500, nullable=true)
     */
    private $namespaceEntidad;

    /**
     * @ORM\Column(name="ruta", type="text", nullable=true)
     */
    private $ruta;

    /**
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Seguridad\Usuario", inversedBy="logsUsuarioRel")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
     */
    protected $usuarioRel;

    /**
     * @return mixed
     */
    public function getCodigoLogPk()
    {
        return $this->codigoLogPk;
    }

    /**
     * @param mixed $codigoLogPk
     * @return GenLog
     */
    public function setCodigoLogPk($codigoLogPk)
    {
        $this->codigoLogPk = $codigoLogPk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoRegistroPk()
    {
        return $this->codigoRegistroPk;
    }

    /**
     * @param mixed $codigoRegistroPk
     * @return GenLog
     */
    public function setCodigoRegistroPk($codigoRegistroPk)
    {
        $this->codigoRegistroPk = $codigoRegistroPk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * @param mixed $accion
     * @return GenLog
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;
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
     * @return GenLog
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCamposSeguimiento()
    {
        return $this->camposSeguimiento;
    }

    /**
     * @param mixed $camposSeguimiento
     * @return GenLog
     */
    public function setCamposSeguimiento($camposSeguimiento)
    {
        $this->camposSeguimiento = $camposSeguimiento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombreEntidad()
    {
        return $this->nombreEntidad;
    }

    /**
     * @param mixed $nombreEntidad
     * @return GenLog
     */
    public function setNombreEntidad($nombreEntidad)
    {
        $this->nombreEntidad = $nombreEntidad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNamespaceEntidad()
    {
        return $this->namespaceEntidad;
    }

    /**
     * @param mixed $namespaceEntidad
     * @return GenLog
     */
    public function setNamespaceEntidad($namespaceEntidad)
    {
        $this->namespaceEntidad = $namespaceEntidad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * @param mixed $ruta
     * @return GenLog
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
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
     * @return GenLog
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsuarioRel()
    {
        return $this->usuarioRel;
    }

    /**
     * @param mixed $usuarioRel
     * @return GenLog
     */
    public function setUsuarioRel($usuarioRel)
    {
        $this->usuarioRel = $usuarioRel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    /**
     * @param mixed $nombreUsuario
     */
    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;
    }
}

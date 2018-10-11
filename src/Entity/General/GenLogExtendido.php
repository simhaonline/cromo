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
 * @ORM\Entity(repositoryClass="App\Repository\General\GenLogExtendidoRepository")
 */
class GenLogExtendido
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_log_extendido_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLogExtendidoPk;

    /**
     * @ORM\Column(name="codigo_registro_pk", type="string", nullable=true)
     */
    private $codigoRegistroPk;

    /**
     * @ORM\Column(name="accion", type="string", length=20, nullable=true)
     */
    private $accion;

    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer")
     */
    private $codigoUsuarioFk;

    /**
     * @ORM\Column(name="campos_seguimiento", type="text", nullable=true)
     */
    private $camposSeguimiento;

    /**
     * @ORM\Column(name="campos_seguimiento_mostrar", type="text", nullable=true)
     */
    private $camposSeguimientoMostrar;

    /**
     * @ORM\Column(name="nombre_entidad", type="string", length=500, nullable=true)
     */
    private $nombreEntidad;

    /**
     * @ORM\Column(name="namespace_entidad", type="string", length=500, nullable=true)
     */
    private $namespaceEntidad;

    /**
     * @ORM\Column(name="modulo", type="string", length=200, nullable=true)
     */
    private $modulo;

    /**
     * @ORM\Column(name="ruta", type="text", nullable=true)
     */
    private $ruta;

    /**
     * @ORM\Column(name="codigo_padre", type="integer", nullable=true)
     */
    private $codigoPadre;

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
    public function getCodigoLogExtendidoPk()
    {
        return $this->codigoLogExtendidoPk;
    }

    /**
     * @param mixed $codigoLogExtendidoPk
     * @return GenLogExtendido
     */
    public function setCodigoLogExtendidoPk($codigoLogExtendidoPk)
    {
        $this->codigoLogExtendidoPk = $codigoLogExtendidoPk;
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
     * @return GenLogExtendido
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
     * @return GenLogExtendido
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
     * @return GenLogExtendido
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
     * @return GenLogExtendido
     */
    public function setCamposSeguimiento($camposSeguimiento)
    {
        $this->camposSeguimiento = $camposSeguimiento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCamposSeguimientoMostrar()
    {
        return $this->camposSeguimientoMostrar;
    }

    /**
     * @param mixed $camposSeguimientoMostrar
     * @return GenLogExtendido
     */
    public function setCamposSeguimientoMostrar($camposSeguimientoMostrar)
    {
        $this->camposSeguimientoMostrar = $camposSeguimientoMostrar;
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
     * @return GenLogExtendido
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
     * @return GenLogExtendido
     */
    public function setNamespaceEntidad($namespaceEntidad)
    {
        $this->namespaceEntidad = $namespaceEntidad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * @param mixed $modulo
     * @return GenLogExtendido
     */
    public function setModulo($modulo)
    {
        $this->modulo = $modulo;
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
     * @return GenLogExtendido
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoPadre()
    {
        return $this->codigoPadre;
    }

    /**
     * @param mixed $codigoPadre
     * @return GenLogExtendido
     */
    public function setCodigoPadre($codigoPadre)
    {
        $this->codigoPadre = $codigoPadre;
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
     * @return GenLogExtendido
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
     * @return GenLogExtendido
     */
    public function setUsuarioRel($usuarioRel)
    {
        $this->usuarioRel = $usuarioRel;
        return $this;
    }
}

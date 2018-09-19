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
     * @return mixed
     */
    public function getCodigoLogExtendidoPk()
    {
        return $this->codigoLogExtendidoPk;
    }

    /**
     * @param mixed $codigoLogExtendidoPk
     */
    public function setCodigoLogExtendidoPk($codigoLogExtendidoPk): void
    {
        $this->codigoLogExtendidoPk = $codigoLogExtendidoPk;
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
     */
    public function setCodigoRegistroPk($codigoRegistroPk): void
    {
        $this->codigoRegistroPk = $codigoRegistroPk;
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
     */
    public function setAccion($accion): void
    {
        $this->accion = $accion;
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
    public function setCodigoUsuarioFk($codigoUsuarioFk): void
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;
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
     */
    public function setCamposSeguimiento($camposSeguimiento): void
    {
        $this->camposSeguimiento = $camposSeguimiento;
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
     */
    public function setCamposSeguimientoMostrar($camposSeguimientoMostrar): void
    {
        $this->camposSeguimientoMostrar = $camposSeguimientoMostrar;
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
     */
    public function setNombreEntidad($nombreEntidad): void
    {
        $this->nombreEntidad = $nombreEntidad;
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
     */
    public function setNamespaceEntidad($namespaceEntidad): void
    {
        $this->namespaceEntidad = $namespaceEntidad;
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
     */
    public function setModulo($modulo): void
    {
        $this->modulo = $modulo;
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
     */
    public function setRuta($ruta): void
    {
        $this->ruta = $ruta;
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
     */
    public function setCodigoPadre($codigoPadre): void
    {
        $this->codigoPadre = $codigoPadre;
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

//    /**
//     * @ORM\ManyToOne(targetEntity="Brasa\SeguridadBundle\Entity\User", inversedBy="logsUsuarioRel")
//     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
//     */
//    protected $usuarioRel;



}

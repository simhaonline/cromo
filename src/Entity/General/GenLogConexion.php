<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenLogConexionRepository")
 */
class GenLogConexion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_log_conexion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLogConexionPk;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=25, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * Login = C, Logout = D
     * @ORM\Column(name="accion", type="string", length=1, nullable=true)
     */
    private $accion;

    /**
     * @return mixed
     */
    public function getCodigoLogConexionPk()
    {
        return $this->codigoLogConexionPk;
    }

    /**
     * @param mixed $codigoLogConexionPk
     */
    public function setCodigoLogConexionPk($codigoLogConexionPk): void
    {
        $this->codigoLogConexionPk = $codigoLogConexionPk;
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
    public function setCodigoUsuario($codigoUsuario): void
    {
        $this->codigoUsuario = $codigoUsuario;
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
}

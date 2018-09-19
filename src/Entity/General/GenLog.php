<?php

namespace App\Entity\General;

use Symfony\Component\Validator\Constraints as Assert;
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
     * @ORM\Column(name="codigo_usuario_fk", type="integer")
     */
    private $codigoUsuarioFk;    
    
    /**
     * @ORM\Column(name="codigo_log_accion_fk", type="integer")
     */
    private $codigoLogAccionFk;          
    
    /**
     * @ORM\Column(name="fecha", type="datetime")
     */    
    private $fecha;  

    /**
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

        /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Seguridad\Usuario", inversedBy="logsUsuarioRel")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
     */
    protected $usuarioRel;

        /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenLogAccion", inversedBy="logsLogAccionRel")
     * @ORM\JoinColumn(name="codigo_log_accion_fk", referencedColumnName="codigo_log_accion_pk")
     */
    protected $logAccionRel;

    /**
     * @return mixed
     */
    public function getCodigoLogPk()
    {
        return $this->codigoLogPk;
    }

    /**
     * @param mixed $codigoLogPk
     */
    public function setCodigoLogPk($codigoLogPk): void
    {
        $this->codigoLogPk = $codigoLogPk;
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
    public function getCodigoLogAccionFk()
    {
        return $this->codigoLogAccionFk;
    }

    /**
     * @param mixed $codigoLogAccionFk
     */
    public function setCodigoLogAccionFk($codigoLogAccionFk): void
    {
        $this->codigoLogAccionFk = $codigoLogAccionFk;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
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
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
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
     */
    public function setUsuarioRel($usuarioRel): void
    {
        $this->usuarioRel = $usuarioRel;
    }

    /**
     * @return mixed
     */
    public function getLogAccionRel()
    {
        return $this->logAccionRel;
    }

    /**
     * @param mixed $logAccionRel
     */
    public function setLogAccionRel($logAccionRel): void
    {
        $this->logAccionRel = $logAccionRel;
    }


}

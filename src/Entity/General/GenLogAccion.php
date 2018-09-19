<?php

namespace App\Entity\General;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenLogAccionRepository")
 */
class GenLogAccion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_log_accion_pk", type="integer")
     */
    private $codigoLogAccionPk;   
     
    /**
     * @ORM\Column(name="nombre", type="string", length=30)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\GenLog", mappedBy="logAccionRel")
     */
    protected $logsLogAccionRel;

    /**
     * @return mixed
     */
    public function getCodigoLogAccionPk()
    {
        return $this->codigoLogAccionPk;
    }

    /**
     * @param mixed $codigoLogAccionPk
     */
    public function setCodigoLogAccionPk($codigoLogAccionPk): void
    {
        $this->codigoLogAccionPk = $codigoLogAccionPk;
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
    public function getLogsLogAccionRel()
    {
        return $this->logsLogAccionRel;
    }

    /**
     * @param mixed $logsLogAccionRel
     */
    public function setLogsLogAccionRel($logsLogAccionRel): void
    {
        $this->logsLogAccionRel = $logsLogAccionRel;
    }



}

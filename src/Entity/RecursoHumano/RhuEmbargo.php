<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEmbargoRepository")
 */
class RhuEmbargo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_embargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmbargoPk;

    /**
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @return mixed
     */
    public function getCodigoEmbargoPk()
    {
        return $this->codigoEmbargoPk;
    }

    /**
     * @param mixed $codigoEmbargoPk
     */
    public function setCodigoEmbargoPk($codigoEmbargoPk): void
    {
        $this->codigoEmbargoPk = $codigoEmbargoPk;
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
}

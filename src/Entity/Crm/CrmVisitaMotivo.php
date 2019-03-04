<?php

namespace App\Entity\Crm;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Crm\CrmVisitaMotivoRepository")
 */
class CrmVisitaMotivo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", name="codigo_visita_motivo_pk", length=20, nullable=false, unique=true)
     */
    private $codigoVisitaMotivoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=20, nullable=false)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoVisitaMotivoPk()
    {
        return $this->codigoVisitaMotivoPk;
    }

    /**
     * @param mixed $codigoVisitaMotivoPk
     */
    public function setCodigoVisitaMotivoPk($codigoVisitaMotivoPk): void
    {
        $this->codigoVisitaMotivoPk = $codigoVisitaMotivoPk;
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



}

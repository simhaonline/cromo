<?php


namespace App\Entity\Crm;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Crm\CrmFaseRepository")
 */
class CrmFase
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", name="codigo_fase_pk", length=20, nullable=false, unique=true)
     */
    private $codigoFasePk;

    /**
     * @ORM\Column(name="nombre", type="string", length=20, nullable=false)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoFasePk()
    {
        return $this->codigoFasePk;
    }

    /**
     * @param mixed $codigoFasePk
     */
    public function setCodigoFasePk($codigoFasePk): void
    {
        $this->codigoFasePk = $codigoFasePk;
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
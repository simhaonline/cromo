<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenSexoRepository")
 */
class GenSexo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSexoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="genSexoRel")
     */
    protected $rhuSolicitudSexoRel;

    /**
     * @return mixed
     */
    public function getCodigoSexoPk()
    {
        return $this->codigoSexoPk;
    }

    /**
     * @param mixed $codigoSexoPk
     */
    public function setCodigoSexoPk($codigoSexoPk): void
    {
        $this->codigoSexoPk = $codigoSexoPk;
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
    public function getRhuSolicitudSexoRel()
    {
        return $this->rhuSolicitudSexoRel;
    }

    /**
     * @param mixed $rhuSolicitudSexoRel
     */
    public function setRhuSolicitudSexoRel($rhuSolicitudSexoRel): void
    {
        $this->rhuSolicitudSexoRel = $rhuSolicitudSexoRel;
    }



}


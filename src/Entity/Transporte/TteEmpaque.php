<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteEmpaqueRepository")
 */
class TteEmpaque
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoEmpaquePk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true, options={"default" : 0})
     */
    private $orden = 0;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="empaqueRel")
     */
    protected $guiasEmpaqueRel;

    /**
     * @return mixed
     */
    public function getCodigoEmpaquePk()
    {
        return $this->codigoEmpaquePk;
    }

    /**
     * @param mixed $codigoEmpaquePk
     */
    public function setCodigoEmpaquePk($codigoEmpaquePk): void
    {
        $this->codigoEmpaquePk = $codigoEmpaquePk;
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
    public function getGuiasEmpaqueRel()
    {
        return $this->guiasEmpaqueRel;
    }

    /**
     * @param mixed $guiasEmpaqueRel
     */
    public function setGuiasEmpaqueRel($guiasEmpaqueRel): void
    {
        $this->guiasEmpaqueRel = $guiasEmpaqueRel;
    }

    /**
     * @return mixed
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     */
    public function setOrden($orden): void
    {
        $this->orden = $orden;
    }


}


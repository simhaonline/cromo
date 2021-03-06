<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenSectorComercialRepository")
 */
class GenSectorComercial
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sector_comercial_pk", type="integer")
     */
    private $codigoSectorComercialPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCliente", mappedBy="sectorComercialRel")
     */
    protected $turClientesSectorComercialRel;

    /**
     * @return mixed
     */
    public function getCodigoSectorComercialPk()
    {
        return $this->codigoSectorComercialPk;
    }

    /**
     * @param mixed $codigoSectorComercialPk
     */
    public function setCodigoSectorComercialPk($codigoSectorComercialPk): void
    {
        $this->codigoSectorComercialPk = $codigoSectorComercialPk;
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
    public function getTurClientesSectorComercialRel()
    {
        return $this->turClientesSectorComercialRel;
    }

    /**
     * @param mixed $turClientesSectorComercialRel
     */
    public function setTurClientesSectorComercialRel($turClientesSectorComercialRel): void
    {
        $this->turClientesSectorComercialRel = $turClientesSectorComercialRel;
    }


}
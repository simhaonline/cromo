<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenSectorEconomicoRepository")
 */
class GenSectorEconomico
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sector_economico_pk", type="integer")
     */
    private $codigoSectorEconomicoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCliente", mappedBy="sectorEconomicoRel")
     */
    protected $turClientesSectorEconomicoRel;

    /**
     * @return mixed
     */
    public function getCodigoSectorEconomicoPk()
    {
        return $this->codigoSectorEconomicoPk;
    }

    /**
     * @param mixed $codigoSectorEconomicoPk
     */
    public function setCodigoSectorEconomicoPk($codigoSectorEconomicoPk): void
    {
        $this->codigoSectorEconomicoPk = $codigoSectorEconomicoPk;
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
    public function getTurClientesSectorEconomicoRel()
    {
        return $this->turClientesSectorEconomicoRel;
    }

    /**
     * @param mixed $turClientesSectorEconomicoRel
     */
    public function setTurClientesSectorEconomicoRel($turClientesSectorEconomicoRel): void
    {
        $this->turClientesSectorEconomicoRel = $turClientesSectorEconomicoRel;
    }


}
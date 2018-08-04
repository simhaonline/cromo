<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteLineaRepository")
 */
class TteLinea
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoLineaPk;

    /**
     * @ORM\Column(name="codigo_marca_fk", type="string", length=20, nullable=true)
     */
    private $codigoMarcaFk;

    /**
     * @ORM\Column(name="linea", type="string", length=100, nullable=true)
     */
    private $linea;


    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteMarca", inversedBy="lineasMarcaRel")
     * @ORM\JoinColumn(name="codigo_marca_fk", referencedColumnName="codigo_marca_pk")
     */
    private $marcaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteVehiculo", mappedBy="lineaRel")
     */
    protected $vehiculosLineaRel;

    /**
     * @return mixed
     */
    public function getCodigoLineaPk()
    {
        return $this->codigoLineaPk;
    }

    /**
     * @param mixed $codigoLineaPk
     */
    public function setCodigoLineaPk($codigoLineaPk): void
    {
        $this->codigoLineaPk = $codigoLineaPk;
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
    public function getCodigoMarcaFk()
    {
        return $this->codigoMarcaFk;
    }

    /**
     * @param mixed $codigoMarcaFk
     */
    public function setCodigoMarcaFk($codigoMarcaFk): void
    {
        $this->codigoMarcaFk = $codigoMarcaFk;
    }

    /**
     * @return mixed
     */
    public function getMarcaRel()
    {
        return $this->marcaRel;
    }

    /**
     * @param mixed $marcaRel
     */
    public function setMarcaRel($marcaRel): void
    {
        $this->marcaRel = $marcaRel;
    }

    /**
     * @return mixed
     */
    public function getVehiculosLineaRel()
    {
        return $this->vehiculosLineaRel;
    }

    /**
     * @param mixed $vehiculosLineaRel
     */
    public function setVehiculosLineaRel($vehiculosLineaRel): void
    {
        $this->vehiculosLineaRel = $vehiculosLineaRel;
    }

    /**
     * @return mixed
     */
    public function getLinea()
    {
        return $this->linea;
    }

    /**
     * @param mixed $linea
     */
    public function setLinea($linea): void
    {
        $this->linea = $linea;
    }


}


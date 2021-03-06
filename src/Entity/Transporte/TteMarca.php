<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteMarcaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteMarca
{
    public $infoLog = [
        "primaryKey" => "codigoMarcaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoMarcaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteVehiculo", mappedBy="marcaRel")
     */
    protected $vehiculosMarcaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteLinea", mappedBy="marcaRel")
     */
    protected $lineasMarcaRel;

    /**
     * @return mixed
     */
    public function getCodigoMarcaPk()
    {
        return $this->codigoMarcaPk;
    }

    /**
     * @param mixed $codigoMarcaPk
     */
    public function setCodigoMarcaPk($codigoMarcaPk): void
    {
        $this->codigoMarcaPk = $codigoMarcaPk;
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
    public function getVehiculosMarcaRel()
    {
        return $this->vehiculosMarcaRel;
    }

    /**
     * @param mixed $vehiculosMarcaRel
     */
    public function setVehiculosMarcaRel($vehiculosMarcaRel): void
    {
        $this->vehiculosMarcaRel = $vehiculosMarcaRel;
    }

    /**
     * @return mixed
     */
    public function getLineasMarcaRel()
    {
        return $this->lineasMarcaRel;
    }

    /**
     * @param mixed $lineasMarcaRel
     */
    public function setLineasMarcaRel($lineasMarcaRel): void
    {
        $this->lineasMarcaRel = $lineasMarcaRel;
    }


}


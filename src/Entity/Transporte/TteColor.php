<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteColorRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteColor
{
    public $infoLog = [
        "primaryKey" => "codigoColorPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoColorPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TteVehiculo", mappedBy="colorRel")
     */
    protected $vehiculosColorRel;

    /**
     * @return mixed
     */
    public function getCodigoColorPk()
    {
        return $this->codigoColorPk;
    }

    /**
     * @param mixed $codigoColorPk
     */
    public function setCodigoColorPk($codigoColorPk): void
    {
        $this->codigoColorPk = $codigoColorPk;
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
    public function getVehiculosColorRel()
    {
        return $this->vehiculosColorRel;
    }

    /**
     * @param mixed $vehiculosColorRel
     */
    public function setVehiculosColorRel($vehiculosColorRel): void
    {
        $this->vehiculosColorRel = $vehiculosColorRel;
    }



}


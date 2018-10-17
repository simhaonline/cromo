<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteTipoCarroceriaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteTipoCarroceria
{
    public $infoLog = [
        "primaryKey" => "codigoTipoCarroceriaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoTipoCarroceriaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TteVehiculo", mappedBy="tipoCarroceriaRel")
     */
    protected $vehiculosTipoCarroceriaRel;

    /**
     * @return mixed
     */
    public function getCodigoTipoCarroceriaPk()
    {
        return $this->codigoTipoCarroceriaPk;
    }

    /**
     * @param mixed $codigoTipoCarroceriaPk
     */
    public function setCodigoTipoCarroceriaPk($codigoTipoCarroceriaPk): void
    {
        $this->codigoTipoCarroceriaPk = $codigoTipoCarroceriaPk;
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
    public function getVehiculosTipoCarroceriaRel()
    {
        return $this->vehiculosTipoCarroceriaRel;
    }

    /**
     * @param mixed $vehiculosTipoCarroceriaRel
     */
    public function setVehiculosTipoCarroceriaRel($vehiculosTipoCarroceriaRel): void
    {
        $this->vehiculosTipoCarroceriaRel = $vehiculosTipoCarroceriaRel;
    }



}


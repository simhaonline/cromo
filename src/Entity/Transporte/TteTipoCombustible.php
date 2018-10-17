<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteTipoCombustibleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteTipoCombustible
{
    public $infoLog = [
        "primaryKey" => "codigoTipoCombustiblePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoTipoCombustiblePk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TteVehiculo", mappedBy="tipoCombustibleRel")
     */
    protected $vehiculosTipoCombustibleRel;

    /**
     * @return mixed
     */
    public function getCodigoTipoCombustiblePk()
    {
        return $this->codigoTipoCombustiblePk;
    }

    /**
     * @param mixed $codigoTipoCombustiblePk
     */
    public function setCodigoTipoCombustiblePk($codigoTipoCombustiblePk): void
    {
        $this->codigoTipoCombustiblePk = $codigoTipoCombustiblePk;
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
    public function getVehiculosTipoCombustibleRel()
    {
        return $this->vehiculosTipoCombustibleRel;
    }

    /**
     * @param mixed $vehiculosTipoCombustibleRel
     */
    public function setVehiculosTipoCombustibleRel($vehiculosTipoCombustibleRel): void
    {
        $this->vehiculosTipoCombustibleRel = $vehiculosTipoCombustibleRel;
    }



}


<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteZonaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteZona
{

    public $infoLog = [
        "primaryKey" => "codigoZonaPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoZonaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TteCiudad", mappedBy="zonaRel")
     */
    protected $ciudadesZonaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="zonaRel")
     */
    protected $guiasZonaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCondicionFlete", mappedBy="zonaRel")
     */
    protected $condicionesClientesZonaRel;

    /**
     * @ORM\OneToMany(targetEntity="TtePrecioDetalle", mappedBy="zonaRel")
     */
    protected $preciosDetallesZonaRel;

    /**
     * @return mixed
     */
    public function getCodigoZonaPk()
    {
        return $this->codigoZonaPk;
    }

    /**
     * @param mixed $codigoZonaPk
     */
    public function setCodigoZonaPk($codigoZonaPk): void
    {
        $this->codigoZonaPk = $codigoZonaPk;
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
    public function getCiudadesZonaRel()
    {
        return $this->ciudadesZonaRel;
    }

    /**
     * @param mixed $ciudadesZonaRel
     */
    public function setCiudadesZonaRel($ciudadesZonaRel): void
    {
        $this->ciudadesZonaRel = $ciudadesZonaRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasZonaRel()
    {
        return $this->guiasZonaRel;
    }

    /**
     * @param mixed $guiasZonaRel
     */
    public function setGuiasZonaRel($guiasZonaRel): void
    {
        $this->guiasZonaRel = $guiasZonaRel;
    }

    /**
     * @return mixed
     */
    public function getCondicionesClientesZonaRel()
    {
        return $this->condicionesClientesZonaRel;
    }

    /**
     * @param mixed $condicionesClientesZonaRel
     */
    public function setCondicionesClientesZonaRel($condicionesClientesZonaRel): void
    {
        $this->condicionesClientesZonaRel = $condicionesClientesZonaRel;
    }

    /**
     * @return mixed
     */
    public function getPreciosDetallesZonaRel()
    {
        return $this->preciosDetallesZonaRel;
    }

    /**
     * @param mixed $preciosDetallesZonaRel
     */
    public function setPreciosDetallesZonaRel($preciosDetallesZonaRel): void
    {
        $this->preciosDetallesZonaRel = $preciosDetallesZonaRel;
    }



}


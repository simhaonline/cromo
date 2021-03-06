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
    protected $condicionesFletesZonaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCondicionManejo", mappedBy="zonaRel")
     */
    protected $condicionesManejosZonaRel;

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
    public function getCondicionesFletesZonaRel()
    {
        return $this->condicionesFletesZonaRel;
    }

    /**
     * @param mixed $condicionesFletesZonaRel
     */
    public function setCondicionesFletesZonaRel($condicionesFletesZonaRel): void
    {
        $this->condicionesFletesZonaRel = $condicionesFletesZonaRel;
    }

    /**
     * @return mixed
     */
    public function getCondicionesManejosZonaRel()
    {
        return $this->condicionesManejosZonaRel;
    }

    /**
     * @param mixed $condicionesManejosZonaRel
     */
    public function setCondicionesManejosZonaRel($condicionesManejosZonaRel): void
    {
        $this->condicionesManejosZonaRel = $condicionesManejosZonaRel;
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


<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDescuentoZonaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteDescuentoZona
{
    public $infoLog = [
        "primaryKey" => "codigoDescuentoZonaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoDescuentoZonaPk;

    /**
     * @ORM\Column(name="codigo_condicion_fk", type="integer", nullable=true)
     */
    private $codigoCondicionFk;

    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadOrigenFk;

    /**
     * @ORM\Column(name="codigo_zona_fk", length=20, nullable=true)
     */
    private $codigoZonaFk;

    /**
     * @ORM\Column(name="descuento_peso", type="float", options={"default" : 0})
     */
    private $descuentoPeso = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TteCondicion", inversedBy="descuentosZonasCondicionRel")
     * @ORM\JoinColumn(name="codigo_condicion_fk", referencedColumnName="codigo_condicion_pk")
     */
    private $condicionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteZona", inversedBy="descuentosZonasZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    private $zonaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="descuentosZonasCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadOrigenRel;

    /**
     * @return mixed
     */
    public function getCodigoDescuentoZonaPk()
    {
        return $this->codigoDescuentoZonaPk;
    }

    /**
     * @param mixed $codigoDescuentoZonaPk
     */
    public function setCodigoDescuentoZonaPk($codigoDescuentoZonaPk): void
    {
        $this->codigoDescuentoZonaPk = $codigoDescuentoZonaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCondicionFk()
    {
        return $this->codigoCondicionFk;
    }

    /**
     * @param mixed $codigoCondicionFk
     */
    public function setCodigoCondicionFk($codigoCondicionFk): void
    {
        $this->codigoCondicionFk = $codigoCondicionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoZonaFk()
    {
        return $this->codigoZonaFk;
    }

    /**
     * @param mixed $codigoZonaFk
     */
    public function setCodigoZonaFk($codigoZonaFk): void
    {
        $this->codigoZonaFk = $codigoZonaFk;
    }

    /**
     * @return mixed
     */
    public function getDescuentoPeso()
    {
        return $this->descuentoPeso;
    }

    /**
     * @param mixed $descuentoPeso
     */
    public function setDescuentoPeso($descuentoPeso): void
    {
        $this->descuentoPeso = $descuentoPeso;
    }

    /**
     * @return mixed
     */
    public function getCondicionRel()
    {
        return $this->condicionRel;
    }

    /**
     * @param mixed $condicionRel
     */
    public function setCondicionRel($condicionRel): void
    {
        $this->condicionRel = $condicionRel;
    }

    /**
     * @return mixed
     */
    public function getZonaRel()
    {
        return $this->zonaRel;
    }

    /**
     * @param mixed $zonaRel
     */
    public function setZonaRel($zonaRel): void
    {
        $this->zonaRel = $zonaRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadOrigenFk()
    {
        return $this->codigoCiudadOrigenFk;
    }

    /**
     * @param mixed $codigoCiudadOrigenFk
     */
    public function setCodigoCiudadOrigenFk($codigoCiudadOrigenFk): void
    {
        $this->codigoCiudadOrigenFk = $codigoCiudadOrigenFk;
    }

    /**
     * @return mixed
     */
    public function getCiudadOrigenRel()
    {
        return $this->ciudadOrigenRel;
    }

    /**
     * @param mixed $ciudadOrigenRel
     */
    public function setCiudadOrigenRel($ciudadOrigenRel): void
    {
        $this->ciudadOrigenRel = $ciudadOrigenRel;
    }


}


<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteCondicionZonaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteCondicionZona
{
    public $infoLog = [
        "primaryKey" => "codigoCondicionZonaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoCondicionZonaPk;

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
     * @ORM\Column(name="porcentaje_manejo", type="float", options={"default" : 0})
     */
    private $porcentajeManejo = 0;

    /**
     * @ORM\Column(name="manejo_minimo_unidad", type="float", options={"default" : 0})
     */
    private $manejoMinimoUnidad = 0;

    /**
     * @ORM\Column(name="manejo_minimo_despacho", type="float", options={"default" : 0})
     */
    private $manejoMinimoDespacho = 0;

    /**
     * @ORM\Column(name="descuento_peso", type="float", options={"default" : 0})
     */
    private $descuentoPeso = 0;

    /**
     * @ORM\Column(name="descuento_unidad", type="float", options={"default" : 0})
     */
    private $descuentoUnidad = 0;

    /**
     * @ORM\Column(name="peso_minimo", type="integer", options={"default" : 0})
     */
    private $pesoMinimo = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TteCondicion", inversedBy="condicionesZonasCondicionRel")
     * @ORM\JoinColumn(name="codigo_condicion_fk", referencedColumnName="codigo_condicion_pk")
     */
    private $condicionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteZona", inversedBy="condicionesZonasZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    private $zonaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="condicionesZonasCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadOrigenRel;

    /**
     * @return mixed
     */
    public function getCodigoCondicionZonaPk()
    {
        return $this->codigoCondicionZonaPk;
    }

    /**
     * @param mixed $codigoCondicionZonaPk
     */
    public function setCodigoCondicionZonaPk($codigoCondicionZonaPk): void
    {
        $this->codigoCondicionZonaPk = $codigoCondicionZonaPk;
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
    public function getCondicionPeso()
    {
        return $this->condicionPeso;
    }

    /**
     * @param mixed $condicionPeso
     */
    public function setCondicionPeso($condicionPeso): void
    {
        $this->condicionPeso = $condicionPeso;
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

    /**
     * @return mixed
     */
    public function getPorcentajeManejo()
    {
        return $this->porcentajeManejo;
    }

    /**
     * @param mixed $porcentajeManejo
     */
    public function setPorcentajeManejo($porcentajeManejo): void
    {
        $this->porcentajeManejo = $porcentajeManejo;
    }

    /**
     * @return mixed
     */
    public function getManejoMinimoUnidad()
    {
        return $this->manejoMinimoUnidad;
    }

    /**
     * @param mixed $manejoMinimoUnidad
     */
    public function setManejoMinimoUnidad($manejoMinimoUnidad): void
    {
        $this->manejoMinimoUnidad = $manejoMinimoUnidad;
    }

    /**
     * @return mixed
     */
    public function getManejoMinimoDespacho()
    {
        return $this->manejoMinimoDespacho;
    }

    /**
     * @param mixed $manejoMinimoDespacho
     */
    public function setManejoMinimoDespacho($manejoMinimoDespacho): void
    {
        $this->manejoMinimoDespacho = $manejoMinimoDespacho;
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
    public function getDescuentoUnidad()
    {
        return $this->descuentoUnidad;
    }

    /**
     * @param mixed $descuentoUnidad
     */
    public function setDescuentoUnidad($descuentoUnidad): void
    {
        $this->descuentoUnidad = $descuentoUnidad;
    }

    /**
     * @return mixed
     */
    public function getPesoMinimo()
    {
        return $this->pesoMinimo;
    }

    /**
     * @param mixed $pesoMinimo
     */
    public function setPesoMinimo($pesoMinimo): void
    {
        $this->pesoMinimo = $pesoMinimo;
    }


}


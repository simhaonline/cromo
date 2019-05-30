<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteCondicionFleteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteCondicionFlete
{
    public $infoLog = [
        "primaryKey" => "codigoCondicionFletePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoCondicionFletePk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadOrigenFk;

    /**
     * @ORM\Column(name="codigo_ciudad_destino_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadDestinoFk;

    /**
     * @ORM\Column(name="codigo_zona_fk", length=20, nullable=true)
     */
    private $codigoZonaFk;

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
     * @ORM\Column(name="peso_minimo_guia", type="integer", options={"default" : 0})
     */
    private $pesoMinimoGuia = 0;

    /**
     * @ORM\Column(name="flete_minimo", type="float", options={"default" : 0})
     */
    private $fleteMinimo = 0;

    /**
     * @ORM\Column(name="flete_minimo_guia", type="float", options={"default" : 0})
     */
    private $fleteMinimoGuia = 0;

    /**
     * @ORM\Column(name="vr_peso", type="float", options={"default" : 0})
     */
    private $vrPeso = 0;

    /**
     * @ORM\Column(name="vr_unidad", type="float", options={"default" : 0})
     */
    private $vrUnidad = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="condicionesFletesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteZona", inversedBy="condicionesFletesZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    private $zonaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="condicionesFletesCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadOrigenRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="condicionesFletesCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadDestinoRel;

    /**
     * @return mixed
     */
    public function getCodigoCondicionFletePk()
    {
        return $this->codigoCondicionFletePk;
    }

    /**
     * @param mixed $codigoCondicionFletePk
     */
    public function setCodigoCondicionFletePk($codigoCondicionFletePk): void
    {
        $this->codigoCondicionFletePk = $codigoCondicionFletePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * @param mixed $codigoClienteFk
     */
    public function setCodigoClienteFk($codigoClienteFk): void
    {
        $this->codigoClienteFk = $codigoClienteFk;
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
    public function getCodigoCiudadDestinoFk()
    {
        return $this->codigoCiudadDestinoFk;
    }

    /**
     * @param mixed $codigoCiudadDestinoFk
     */
    public function setCodigoCiudadDestinoFk($codigoCiudadDestinoFk): void
    {
        $this->codigoCiudadDestinoFk = $codigoCiudadDestinoFk;
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

    /**
     * @return mixed
     */
    public function getPesoMinimoGuia()
    {
        return $this->pesoMinimoGuia;
    }

    /**
     * @param mixed $pesoMinimoGuia
     */
    public function setPesoMinimoGuia($pesoMinimoGuia): void
    {
        $this->pesoMinimoGuia = $pesoMinimoGuia;
    }

    /**
     * @return mixed
     */
    public function getFleteMinimo()
    {
        return $this->fleteMinimo;
    }

    /**
     * @param mixed $fleteMinimo
     */
    public function setFleteMinimo($fleteMinimo): void
    {
        $this->fleteMinimo = $fleteMinimo;
    }

    /**
     * @return mixed
     */
    public function getFleteMinimoGuia()
    {
        return $this->fleteMinimoGuia;
    }

    /**
     * @param mixed $fleteMinimoGuia
     */
    public function setFleteMinimoGuia($fleteMinimoGuia): void
    {
        $this->fleteMinimoGuia = $fleteMinimoGuia;
    }

    /**
     * @return mixed
     */
    public function getVrPeso()
    {
        return $this->vrPeso;
    }

    /**
     * @param mixed $vrPeso
     */
    public function setVrPeso($vrPeso): void
    {
        $this->vrPeso = $vrPeso;
    }

    /**
     * @return mixed
     */
    public function getVrUnidad()
    {
        return $this->vrUnidad;
    }

    /**
     * @param mixed $vrUnidad
     */
    public function setVrUnidad($vrUnidad): void
    {
        $this->vrUnidad = $vrUnidad;
    }

    /**
     * @return mixed
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * @param mixed $clienteRel
     */
    public function setClienteRel($clienteRel): void
    {
        $this->clienteRel = $clienteRel;
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
    public function getCiudadDestinoRel()
    {
        return $this->ciudadDestinoRel;
    }

    /**
     * @param mixed $ciudadDestinoRel
     */
    public function setCiudadDestinoRel($ciudadDestinoRel): void
    {
        $this->ciudadDestinoRel = $ciudadDestinoRel;
    }


}


<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteCondicionManejoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteCondicionManejo
{
    public $infoLog = [
        "primaryKey" => "codigoCondicionManejoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoCondicionManejoPk;

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
     * @ORM\Column(name="porcentaje", type="float", options={"default" : 0})
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="minimo_unidad", type="float", options={"default" : 0})
     */
    private $minimoUnidad = 0;

    /**
     * @ORM\Column(name="minimo_despacho", type="float", options={"default" : 0})
     */
    private $minimoDespacho = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="condicionesManejosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteZona", inversedBy="condicionesManejosZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    private $zonaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="condicionesManejosCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadOrigenRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="condicionesManejosCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadDestinoRel;

    /**
     * @return mixed
     */
    public function getCodigoCondicionManejoPk()
    {
        return $this->codigoCondicionManejoPk;
    }

    /**
     * @param mixed $codigoCondicionManejoPk
     */
    public function setCodigoCondicionManejoPk($codigoCondicionManejoPk): void
    {
        $this->codigoCondicionManejoPk = $codigoCondicionManejoPk;
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
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje($porcentaje): void
    {
        $this->porcentaje = $porcentaje;
    }

    /**
     * @return mixed
     */
    public function getMinimoUnidad()
    {
        return $this->minimoUnidad;
    }

    /**
     * @param mixed $minimoUnidad
     */
    public function setMinimoUnidad($minimoUnidad): void
    {
        $this->minimoUnidad = $minimoUnidad;
    }

    /**
     * @return mixed
     */
    public function getMinimoDespacho()
    {
        return $this->minimoDespacho;
    }

    /**
     * @param mixed $minimoDespacho
     */
    public function setMinimoDespacho($minimoDespacho): void
    {
        $this->minimoDespacho = $minimoDespacho;
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


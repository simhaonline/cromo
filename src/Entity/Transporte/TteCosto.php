<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteCostoRepository")
 */
class TteCosto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoCostoPk;

    /**
     * @ORM\Column(name="codigo_cierre_fk", type="integer", nullable=true)
     */
    private $codigoCierreFk;

    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */
    private $codigoGuiaFk;

    /**
     * @ORM\Column(name="vr_costo_unidad", type="float", nullable=true)
     */
    private $vrCostoUnidad = 0;

    /**
     * @ORM\Column(name="vr_costo_peso", type="float", nullable=true)
     */
    private $vrCostoPeso = 0;

    /**
     * @ORM\Column(name="vr_costo_volumen", type="float", nullable=true)
     */
    private $vrCostoVolumen = 0;

    /**
     * @ORM\Column(name="vr_costo", type="float", nullable=true)
     */
    private $vrCosto = 0;

    /**
     * @ORM\Column(name="vr_precio", type="float", nullable=true)
     */
    private $vrPrecio = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TteCierre", inversedBy="costosCierreRel")
     * @ORM\JoinColumn(name="codigo_cierre_fk", referencedColumnName="codigo_cierre_pk")
     */
    private $cierreRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteGuia", inversedBy="costosGuiaRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    private $guiaRel;

    /**
     * @return mixed
     */
    public function getCodigoCostoPk()
    {
        return $this->codigoCostoPk;
    }

    /**
     * @param mixed $codigoCostoPk
     */
    public function setCodigoCostoPk($codigoCostoPk): void
    {
        $this->codigoCostoPk = $codigoCostoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoGuiaFk()
    {
        return $this->codigoGuiaFk;
    }

    /**
     * @param mixed $codigoGuiaFk
     */
    public function setCodigoGuiaFk($codigoGuiaFk): void
    {
        $this->codigoGuiaFk = $codigoGuiaFk;
    }

    /**
     * @return mixed
     */
    public function getVrCostoUnidad()
    {
        return $this->vrCostoUnidad;
    }

    /**
     * @param mixed $vrCostoUnidad
     */
    public function setVrCostoUnidad($vrCostoUnidad): void
    {
        $this->vrCostoUnidad = $vrCostoUnidad;
    }

    /**
     * @return mixed
     */
    public function getVrCostoPeso()
    {
        return $this->vrCostoPeso;
    }

    /**
     * @param mixed $vrCostoPeso
     */
    public function setVrCostoPeso($vrCostoPeso): void
    {
        $this->vrCostoPeso = $vrCostoPeso;
    }

    /**
     * @return mixed
     */
    public function getVrCostoVolumen()
    {
        return $this->vrCostoVolumen;
    }

    /**
     * @param mixed $vrCostoVolumen
     */
    public function setVrCostoVolumen($vrCostoVolumen): void
    {
        $this->vrCostoVolumen = $vrCostoVolumen;
    }

    /**
     * @return mixed
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * @param mixed $vrCosto
     */
    public function setVrCosto($vrCosto): void
    {
        $this->vrCosto = $vrCosto;
    }

    /**
     * @return mixed
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * @param mixed $vrPrecio
     */
    public function setVrPrecio($vrPrecio): void
    {
        $this->vrPrecio = $vrPrecio;
    }

    /**
     * @return mixed
     */
    public function getGuiaRel()
    {
        return $this->guiaRel;
    }

    /**
     * @param mixed $guiaRel
     */
    public function setGuiaRel($guiaRel): void
    {
        $this->guiaRel = $guiaRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoCierreFk()
    {
        return $this->codigoCierreFk;
    }

    /**
     * @param mixed $codigoCierreFk
     */
    public function setCodigoCierreFk($codigoCierreFk): void
    {
        $this->codigoCierreFk = $codigoCierreFk;
    }

    /**
     * @return mixed
     */
    public function getCierreRel()
    {
        return $this->cierreRel;
    }

    /**
     * @param mixed $cierreRel
     */
    public function setCierreRel($cierreRel): void
    {
        $this->cierreRel = $cierreRel;
    }



}


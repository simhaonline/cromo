<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoDetalleRepository")
 */
class TteDespachoDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoDespachoDetallePk;

    /**
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */
    private $codigoDespachoFk;

    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */
    private $codigoGuiaFk;

    /**
     * @ORM\Column(name="unidades", type="float")
     */
    private $unidades = 0;

    /**
     * @ORM\Column(name="peso_real", type="float")
     */
    private $pesoReal = 0;

    /**
     * @ORM\Column(name="peso_volumen", type="float")
     */
    private $pesoVolumen = 0;

    /**
     * @ORM\Column(name="vr_declara", type="float")
     */
    private $vrDeclara = 0;

    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_manejo", type="float")
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="vr_recaudo", type="float")
     */
    private $vrRecaudo = 0;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteDespacho", inversedBy="despachosDetallesDespachoRel")
     * @ORM\JoinColumn(name="codigo_despacho_fk", referencedColumnName="codigo_despacho_pk")
     */
    private $despachoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteGuia", inversedBy="despachosDetallesGuiaRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    private $guiaRel;

    /**
     * @return mixed
     */
    public function getCodigoDespachoDetallePk()
    {
        return $this->codigoDespachoDetallePk;
    }

    /**
     * @param mixed $codigoDespachoDetallePk
     */
    public function setCodigoDespachoDetallePk($codigoDespachoDetallePk): void
    {
        $this->codigoDespachoDetallePk = $codigoDespachoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoFk()
    {
        return $this->codigoDespachoFk;
    }

    /**
     * @param mixed $codigoDespachoFk
     */
    public function setCodigoDespachoFk($codigoDespachoFk): void
    {
        $this->codigoDespachoFk = $codigoDespachoFk;
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
    public function getUnidades()
    {
        return $this->unidades;
    }

    /**
     * @param mixed $unidades
     */
    public function setUnidades($unidades): void
    {
        $this->unidades = $unidades;
    }

    /**
     * @return mixed
     */
    public function getPesoReal()
    {
        return $this->pesoReal;
    }

    /**
     * @param mixed $pesoReal
     */
    public function setPesoReal($pesoReal): void
    {
        $this->pesoReal = $pesoReal;
    }

    /**
     * @return mixed
     */
    public function getPesoVolumen()
    {
        return $this->pesoVolumen;
    }

    /**
     * @param mixed $pesoVolumen
     */
    public function setPesoVolumen($pesoVolumen): void
    {
        $this->pesoVolumen = $pesoVolumen;
    }

    /**
     * @return mixed
     */
    public function getVrDeclara()
    {
        return $this->vrDeclara;
    }

    /**
     * @param mixed $vrDeclara
     */
    public function setVrDeclara($vrDeclara): void
    {
        $this->vrDeclara = $vrDeclara;
    }

    /**
     * @return mixed
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * @param mixed $vrFlete
     */
    public function setVrFlete($vrFlete): void
    {
        $this->vrFlete = $vrFlete;
    }

    /**
     * @return mixed
     */
    public function getVrManejo()
    {
        return $this->vrManejo;
    }

    /**
     * @param mixed $vrManejo
     */
    public function setVrManejo($vrManejo): void
    {
        $this->vrManejo = $vrManejo;
    }

    /**
     * @return mixed
     */
    public function getVrRecaudo()
    {
        return $this->vrRecaudo;
    }

    /**
     * @param mixed $vrRecaudo
     */
    public function setVrRecaudo($vrRecaudo): void
    {
        $this->vrRecaudo = $vrRecaudo;
    }

    /**
     * @return mixed
     */
    public function getDespachoRel()
    {
        return $this->despachoRel;
    }

    /**
     * @param mixed $despachoRel
     */
    public function setDespachoRel($despachoRel): void
    {
        $this->despachoRel = $despachoRel;
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



}


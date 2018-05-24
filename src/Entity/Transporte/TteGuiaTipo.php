<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteGuiaTipoRepository")
 */
class TteGuiaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoGuiaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="factura", type="boolean", nullable=true)
     */
    private $factura = false;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="codigo_factura_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoFacturaTipoFk;

    /**
     * @ORM\Column(name="exige_numero", type="boolean", nullable=true)
     */
    private $exigeNumero = false;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true, options={"default" : 0})
     */
    private $orden = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TteFacturaTipo", inversedBy="guiasTiposFacturaTipoRel")
     * @ORM\JoinColumn(name="codigo_factura_tipo_fk", referencedColumnName="codigo_factura_tipo_pk")
     */
    private $facturaTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="guiaTipoRel")
     */
    protected $guiasGuiaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoGuiaTipoPk()
    {
        return $this->codigoGuiaTipoPk;
    }

    /**
     * @param mixed $codigoGuiaTipoPk
     */
    public function setCodigoGuiaTipoPk($codigoGuiaTipoPk): void
    {
        $this->codigoGuiaTipoPk = $codigoGuiaTipoPk;
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
    public function getGuiasGuiaTipoRel()
    {
        return $this->guiasGuiaTipoRel;
    }

    /**
     * @param mixed $guiasGuiaTipoRel
     */
    public function setGuiasGuiaTipoRel($guiasGuiaTipoRel): void
    {
        $this->guiasGuiaTipoRel = $guiasGuiaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * @param mixed $factura
     */
    public function setFactura($factura): void
    {
        $this->factura = $factura;
    }

    /**
     * @return mixed
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaTipoFk()
    {
        return $this->codigoFacturaTipoFk;
    }

    /**
     * @param mixed $codigoFacturaTipoFk
     */
    public function setCodigoFacturaTipoFk($codigoFacturaTipoFk): void
    {
        $this->codigoFacturaTipoFk = $codigoFacturaTipoFk;
    }

    /**
     * @return mixed
     */
    public function getFacturaTipoRel()
    {
        return $this->facturaTipoRel;
    }

    /**
     * @param mixed $facturaTipoRel
     */
    public function setFacturaTipoRel($facturaTipoRel): void
    {
        $this->facturaTipoRel = $facturaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getExigeNumero()
    {
        return $this->exigeNumero;
    }

    /**
     * @param mixed $exigeNumero
     */
    public function setExigeNumero($exigeNumero): void
    {
        $this->exigeNumero = $exigeNumero;
    }

    /**
     * @return mixed
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     */
    public function setOrden($orden): void
    {
        $this->orden = $orden;
    }



}


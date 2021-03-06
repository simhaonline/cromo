<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteFacturaDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteFacturaDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoFacturaDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoFacturaDetallePk;

    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer", nullable=true)
     */
    private $codigoFacturaFk;

    /**
     * @ORM\Column(name="codigo_factura_detalle_fk", type="integer", nullable=true)
     */
    private $codigoFacturaDetalleFk;

    /**
     * @ORM\Column(name="codigo_factura_planilla_fk", type="integer", nullable=true)
     */
    private $codigoFacturaPlanillaFk;

    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */
    private $codigoGuiaFk;

    /**
     * @ORM\Column(name="unidades", type="float", options={"default" : 0})
     */
    private $unidades = 0;

    /**
     * @ORM\Column(name="peso_real", type="float", options={"default" : 0})
     */
    private $pesoReal = 0;

    /**
     * @ORM\Column(name="peso_volumen", type="float", options={"default" : 0})
     */
    private $pesoVolumen = 0;

    /**
     * @ORM\Column(name="peso_facturado", type="float", options={"default" : 0})
     */
    private $pesoFacturado = 0;

    /**
     * @ORM\Column(name="vr_declara", type="float", options={"default" : 0})
     */
    private $vrDeclara = 0;

    /**
     * @ORM\Column(name="vr_flete", type="float", options={"default" : 0})
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_manejo", type="float", options={"default" : 0})
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="codigo_impuesto_retencion_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoRetencionFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteFactura", inversedBy="facturasDetallesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    private $facturaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteFacturaDetalle", inversedBy="facturasDetallesFacturaDetalleRel")
     * @ORM\JoinColumn(name="codigo_factura_detalle_fk", referencedColumnName="codigo_factura_detalle_pk")
     */
    private $facturaDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteFacturaPlanilla", inversedBy="facturasDetallesFacturaPlanillaRel")
     * @ORM\JoinColumn(name="codigo_factura_planilla_fk", referencedColumnName="codigo_factura_planilla_pk")
     */
    private $facturaPlanillaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteGuia", inversedBy="facturasDetallesGuiaRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    private $guiaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaDetalle", mappedBy="facturaDetalleRel")
     */
    protected $facturasDetallesFacturaDetalleRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFacturaDetalleReliquidar", mappedBy="facturaDetalleRel")
     */
    protected $facturasDetallesReliquidarFacturaDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaDetallePk()
    {
        return $this->codigoFacturaDetallePk;
    }

    /**
     * @param mixed $codigoFacturaDetallePk
     */
    public function setCodigoFacturaDetallePk($codigoFacturaDetallePk): void
    {
        $this->codigoFacturaDetallePk = $codigoFacturaDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * @param mixed $codigoFacturaFk
     */
    public function setCodigoFacturaFk($codigoFacturaFk): void
    {
        $this->codigoFacturaFk = $codigoFacturaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaDetalleFk()
    {
        return $this->codigoFacturaDetalleFk;
    }

    /**
     * @param mixed $codigoFacturaDetalleFk
     */
    public function setCodigoFacturaDetalleFk($codigoFacturaDetalleFk): void
    {
        $this->codigoFacturaDetalleFk = $codigoFacturaDetalleFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaPlanillaFk()
    {
        return $this->codigoFacturaPlanillaFk;
    }

    /**
     * @param mixed $codigoFacturaPlanillaFk
     */
    public function setCodigoFacturaPlanillaFk($codigoFacturaPlanillaFk): void
    {
        $this->codigoFacturaPlanillaFk = $codigoFacturaPlanillaFk;
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
    public function getCodigoImpuestoRetencionFk()
    {
        return $this->codigoImpuestoRetencionFk;
    }

    /**
     * @param mixed $codigoImpuestoRetencionFk
     */
    public function setCodigoImpuestoRetencionFk($codigoImpuestoRetencionFk): void
    {
        $this->codigoImpuestoRetencionFk = $codigoImpuestoRetencionFk;
    }

    /**
     * @return mixed
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * @param mixed $facturaRel
     */
    public function setFacturaRel($facturaRel): void
    {
        $this->facturaRel = $facturaRel;
    }

    /**
     * @return mixed
     */
    public function getFacturaDetalleRel()
    {
        return $this->facturaDetalleRel;
    }

    /**
     * @param mixed $facturaDetalleRel
     */
    public function setFacturaDetalleRel($facturaDetalleRel): void
    {
        $this->facturaDetalleRel = $facturaDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getFacturaPlanillaRel()
    {
        return $this->facturaPlanillaRel;
    }

    /**
     * @param mixed $facturaPlanillaRel
     */
    public function setFacturaPlanillaRel($facturaPlanillaRel): void
    {
        $this->facturaPlanillaRel = $facturaPlanillaRel;
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
    public function getFacturasDetallesFacturaDetalleRel()
    {
        return $this->facturasDetallesFacturaDetalleRel;
    }

    /**
     * @param mixed $facturasDetallesFacturaDetalleRel
     */
    public function setFacturasDetallesFacturaDetalleRel($facturasDetallesFacturaDetalleRel): void
    {
        $this->facturasDetallesFacturaDetalleRel = $facturasDetallesFacturaDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasDetallesReliquidarFacturaDetalleRel()
    {
        return $this->facturasDetallesReliquidarFacturaDetalleRel;
    }

    /**
     * @param mixed $facturasDetallesReliquidarFacturaDetalleRel
     */
    public function setFacturasDetallesReliquidarFacturaDetalleRel($facturasDetallesReliquidarFacturaDetalleRel): void
    {
        $this->facturasDetallesReliquidarFacturaDetalleRel = $facturasDetallesReliquidarFacturaDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getPesoFacturado()
    {
        return $this->pesoFacturado;
    }

    /**
     * @param mixed $pesoFacturado
     */
    public function setPesoFacturado($pesoFacturado): void
    {
        $this->pesoFacturado = $pesoFacturado;
    }



}


<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteFacturaTipoRepository")
 */
class TteFacturaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoFacturaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="resolucion_facturacion", type="text", nullable=true)
     */
    private $resolucionFacturacion;

    /**
     * @ORM\Column(name="guia_factura", type="boolean", nullable=true, options={"default" : false})
     */
    private $guiaFactura = false;

    /**
     * @ORM\Column(name="prefijo", type="string", length=5, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\Column(name="codigo_factura_clase_fk", type="string", length=2, nullable=true)
     */
    private $codigoFacturaClaseFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaCobrarTipoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_flete_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoFleteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_manejo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoManejoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cliente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaClienteFk;

    /**
     * @ORM\OneToMany(targetEntity="TteFactura", mappedBy="facturaTipoRel")
     */
    protected $facturasFacturaTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuiaTipo", mappedBy="facturaTipoRel")
     */
    protected $guiasTiposFacturaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaTipoPk()
    {
        return $this->codigoFacturaTipoPk;
    }

    /**
     * @param mixed $codigoFacturaTipoPk
     */
    public function setCodigoFacturaTipoPk($codigoFacturaTipoPk): void
    {
        $this->codigoFacturaTipoPk = $codigoFacturaTipoPk;
    }



}


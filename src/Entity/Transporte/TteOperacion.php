<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteOperacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteOperacion
{
    public $infoLog = [
        "primaryKey" => "codigoOperacionPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoOperacionPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_flete_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoFleteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_manejo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIngresoManejoFk;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_despacho_flete_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDespachoFleteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_despacho_retencion_fuente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDespachoRetencionFuenteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_despacho_industria_comercio_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDespachoIndustriaComercioFk;

    /**
     * @ORM\Column(name="codigo_cuenta_despacho_seguridad_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDespachoSeguridadFk;

    /**
     * @ORM\Column(name="codigo_cuenta_despacho_cargue_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDespachoCargueFk;

    /**
     * @ORM\Column(name="codigo_cuenta_despacho_estampilla_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDespachoEstampillaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_despacho_papeleria_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDespachoPapeleriaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_despacho_anticipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDespachoAnticipoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_despacho_pagar_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDespachoPagarFk;

    /**
     * @ORM\Column(name="retencion_industria_comercio", type="boolean", nullable=true, options={"default" : true})
     */
    private $retencionIndustriaComercio = true;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="operacionesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="operacionIngresoRel")
     */
    protected $guiasOperacionIngresoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="operacionCargoRel")
     */
    protected $guiasOperacionCargoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogida", mappedBy="operacionRel")
     */
    protected $recogidasOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogidaProgramada", mappedBy="operacionRel")
     */
    protected $recogidasProgramadasOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespachoRecogida", mappedBy="operacionRel")
     */
    protected $despachosRecogidasOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRutaRecogida", mappedBy="operacionRel")
     */
    protected $rutasRecogidasOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="operacionRel")
     */
    protected $despachosOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seguridad\Usuario", mappedBy="operacionRel")
     */
    protected $usuariosOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecibo", mappedBy="operacionRel")
     */
    protected $recibosOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteFactura", mappedBy="operacionRel")
     */
    protected $facturasOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCliente", mappedBy="operacionRel")
     */
    protected $clientesOperacionRel;

    /**
     * @return mixed
     */
    public function getCodigoOperacionPk()
    {
        return $this->codigoOperacionPk;
    }

    /**
     * @param mixed $codigoOperacionPk
     */
    public function setCodigoOperacionPk( $codigoOperacionPk ): void
    {
        $this->codigoOperacionPk = $codigoOperacionPk;
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
    public function setNombre( $nombre ): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * @param mixed $codigoCiudadFk
     */
    public function setCodigoCiudadFk( $codigoCiudadFk ): void
    {
        $this->codigoCiudadFk = $codigoCiudadFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaIngresoFleteFk()
    {
        return $this->codigoCuentaIngresoFleteFk;
    }

    /**
     * @param mixed $codigoCuentaIngresoFleteFk
     */
    public function setCodigoCuentaIngresoFleteFk( $codigoCuentaIngresoFleteFk ): void
    {
        $this->codigoCuentaIngresoFleteFk = $codigoCuentaIngresoFleteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaIngresoManejoFk()
    {
        return $this->codigoCuentaIngresoManejoFk;
    }

    /**
     * @param mixed $codigoCuentaIngresoManejoFk
     */
    public function setCodigoCuentaIngresoManejoFk( $codigoCuentaIngresoManejoFk ): void
    {
        $this->codigoCuentaIngresoManejoFk = $codigoCuentaIngresoManejoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * @param mixed $codigoCentroCostoFk
     */
    public function setCodigoCentroCostoFk( $codigoCentroCostoFk ): void
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;
    }

    /**
     * @return mixed
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * @param mixed $ciudadRel
     */
    public function setCiudadRel( $ciudadRel ): void
    {
        $this->ciudadRel = $ciudadRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasOperacionIngresoRel()
    {
        return $this->guiasOperacionIngresoRel;
    }

    /**
     * @param mixed $guiasOperacionIngresoRel
     */
    public function setGuiasOperacionIngresoRel( $guiasOperacionIngresoRel ): void
    {
        $this->guiasOperacionIngresoRel = $guiasOperacionIngresoRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasOperacionCargoRel()
    {
        return $this->guiasOperacionCargoRel;
    }

    /**
     * @param mixed $guiasOperacionCargoRel
     */
    public function setGuiasOperacionCargoRel( $guiasOperacionCargoRel ): void
    {
        $this->guiasOperacionCargoRel = $guiasOperacionCargoRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasOperacionRel()
    {
        return $this->recogidasOperacionRel;
    }

    /**
     * @param mixed $recogidasOperacionRel
     */
    public function setRecogidasOperacionRel( $recogidasOperacionRel ): void
    {
        $this->recogidasOperacionRel = $recogidasOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasProgramadasOperacionRel()
    {
        return $this->recogidasProgramadasOperacionRel;
    }

    /**
     * @param mixed $recogidasProgramadasOperacionRel
     */
    public function setRecogidasProgramadasOperacionRel( $recogidasProgramadasOperacionRel ): void
    {
        $this->recogidasProgramadasOperacionRel = $recogidasProgramadasOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasOperacionRel()
    {
        return $this->despachosRecogidasOperacionRel;
    }

    /**
     * @param mixed $despachosRecogidasOperacionRel
     */
    public function setDespachosRecogidasOperacionRel( $despachosRecogidasOperacionRel ): void
    {
        $this->despachosRecogidasOperacionRel = $despachosRecogidasOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getRutasRecogidasOperacionRel()
    {
        return $this->rutasRecogidasOperacionRel;
    }

    /**
     * @param mixed $rutasRecogidasOperacionRel
     */
    public function setRutasRecogidasOperacionRel( $rutasRecogidasOperacionRel ): void
    {
        $this->rutasRecogidasOperacionRel = $rutasRecogidasOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosOperacionRel()
    {
        return $this->despachosOperacionRel;
    }

    /**
     * @param mixed $despachosOperacionRel
     */
    public function setDespachosOperacionRel( $despachosOperacionRel ): void
    {
        $this->despachosOperacionRel = $despachosOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getUsuariosOperacionRel()
    {
        return $this->usuariosOperacionRel;
    }

    /**
     * @param mixed $usuariosOperacionRel
     */
    public function setUsuariosOperacionRel( $usuariosOperacionRel ): void
    {
        $this->usuariosOperacionRel = $usuariosOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getRecibosOperacionRel()
    {
        return $this->recibosOperacionRel;
    }

    /**
     * @param mixed $recibosOperacionRel
     */
    public function setRecibosOperacionRel( $recibosOperacionRel ): void
    {
        $this->recibosOperacionRel = $recibosOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasOperacionRel()
    {
        return $this->facturasOperacionRel;
    }

    /**
     * @param mixed $facturasOperacionRel
     */
    public function setFacturasOperacionRel( $facturasOperacionRel ): void
    {
        $this->facturasOperacionRel = $facturasOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getClientesOperacionRel()
    {
        return $this->clientesOperacionRel;
    }

    /**
     * @param mixed $clientesOperacionRel
     */
    public function setClientesOperacionRel($clientesOperacionRel): void
    {
        $this->clientesOperacionRel = $clientesOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDespachoFleteFk()
    {
        return $this->codigoCuentaDespachoFleteFk;
    }

    /**
     * @param mixed $codigoCuentaDespachoFleteFk
     */
    public function setCodigoCuentaDespachoFleteFk($codigoCuentaDespachoFleteFk): void
    {
        $this->codigoCuentaDespachoFleteFk = $codigoCuentaDespachoFleteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDespachoRetencionFuenteFk()
    {
        return $this->codigoCuentaDespachoRetencionFuenteFk;
    }

    /**
     * @param mixed $codigoCuentaDespachoRetencionFuenteFk
     */
    public function setCodigoCuentaDespachoRetencionFuenteFk($codigoCuentaDespachoRetencionFuenteFk): void
    {
        $this->codigoCuentaDespachoRetencionFuenteFk = $codigoCuentaDespachoRetencionFuenteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDespachoIndustriaComercioFk()
    {
        return $this->codigoCuentaDespachoIndustriaComercioFk;
    }

    /**
     * @param mixed $codigoCuentaDespachoIndustriaComercioFk
     */
    public function setCodigoCuentaDespachoIndustriaComercioFk($codigoCuentaDespachoIndustriaComercioFk): void
    {
        $this->codigoCuentaDespachoIndustriaComercioFk = $codigoCuentaDespachoIndustriaComercioFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDespachoSeguridadFk()
    {
        return $this->codigoCuentaDespachoSeguridadFk;
    }

    /**
     * @param mixed $codigoCuentaDespachoSeguridadFk
     */
    public function setCodigoCuentaDespachoSeguridadFk($codigoCuentaDespachoSeguridadFk): void
    {
        $this->codigoCuentaDespachoSeguridadFk = $codigoCuentaDespachoSeguridadFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDespachoCargueFk()
    {
        return $this->codigoCuentaDespachoCargueFk;
    }

    /**
     * @param mixed $codigoCuentaDespachoCargueFk
     */
    public function setCodigoCuentaDespachoCargueFk($codigoCuentaDespachoCargueFk): void
    {
        $this->codigoCuentaDespachoCargueFk = $codigoCuentaDespachoCargueFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDespachoEstampillaFk()
    {
        return $this->codigoCuentaDespachoEstampillaFk;
    }

    /**
     * @param mixed $codigoCuentaDespachoEstampillaFk
     */
    public function setCodigoCuentaDespachoEstampillaFk($codigoCuentaDespachoEstampillaFk): void
    {
        $this->codigoCuentaDespachoEstampillaFk = $codigoCuentaDespachoEstampillaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDespachoPapeleriaFk()
    {
        return $this->codigoCuentaDespachoPapeleriaFk;
    }

    /**
     * @param mixed $codigoCuentaDespachoPapeleriaFk
     */
    public function setCodigoCuentaDespachoPapeleriaFk($codigoCuentaDespachoPapeleriaFk): void
    {
        $this->codigoCuentaDespachoPapeleriaFk = $codigoCuentaDespachoPapeleriaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDespachoAnticipoFk()
    {
        return $this->codigoCuentaDespachoAnticipoFk;
    }

    /**
     * @param mixed $codigoCuentaDespachoAnticipoFk
     */
    public function setCodigoCuentaDespachoAnticipoFk($codigoCuentaDespachoAnticipoFk): void
    {
        $this->codigoCuentaDespachoAnticipoFk = $codigoCuentaDespachoAnticipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDespachoPagarFk()
    {
        return $this->codigoCuentaDespachoPagarFk;
    }

    /**
     * @param mixed $codigoCuentaDespachoPagarFk
     */
    public function setCodigoCuentaDespachoPagarFk($codigoCuentaDespachoPagarFk): void
    {
        $this->codigoCuentaDespachoPagarFk = $codigoCuentaDespachoPagarFk;
    }

    /**
     * @return mixed
     */
    public function getRetencionIndustriaComercio()
    {
        return $this->retencionIndustriaComercio;
    }

    /**
     * @param mixed $retencionIndustriaComercio
     */
    public function setRetencionIndustriaComercio($retencionIndustriaComercio): void
    {
        $this->retencionIndustriaComercio = $retencionIndustriaComercio;
    }





}


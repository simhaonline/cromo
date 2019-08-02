<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoRecogidaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteDespachoRecogidaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoDespachoRecogidaTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoDespachoRecogidaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="genera_monitoreo", type="boolean", nullable=true, options={"default" : false})
     */
    private $generaMonitoreo = false;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_flete_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFleteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_fuente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionFuenteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_industria_comercio_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaIndustriaComercioFk;

    /**
     * @ORM\Column(name="codigo_cuenta_seguridad_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaSeguridadFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cargue_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaCargueFk;

    /**
     * @ORM\Column(name="codigo_cuenta_estampilla_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaEstampillaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_papeleria_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaPapeleriaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_anticipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaAnticipoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_pagar_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaPagarFk;

    /**
     * @ORM\Column(name="contabilizar", type="boolean", nullable=true,options={"default":false})
     */
    private $contabilizar = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespachoRecogida", mappedBy="despachoRecogidaTipoRel")
     */
    protected $despachosRecogidasDespachoRecogidaTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteIntermediacionRecogida", mappedBy="despachoRecogidaTipoRel")
     */
    protected $intermediacionesRecogidasDespachoRecogidaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoDespachoRecogidaTipoPk()
    {
        return $this->codigoDespachoRecogidaTipoPk;
    }

    /**
     * @param mixed $codigoDespachoRecogidaTipoPk
     */
    public function setCodigoDespachoRecogidaTipoPk($codigoDespachoRecogidaTipoPk): void
    {
        $this->codigoDespachoRecogidaTipoPk = $codigoDespachoRecogidaTipoPk;
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
    public function getGeneraMonitoreo()
    {
        return $this->generaMonitoreo;
    }

    /**
     * @param mixed $generaMonitoreo
     */
    public function setGeneraMonitoreo($generaMonitoreo): void
    {
        $this->generaMonitoreo = $generaMonitoreo;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasDespachoRecogidaTipoRel()
    {
        return $this->despachosRecogidasDespachoRecogidaTipoRel;
    }

    /**
     * @param mixed $despachosRecogidasDespachoRecogidaTipoRel
     */
    public function setDespachosRecogidasDespachoRecogidaTipoRel($despachosRecogidasDespachoRecogidaTipoRel): void
    {
        $this->despachosRecogidasDespachoRecogidaTipoRel = $despachosRecogidasDespachoRecogidaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * @param mixed $codigoComprobanteFk
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk): void
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaFleteFk()
    {
        return $this->codigoCuentaFleteFk;
    }

    /**
     * @param mixed $codigoCuentaFleteFk
     */
    public function setCodigoCuentaFleteFk($codigoCuentaFleteFk): void
    {
        $this->codigoCuentaFleteFk = $codigoCuentaFleteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaRetencionFuenteFk()
    {
        return $this->codigoCuentaRetencionFuenteFk;
    }

    /**
     * @param mixed $codigoCuentaRetencionFuenteFk
     */
    public function setCodigoCuentaRetencionFuenteFk($codigoCuentaRetencionFuenteFk): void
    {
        $this->codigoCuentaRetencionFuenteFk = $codigoCuentaRetencionFuenteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaIndustriaComercioFk()
    {
        return $this->codigoCuentaIndustriaComercioFk;
    }

    /**
     * @param mixed $codigoCuentaIndustriaComercioFk
     */
    public function setCodigoCuentaIndustriaComercioFk($codigoCuentaIndustriaComercioFk): void
    {
        $this->codigoCuentaIndustriaComercioFk = $codigoCuentaIndustriaComercioFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaSeguridadFk()
    {
        return $this->codigoCuentaSeguridadFk;
    }

    /**
     * @param mixed $codigoCuentaSeguridadFk
     */
    public function setCodigoCuentaSeguridadFk($codigoCuentaSeguridadFk): void
    {
        $this->codigoCuentaSeguridadFk = $codigoCuentaSeguridadFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCargueFk()
    {
        return $this->codigoCuentaCargueFk;
    }

    /**
     * @param mixed $codigoCuentaCargueFk
     */
    public function setCodigoCuentaCargueFk($codigoCuentaCargueFk): void
    {
        $this->codigoCuentaCargueFk = $codigoCuentaCargueFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaEstampillaFk()
    {
        return $this->codigoCuentaEstampillaFk;
    }

    /**
     * @param mixed $codigoCuentaEstampillaFk
     */
    public function setCodigoCuentaEstampillaFk($codigoCuentaEstampillaFk): void
    {
        $this->codigoCuentaEstampillaFk = $codigoCuentaEstampillaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaPapeleriaFk()
    {
        return $this->codigoCuentaPapeleriaFk;
    }

    /**
     * @param mixed $codigoCuentaPapeleriaFk
     */
    public function setCodigoCuentaPapeleriaFk($codigoCuentaPapeleriaFk): void
    {
        $this->codigoCuentaPapeleriaFk = $codigoCuentaPapeleriaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaAnticipoFk()
    {
        return $this->codigoCuentaAnticipoFk;
    }

    /**
     * @param mixed $codigoCuentaAnticipoFk
     */
    public function setCodigoCuentaAnticipoFk($codigoCuentaAnticipoFk): void
    {
        $this->codigoCuentaAnticipoFk = $codigoCuentaAnticipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaPagarFk()
    {
        return $this->codigoCuentaPagarFk;
    }

    /**
     * @param mixed $codigoCuentaPagarFk
     */
    public function setCodigoCuentaPagarFk($codigoCuentaPagarFk): void
    {
        $this->codigoCuentaPagarFk = $codigoCuentaPagarFk;
    }

    /**
     * @return mixed
     */
    public function getContabilizar()
    {
        return $this->contabilizar;
    }

    /**
     * @param mixed $contabilizar
     */
    public function setContabilizar( $contabilizar ): void
    {
        $this->contabilizar = $contabilizar;
    }

    /**
     * @return mixed
     */
    public function getIntermediacionesRecogidasDespachoRecogidaTipoRel()
    {
        return $this->intermediacionesRecogidasDespachoRecogidaTipoRel;
    }

    /**
     * @param mixed $intermediacionesRecogidasDespachoRecogidaTipoRel
     */
    public function setIntermediacionesRecogidasDespachoRecogidaTipoRel($intermediacionesRecogidasDespachoRecogidaTipoRel): void
    {
        $this->intermediacionesRecogidasDespachoRecogidaTipoRel = $intermediacionesRecogidasDespachoRecogidaTipoRel;
    }



}


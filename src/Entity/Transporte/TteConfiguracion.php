<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteConfiguracionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteConfiguracion
{
    public $infoLog = [
        "primaryKey" => "codigoConfiguracionPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoConfiguracionPk;

    /**
     * @ORM\Column(name="usuario_rndc", type="string", length=50, nullable=true)
     */
    private $usuarioRndc;

    /**
     * @ORM\Column(name="codigo_operador_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperadorFk;

    /**
     * @ORM\Column(name="clave_rndc", type="string", length=50, nullable=true)
     */
    private $claveRndc;

    /**
     * @ORM\Column(name="empresa_rndc", type="string", length=50, nullable=true)
     */
    private $empresaRndc;

    /**
     * @ORM\Column(name="numero_poliza", type="string", length=50, nullable=true)
     */
    private $numeroPoliza;

    /**
     * @ORM\Column(name="fecha_vence_poliza", type="date", nullable=true)
     */
    private $fechaVencePoliza;

    /**
     * @ORM\Column(name="numero_identificacion_aseguradora", type="string", length=50, nullable=true)
     */
    private $numeroIdentificacionAseguradora;

    /**
     * @ORM\Column(name="codigo_precio_general_fk", type="integer", nullable=true)
     */
    private $codigoPrecioGeneralFk;

    /**
     * @ORM\Column(name="codigo_condicion_general_fk", type="integer", nullable=true)
     */
    private $codigoCondicionGeneralFk;

    /**
     * @ORM\Column(name="vr_base_retencion_fuente", type="float", options={"default" : 0})
     */
    private $vrBaseRetencionFuente = 0;

    /**
     * @ORM\Column(name="porcentaje_retencion_fuente", type="float", options={"default" : 0})
     */
    private $porcentajeRetencionFuente = 0;

    /**
     * @ORM\Column(name="porcentaje_industria_comercio", type="float", options={"default" : 0})
     */
    private $porcentajeIndustriaComercio = 0;

    /**
     * @ORM\Column(name="codigo_impuesto_retencion_transporte_fk", type="string", length=3, nullable=true)
     */
    private $codigoImpuestoRetencionTransporteFk;

    /**
     * @ORM\Column(name="codigo_comprobante_intermediacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteIntermediacionFk;

    /**
     * @ORM\Column(name="codigo_formato", type="integer")
     */
    private $codigoFormato = 0;

    /**
     * @ORM\Column(name="codigo_formato_guia", type="string", length=2, nullable=true)
     */
    private $codigoFormatoGuia = 0;

    /**
     * @ORM\Column(name="numero_unico_guia", type="boolean", options={"default" : false})
     */
    private $numeroUnicoGuia = false;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * @param mixed $codigoConfiguracionPk
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk): void
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;
    }

    /**
     * @return mixed
     */
    public function getUsuarioRndc()
    {
        return $this->usuarioRndc;
    }

    /**
     * @param mixed $usuarioRndc
     */
    public function setUsuarioRndc($usuarioRndc): void
    {
        $this->usuarioRndc = $usuarioRndc;
    }

    /**
     * @return mixed
     */
    public function getCodigoOperadorFk()
    {
        return $this->codigoOperadorFk;
    }

    /**
     * @param mixed $codigoOperadorFk
     */
    public function setCodigoOperadorFk($codigoOperadorFk): void
    {
        $this->codigoOperadorFk = $codigoOperadorFk;
    }

    /**
     * @return mixed
     */
    public function getClaveRndc()
    {
        return $this->claveRndc;
    }

    /**
     * @param mixed $claveRndc
     */
    public function setClaveRndc($claveRndc): void
    {
        $this->claveRndc = $claveRndc;
    }

    /**
     * @return mixed
     */
    public function getEmpresaRndc()
    {
        return $this->empresaRndc;
    }

    /**
     * @param mixed $empresaRndc
     */
    public function setEmpresaRndc($empresaRndc): void
    {
        $this->empresaRndc = $empresaRndc;
    }

    /**
     * @return mixed
     */
    public function getNumeroPoliza()
    {
        return $this->numeroPoliza;
    }

    /**
     * @param mixed $numeroPoliza
     */
    public function setNumeroPoliza($numeroPoliza): void
    {
        $this->numeroPoliza = $numeroPoliza;
    }

    /**
     * @return mixed
     */
    public function getFechaVencePoliza()
    {
        return $this->fechaVencePoliza;
    }

    /**
     * @param mixed $fechaVencePoliza
     */
    public function setFechaVencePoliza($fechaVencePoliza): void
    {
        $this->fechaVencePoliza = $fechaVencePoliza;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacionAseguradora()
    {
        return $this->numeroIdentificacionAseguradora;
    }

    /**
     * @param mixed $numeroIdentificacionAseguradora
     */
    public function setNumeroIdentificacionAseguradora($numeroIdentificacionAseguradora): void
    {
        $this->numeroIdentificacionAseguradora = $numeroIdentificacionAseguradora;
    }

    /**
     * @return mixed
     */
    public function getCodigoPrecioGeneralFk()
    {
        return $this->codigoPrecioGeneralFk;
    }

    /**
     * @param mixed $codigoPrecioGeneralFk
     */
    public function setCodigoPrecioGeneralFk($codigoPrecioGeneralFk): void
    {
        $this->codigoPrecioGeneralFk = $codigoPrecioGeneralFk;
    }

    /**
     * @return mixed
     */
    public function getVrBaseRetencionFuente()
    {
        return $this->vrBaseRetencionFuente;
    }

    /**
     * @param mixed $vrBaseRetencionFuente
     */
    public function setVrBaseRetencionFuente($vrBaseRetencionFuente): void
    {
        $this->vrBaseRetencionFuente = $vrBaseRetencionFuente;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeRetencionFuente()
    {
        return $this->porcentajeRetencionFuente;
    }

    /**
     * @param mixed $porcentajeRetencionFuente
     */
    public function setPorcentajeRetencionFuente($porcentajeRetencionFuente): void
    {
        $this->porcentajeRetencionFuente = $porcentajeRetencionFuente;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeIndustriaComercio()
    {
        return $this->porcentajeIndustriaComercio;
    }

    /**
     * @param mixed $porcentajeIndustriaComercio
     */
    public function setPorcentajeIndustriaComercio($porcentajeIndustriaComercio): void
    {
        $this->porcentajeIndustriaComercio = $porcentajeIndustriaComercio;
    }

    /**
     * @return mixed
     */
    public function getCodigoImpuestoRetencionTransporteFk()
    {
        return $this->codigoImpuestoRetencionTransporteFk;
    }

    /**
     * @param mixed $codigoImpuestoRetencionTransporteFk
     */
    public function setCodigoImpuestoRetencionTransporteFk($codigoImpuestoRetencionTransporteFk): void
    {
        $this->codigoImpuestoRetencionTransporteFk = $codigoImpuestoRetencionTransporteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoComprobanteIntermediacionFk()
    {
        return $this->codigoComprobanteIntermediacionFk;
    }

    /**
     * @param mixed $codigoComprobanteIntermediacionFk
     */
    public function setCodigoComprobanteIntermediacionFk($codigoComprobanteIntermediacionFk): void
    {
        $this->codigoComprobanteIntermediacionFk = $codigoComprobanteIntermediacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFormato()
    {
        return $this->codigoFormato;
    }

    /**
     * @param mixed $codigoFormato
     */
    public function setCodigoFormato($codigoFormato): void
    {
        $this->codigoFormato = $codigoFormato;
    }

    /**
     * @return mixed
     */
    public function getNumeroUnicoGuia()
    {
        return $this->numeroUnicoGuia;
    }

    /**
     * @param mixed $numeroUnicoGuia
     */
    public function setNumeroUnicoGuia($numeroUnicoGuia): void
    {
        $this->numeroUnicoGuia = $numeroUnicoGuia;
    }

    /**
     * @return mixed
     */
    public function getCodigoFormatoGuia()
    {
        return $this->codigoFormatoGuia;
    }

    /**
     * @param mixed $codigoFormatoGuia
     */
    public function setCodigoFormatoGuia($codigoFormatoGuia): void
    {
        $this->codigoFormatoGuia = $codigoFormatoGuia;
    }

    /**
     * @return mixed
     */
    public function getCodigoCondicionGeneralFk()
    {
        return $this->codigoCondicionGeneralFk;
    }

    /**
     * @param mixed $codigoCondicionGeneralFk
     */
    public function setCodigoCondicionGeneralFk($codigoCondicionGeneralFk): void
    {
        $this->codigoCondicionGeneralFk = $codigoCondicionGeneralFk;
    }





}


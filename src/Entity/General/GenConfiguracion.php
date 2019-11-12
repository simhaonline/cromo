<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenConfiguracionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenConfiguracion
{
    public $infoLog = [
        "primaryKey" => "codigoConfiguracionPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer")
     */
    private $codigoConfiguracionPk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string", length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="nit", type="string", length=20, nullable=true)
     */
    private $nit;

    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=2, nullable=true)
     */
    private $digitoVerificacion;

    /**
     * @ORM\Column(name="nombre", type="string", length=90, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="ruta_temporal", type="string", length=100, nullable=true)
     */
    private $rutaTemporal;

    /**
     * @ORM\Column(name="telefono", type="string", length=25, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="logo", type="blob", nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(name="web_service_cesio_url", type="string", nullable=true)
     */
    private $webServiceCesioUrl;

    /**
     * @ORM\Column(name="web_service_galio_url", type="string", nullable=true)
     */
    private $webServiceGalioUrl;

    /**
     * @ORM\Column(name="web_service_oxigeno_url", type="string", nullable=true)
     */
    private $webServiceOxigenoUrl;

    /**
     * @ORM\Column(name="dominio", type="string", nullable=true)
     */
    private $dominio;

    /**
     * @ORM\Column(name="codigo_cliente_mesa_ayuda", type="integer", nullable=true)
     */
    private $codigoClienteMesaAyuda;

    /**
     * @ORM\Column(name="autoretencion_venta", type="boolean", options={"default" : false})
     */
    private $autoretencionVenta = false;

    /**
     * @ORM\Column(name="contabilidad_automatica", type="boolean", options={"default" : false})
     */
    private $contabilidadAutomatica = false;

    /**
     * @ORM\Column(name="codigo_cuenta_autoretencion_venta_valor_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaAutoretencionVentaValorFk;

    /**
     * @ORM\Column(name="codigo_cuenta_autoretencion_venta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaAutoretencionVentaFk;

    /**
     * @ORM\Column(name="porcentaje_autoretencion", type="float", nullable=true, options={"default" : 0})
     */
    private $porcentajeAutoretencion = 0;

    /**
     * @ORM\Column(name="version_base_datos", type="integer", options={"default" : 0})
     */
    private $versionBaseDatos = 0;

    /**
     * @ORM\Column(name="codigo_empresa", type="integer", options={"default" : 0})
     */
    private $codigoEmpresa = 0;

    /**
     * @ORM\Column(name="correo", type="string", length=50, nullable=true)
     */
    private $correo;

    /**
     * @ORM\Column(name="codigo_empresa_oxigeno", type="integer", options={"default" : null}, nullable=true)
     */
    private $codigoEmpresaOxigeno;

    /**
     * @ORM\Column(name="fe_token", type="string", length=200, nullable=true)
     */
    private $feToken;

    /**
     * @ORM\Column(name="fe_usuario", type="string", length=50, nullable=true)
     */
    private $feUsuario;

    /**
     * @ORM\Column(name="fe_clave", type="string", length=50, nullable=true)
     */
    private $feClave;

    /**
     * @ORM\Column(name="fe_codigo_empresa", type="string", length=10, nullable=true)
     */
    private $feCodigoEmpresa;

    /**
     * @ORM\Column(name="codigo_tipo_persona_fk", type="string", length=3, nullable=true)
     */
    private $codigoTipoPersonaFk;

    /**
     * @ORM\Column(name="matricula_mercantil", type="string", length=100, nullable=true)
     */
    private $matriculaMercantil;

    /**
     * @ORM\ManyToOne(targetEntity="GenCiudad", inversedBy="configuracionesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="GenIdentificacion", inversedBy="configuracionesIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_identificacion_fk", referencedColumnName="codigo_identificacion_pk")
     */
    protected $identificacionRel;

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
    public function getCodigoIdentificacionFk()
    {
        return $this->codigoIdentificacionFk;
    }

    /**
     * @param mixed $codigoIdentificacionFk
     */
    public function setCodigoIdentificacionFk($codigoIdentificacionFk): void
    {
        $this->codigoIdentificacionFk = $codigoIdentificacionFk;
    }

    /**
     * @return mixed
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * @param mixed $nit
     */
    public function setNit($nit): void
    {
        $this->nit = $nit;
    }

    /**
     * @return mixed
     */
    public function getDigitoVerificacion()
    {
        return $this->digitoVerificacion;
    }

    /**
     * @param mixed $digitoVerificacion
     */
    public function setDigitoVerificacion($digitoVerificacion): void
    {
        $this->digitoVerificacion = $digitoVerificacion;
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
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * @param mixed $codigoCiudadFk
     */
    public function setCodigoCiudadFk($codigoCiudadFk): void
    {
        $this->codigoCiudadFk = $codigoCiudadFk;
    }

    /**
     * @return mixed
     */
    public function getRutaTemporal()
    {
        return $this->rutaTemporal;
    }

    /**
     * @param mixed $rutaTemporal
     */
    public function setRutaTemporal($rutaTemporal): void
    {
        $this->rutaTemporal = $rutaTemporal;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getWebServiceCesioUrl()
    {
        return $this->webServiceCesioUrl;
    }

    /**
     * @param mixed $webServiceCesioUrl
     */
    public function setWebServiceCesioUrl($webServiceCesioUrl): void
    {
        $this->webServiceCesioUrl = $webServiceCesioUrl;
    }

    /**
     * @return mixed
     */
    public function getWebServiceGalioUrl()
    {
        return $this->webServiceGalioUrl;
    }

    /**
     * @param mixed $webServiceGalioUrl
     */
    public function setWebServiceGalioUrl($webServiceGalioUrl): void
    {
        $this->webServiceGalioUrl = $webServiceGalioUrl;
    }

    /**
     * @return mixed
     */
    public function getWebServiceOxigenoUrl()
    {
        return $this->webServiceOxigenoUrl;
    }

    /**
     * @param mixed $webServiceOxigenoUrl
     */
    public function setWebServiceOxigenoUrl($webServiceOxigenoUrl): void
    {
        $this->webServiceOxigenoUrl = $webServiceOxigenoUrl;
    }

    /**
     * @return mixed
     */
    public function getDominio()
    {
        return $this->dominio;
    }

    /**
     * @param mixed $dominio
     */
    public function setDominio($dominio): void
    {
        $this->dominio = $dominio;
    }

    /**
     * @return mixed
     */
    public function getCodigoClienteMesaAyuda()
    {
        return $this->codigoClienteMesaAyuda;
    }

    /**
     * @param mixed $codigoClienteMesaAyuda
     */
    public function setCodigoClienteMesaAyuda($codigoClienteMesaAyuda): void
    {
        $this->codigoClienteMesaAyuda = $codigoClienteMesaAyuda;
    }

    /**
     * @return mixed
     */
    public function getAutoretencionVenta()
    {
        return $this->autoretencionVenta;
    }

    /**
     * @param mixed $autoretencionVenta
     */
    public function setAutoretencionVenta($autoretencionVenta): void
    {
        $this->autoretencionVenta = $autoretencionVenta;
    }

    /**
     * @return mixed
     */
    public function getContabilidadAutomatica()
    {
        return $this->contabilidadAutomatica;
    }

    /**
     * @param mixed $contabilidadAutomatica
     */
    public function setContabilidadAutomatica($contabilidadAutomatica): void
    {
        $this->contabilidadAutomatica = $contabilidadAutomatica;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaAutoretencionVentaValorFk()
    {
        return $this->codigoCuentaAutoretencionVentaValorFk;
    }

    /**
     * @param mixed $codigoCuentaAutoretencionVentaValorFk
     */
    public function setCodigoCuentaAutoretencionVentaValorFk($codigoCuentaAutoretencionVentaValorFk): void
    {
        $this->codigoCuentaAutoretencionVentaValorFk = $codigoCuentaAutoretencionVentaValorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaAutoretencionVentaFk()
    {
        return $this->codigoCuentaAutoretencionVentaFk;
    }

    /**
     * @param mixed $codigoCuentaAutoretencionVentaFk
     */
    public function setCodigoCuentaAutoretencionVentaFk($codigoCuentaAutoretencionVentaFk): void
    {
        $this->codigoCuentaAutoretencionVentaFk = $codigoCuentaAutoretencionVentaFk;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeAutoretencion()
    {
        return $this->porcentajeAutoretencion;
    }

    /**
     * @param mixed $porcentajeAutoretencion
     */
    public function setPorcentajeAutoretencion($porcentajeAutoretencion): void
    {
        $this->porcentajeAutoretencion = $porcentajeAutoretencion;
    }

    /**
     * @return mixed
     */
    public function getVersionBaseDatos()
    {
        return $this->versionBaseDatos;
    }

    /**
     * @param mixed $versionBaseDatos
     */
    public function setVersionBaseDatos($versionBaseDatos): void
    {
        $this->versionBaseDatos = $versionBaseDatos;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmpresa()
    {
        return $this->codigoEmpresa;
    }

    /**
     * @param mixed $codigoEmpresa
     */
    public function setCodigoEmpresa($codigoEmpresa): void
    {
        $this->codigoEmpresa = $codigoEmpresa;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmpresaOxigeno()
    {
        return $this->codigoEmpresaOxigeno;
    }

    /**
     * @param mixed $codigoEmpresaOxigeno
     */
    public function setCodigoEmpresaOxigeno($codigoEmpresaOxigeno): void
    {
        $this->codigoEmpresaOxigeno = $codigoEmpresaOxigeno;
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
    public function setCiudadRel($ciudadRel): void
    {
        $this->ciudadRel = $ciudadRel;
    }

    /**
     * @return mixed
     */
    public function getIdentificacionRel()
    {
        return $this->identificacionRel;
    }

    /**
     * @param mixed $identificacionRel
     */
    public function setIdentificacionRel($identificacionRel): void
    {
        $this->identificacionRel = $identificacionRel;
    }

    /**
     * @return mixed
     */
    public function getFeToken()
    {
        return $this->feToken;
    }

    /**
     * @param mixed $feToken
     */
    public function setFeToken($feToken): void
    {
        $this->feToken = $feToken;
    }

    /**
     * @return mixed
     */
    public function getFeUsuario()
    {
        return $this->feUsuario;
    }

    /**
     * @param mixed $feUsuario
     */
    public function setFeUsuario($feUsuario): void
    {
        $this->feUsuario = $feUsuario;
    }

    /**
     * @return mixed
     */
    public function getFeClave()
    {
        return $this->feClave;
    }

    /**
     * @param mixed $feClave
     */
    public function setFeClave($feClave): void
    {
        $this->feClave = $feClave;
    }

    /**
     * @return mixed
     */
    public function getFeCodigoEmpresa()
    {
        return $this->feCodigoEmpresa;
    }

    /**
     * @param mixed $feCodigoEmpresa
     */
    public function setFeCodigoEmpresa($feCodigoEmpresa): void
    {
        $this->feCodigoEmpresa = $feCodigoEmpresa;
    }

    /**
     * @return mixed
     */
    public function getCodigoTipoPersonaFk()
    {
        return $this->codigoTipoPersonaFk;
    }

    /**
     * @param mixed $codigoTipoPersonaFk
     */
    public function setCodigoTipoPersonaFk($codigoTipoPersonaFk): void
    {
        $this->codigoTipoPersonaFk = $codigoTipoPersonaFk;
    }

    /**
     * @return mixed
     */
    public function getMatriculaMercantil()
    {
        return $this->matriculaMercantil;
    }

    /**
     * @param mixed $matriculaMercantil
     */
    public function setMatriculaMercantil($matriculaMercantil): void
    {
        $this->matriculaMercantil = $matriculaMercantil;
    }

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
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param mixed $correo
     */
    public function setCorreo($correo): void
    {
        $this->correo = $correo;
    }


}


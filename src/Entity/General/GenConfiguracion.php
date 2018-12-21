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
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer")
     */
    private $codigoConfiguracionPk;

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
     * @ORM\Column(name="web_service_oro_url", type="string", nullable=true)
     */
    private $webServiceOroUrl;

    /**
     * @ORM\Column(name="web_service_cesio_url", type="string", nullable=true)
     */
    private $webServiceCesioUrl;

    /**
     * @ORM\Column(name="dominio", type="string", nullable=true)
     */
    private $dominio;

    /**
     * @ORM\Column(name="codigo_cliente_oro", type="integer", nullable=true)
     */
    private $codigoClienteOro;

    /**
     * @ORM\Column(name="autoretencion_venta", type="boolean", options={"default" : false})
     */
    private $autoretencionVenta = false;

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
     * @ORM\ManyToOne(targetEntity="GenCiudad", inversedBy="configuracionesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

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
    public function getWebServiceOroUrl()
    {
        return $this->webServiceOroUrl;
    }

    /**
     * @param mixed $webServiceOroUrl
     */
    public function setWebServiceOroUrl($webServiceOroUrl): void
    {
        $this->webServiceOroUrl = $webServiceOroUrl;
    }

    /**
     * @return mixed
     */
    public function getCodigoClienteOro()
    {
        return $this->codigoClienteOro;
    }

    /**
     * @param mixed $codigoClienteOro
     */
    public function setCodigoClienteOro($codigoClienteOro): void
    {
        $this->codigoClienteOro = $codigoClienteOro;
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
    public function getAutoretencionVenta()
    {
        return $this->autoretencionVenta;
    }

    /**
     * @param mixed $autoretencionVenta
     */
    public function setAutoretencionVenta( $autoretencionVenta ): void
    {
        $this->autoretencionVenta = $autoretencionVenta;
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
    public function setCodigoCuentaAutoretencionVentaValorFk( $codigoCuentaAutoretencionVentaValorFk ): void
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
    public function setCodigoCuentaAutoretencionVentaFk( $codigoCuentaAutoretencionVentaFk ): void
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
    public function setPorcentajeAutoretencion( $porcentajeAutoretencion ): void
    {
        $this->porcentajeAutoretencion = $porcentajeAutoretencion;
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
    public function setDominio($dominio)
    {
        $this->dominio = $dominio;
        return $this;
    }




}


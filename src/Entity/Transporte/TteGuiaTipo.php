<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteGuiaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteGuiaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoGuiaTipoPk",
        "todos"     => true,
    ];
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
     * @ORM\Column(name="factura", type="boolean", nullable=true,options={"default":false})
     */
    private $factura = false;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="consecutivo_factura", type="integer", nullable=true)
     */
    private $consecutivoFactura = 0;

    /**
     * @ORM\Column(name="codigo_factura_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoFacturaTipoFk;

    /**
     * @ORM\Column(name="exige_numero", type="boolean", nullable=true,options={"default":false})
     */
    private $exigeNumero = false;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true,options={"default":0})
     */
    private $orden = 0;

    /**
     * @ORM\Column(name="validar_flete", type="boolean", nullable=true,options={"default":false})
     */
    private $validarFlete = false;

    /**
     * @ORM\Column(name="validar_rango", type="boolean", nullable=true, options={"default":false})
     */
    private $validarRango = false;

    /**
     * @ORM\Column(name="cortesia", type="boolean", nullable=true,options={"default":false})
     */
    private $cortesia = false;

    /**
     * @ORM\Column(name="validar_caracteres", type="integer", nullable=true)
     */
    private $validarCaracteres;

    /**
     * @ORM\Column(name="codigo_forma_pago", type="string", length=3, nullable=true)
     */
    private $codigoFormaPago;

    /**
     * @ORM\Column(name="mensaje_formato", type="string", length=900, nullable=true)
     */
    private $mensajeFormato;

    /**
     * @return mixed
     */
    public function getGeneraCobroEntrega()
    {
        return $this->generaCobroEntrega;
    }

    /**
     * @param mixed $generaCobroEntrega
     */
    public function setGeneraCobroEntrega($generaCobroEntrega): void
    {
        $this->generaCobroEntrega = $generaCobroEntrega;
    }

    /**
     * @ORM\Column(name="genera_cobro", type="boolean", nullable=true)
     */
    private $generaCobro = false;

    /**
     * @ORM\Column(name="genera_cobro_entrega", type="boolean", nullable=true)
     */
    private $generaCobroEntrega = false;

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
     * @ORM\OneToMany(targetEntity="TteGuiaCliente", mappedBy="guiaTipoRel")
     */
    protected $guiasClientesGuiaTipoRel;

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

    /**
     * @return mixed
     */
    public function getValidarFlete()
    {
        return $this->validarFlete;
    }

    /**
     * @param mixed $validarFlete
     */
    public function setValidarFlete($validarFlete): void
    {
        $this->validarFlete = $validarFlete;
    }

    /**
     * @return mixed
     */
    public function getValidarRango()
    {
        return $this->validarRango;
    }

    /**
     * @param mixed $validarRango
     */
    public function setValidarRango($validarRango): void
    {
        $this->validarRango = $validarRango;
    }

    /**
     * @return mixed
     */
    public function getGeneraCobro()
    {
        return $this->generaCobro;
    }

    /**
     * @param mixed $generaCobro
     */
    public function setGeneraCobro($generaCobro): void
    {
        $this->generaCobro = $generaCobro;
    }

    /**
     * @return mixed
     */
    public function getCortesia()
    {
        return $this->cortesia;
    }

    /**
     * @param mixed $cortesia
     */
    public function setCortesia($cortesia): void
    {
        $this->cortesia = $cortesia;
    }

    /**
     * @return mixed
     */
    public function getConsecutivoFactura()
    {
        return $this->consecutivoFactura;
    }

    /**
     * @param mixed $consecutivoFactura
     */
    public function setConsecutivoFactura($consecutivoFactura): void
    {
        $this->consecutivoFactura = $consecutivoFactura;
    }

    /**
     * @return mixed
     */
    public function getCodigoFormaPago()
    {
        return $this->codigoFormaPago;
    }

    /**
     * @param mixed $codigoFormaPago
     */
    public function setCodigoFormaPago($codigoFormaPago): void
    {
        $this->codigoFormaPago = $codigoFormaPago;
    }

    /**
     * @return mixed
     */
    public function getGuiasClientesGuiaTipoRel()
    {
        return $this->guiasClientesGuiaTipoRel;
    }

    /**
     * @param mixed $guiasClientesGuiaTipoRel
     */
    public function setGuiasClientesGuiaTipoRel($guiasClientesGuiaTipoRel): void
    {
        $this->guiasClientesGuiaTipoRel = $guiasClientesGuiaTipoRel;
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
    public function getValidarCaracteres()
    {
        return $this->validarCaracteres;
    }

    /**
     * @param mixed $validarCaracteres
     */
    public function setValidarCaracteres($validarCaracteres): void
    {
        $this->validarCaracteres = $validarCaracteres;
    }

    /**
     * @return mixed
     */
    public function getMensajeFormato()
    {
        return $this->mensajeFormato;
    }

    /**
     * @param mixed $mensajeFormato
     */
    public function setMensajeFormato($mensajeFormato): void
    {
        $this->mensajeFormato = $mensajeFormato;
    }



}


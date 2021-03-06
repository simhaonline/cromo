<?php


namespace App\Entity\Financiero;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Financiero\FinRegistroRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class FinRegistro
{
    public $infoLog = [
        "primaryKey" => "codigoRegistroPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_registro_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRegistroPk;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */
    private $fechaVence;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="numero_referencia", type="integer", nullable=true, options={"default" : null})
     */
    private $numeroReferencia;

    /**
     * @ORM\Column(name="numero_prefijo", type="string", length=20, nullable=true)
     */
    private $numeroPrefijo;

    /**
     * @ORM\Column(name="numero_referencia_prefijo", type="string", length=20, nullable=true)
     */
    private $numeroReferenciaPrefijo;

    /**
     * @ORM\Column(name="codigo_comprobante_referencia_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteReferenciaFk;

    /**
     * @ORM\Column(name="vr_debito", type="float", nullable=true, options={"default" : 0})
     */
    private $vrDebito = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="vr_credito", type="float", nullable=true, options={"default" : 0})
     */
    private $vrCredito = 0;

    /**
     * @ORM\Column(name="vr_base", type="float", nullable=true, options={"default" : 0})
     */
    private $vrBase = 0;

    /**
     * @ORM\Column(name="naturaleza", type="string", length=1, nullable=true)
     */
    private $naturaleza = 0;

    /**
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="estado_intercambio", type="boolean", options={"default":false})
     */
    private $estadoIntercambio = 0;

    /**
     * @ORM\Column(name="codigo_modelo_fk", type="string", length=80, nullable=true, options={"default" : NULL})
     */
    private $codigoModeloFk;

    /**
     * @ORM\Column(name="codigo_documento", type="integer", nullable=true)
     */
    private $codigoDocumento;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinCuenta", inversedBy="registrosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinTercero", inversedBy="registrosTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinComprobante", inversedBy="registrosComprobanteRel")
     * @ORM\JoinColumn(name="codigo_comprobante_fk", referencedColumnName="codigo_comprobante_pk")
     */
    protected $comprobanteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinComprobante", inversedBy="registrosComprobanteReferenciaRel")
     * @ORM\JoinColumn(name="codigo_comprobante_referencia_fk", referencedColumnName="codigo_comprobante_pk")
     */
    protected $comprobanteReferenciaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinCentroCosto", inversedBy="registrosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;

    /**
     * @return mixed
     */
    public function getCodigoRegistroPk()
    {
        return $this->codigoRegistroPk;
    }

    /**
     * @param mixed $codigoRegistroPk
     */
    public function setCodigoRegistroPk($codigoRegistroPk): void
    {
        $this->codigoRegistroPk = $codigoRegistroPk;
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
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * @param mixed $codigoCuentaFk
     */
    public function setCodigoCuentaFk($codigoCuentaFk): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * @param mixed $codigoTerceroFk
     */
    public function setCodigoTerceroFk($codigoTerceroFk): void
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
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
    public function setCodigoCentroCostoFk($codigoCentroCostoFk): void
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getNumeroReferencia()
    {
        return $this->numeroReferencia;
    }

    /**
     * @param mixed $numeroReferencia
     */
    public function setNumeroReferencia($numeroReferencia): void
    {
        $this->numeroReferencia = $numeroReferencia;
    }

    /**
     * @return mixed
     */
    public function getNumeroPrefijo()
    {
        return $this->numeroPrefijo;
    }

    /**
     * @param mixed $numeroPrefijo
     */
    public function setNumeroPrefijo($numeroPrefijo): void
    {
        $this->numeroPrefijo = $numeroPrefijo;
    }

    /**
     * @return mixed
     */
    public function getNumeroReferenciaPrefijo()
    {
        return $this->numeroReferenciaPrefijo;
    }

    /**
     * @param mixed $numeroReferenciaPrefijo
     */
    public function setNumeroReferenciaPrefijo($numeroReferenciaPrefijo): void
    {
        $this->numeroReferenciaPrefijo = $numeroReferenciaPrefijo;
    }

    /**
     * @return mixed
     */
    public function getVrDebito()
    {
        return $this->vrDebito;
    }

    /**
     * @param mixed $vrDebito
     */
    public function setVrDebito($vrDebito): void
    {
        $this->vrDebito = $vrDebito;
    }

    /**
     * @return mixed
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @param mixed $estadoAutorizado
     */
    public function setEstadoAutorizado($estadoAutorizado): void
    {
        $this->estadoAutorizado = $estadoAutorizado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param mixed $estadoAnulado
     */
    public function setEstadoAnulado($estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
    }

    /**
     * @return mixed
     */
    public function getVrCredito()
    {
        return $this->vrCredito;
    }

    /**
     * @param mixed $vrCredito
     */
    public function setVrCredito($vrCredito): void
    {
        $this->vrCredito = $vrCredito;
    }

    /**
     * @return mixed
     */
    public function getVrBase()
    {
        return $this->vrBase;
    }

    /**
     * @param mixed $vrBase
     */
    public function setVrBase($vrBase): void
    {
        $this->vrBase = $vrBase;
    }

    /**
     * @return mixed
     */
    public function getNaturaleza()
    {
        return $this->naturaleza;
    }

    /**
     * @param mixed $naturaleza
     */
    public function setNaturaleza($naturaleza): void
    {
        $this->naturaleza = $naturaleza;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getEstadoIntercambio()
    {
        return $this->estadoIntercambio;
    }

    /**
     * @param mixed $estadoIntercambio
     */
    public function setEstadoIntercambio($estadoIntercambio): void
    {
        $this->estadoIntercambio = $estadoIntercambio;
    }

    /**
     * @return mixed
     */
    public function getCodigoModeloFk()
    {
        return $this->codigoModeloFk;
    }

    /**
     * @param mixed $codigoModeloFk
     */
    public function setCodigoModeloFk($codigoModeloFk): void
    {
        $this->codigoModeloFk = $codigoModeloFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDocumento()
    {
        return $this->codigoDocumento;
    }

    /**
     * @param mixed $codigoDocumento
     */
    public function setCodigoDocumento($codigoDocumento): void
    {
        $this->codigoDocumento = $codigoDocumento;
    }

    /**
     * @return mixed
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * @param mixed $cuentaRel
     */
    public function setCuentaRel($cuentaRel): void
    {
        $this->cuentaRel = $cuentaRel;
    }

    /**
     * @return mixed
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * @param mixed $terceroRel
     */
    public function setTerceroRel($terceroRel): void
    {
        $this->terceroRel = $terceroRel;
    }

    /**
     * @return mixed
     */
    public function getComprobanteRel()
    {
        return $this->comprobanteRel;
    }

    /**
     * @param mixed $comprobanteRel
     */
    public function setComprobanteRel($comprobanteRel): void
    {
        $this->comprobanteRel = $comprobanteRel;
    }

    /**
     * @return mixed
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * @param mixed $centroCostoRel
     */
    public function setCentroCostoRel($centroCostoRel): void
    {
        $this->centroCostoRel = $centroCostoRel;
    }

    /**
     * @return mixed
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * @param mixed $fechaVence
     */
    public function setFechaVence( $fechaVence ): void
    {
        $this->fechaVence = $fechaVence;
    }

    /**
     * @return mixed
     */
    public function getCodigoComprobanteReferenciaFk()
    {
        return $this->codigoComprobanteReferenciaFk;
    }

    /**
     * @param mixed $codigoComprobanteReferenciaFk
     */
    public function setCodigoComprobanteReferenciaFk( $codigoComprobanteReferenciaFk ): void
    {
        $this->codigoComprobanteReferenciaFk = $codigoComprobanteReferenciaFk;
    }

    /**
     * @return mixed
     */
    public function getComprobanteReferenciaRel()
    {
        return $this->comprobanteReferenciaRel;
    }

    /**
     * @param mixed $comprobanteReferenciaRel
     */
    public function setComprobanteReferenciaRel( $comprobanteReferenciaRel ): void
    {
        $this->comprobanteReferenciaRel = $comprobanteReferenciaRel;
    }



}


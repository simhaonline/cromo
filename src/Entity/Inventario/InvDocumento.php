<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documento")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvDocumentoRepository")
 */
class InvDocumento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDocumentoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_documento_tipo_fk", type="integer")
     */
    private $codigoDocumentoTipoFk;    

    /**
     * @ORM\Column(name="abreviatura", type="string", length=10)
     */     
    private $abreviatura;

    /**
     * @ORM\Column(name="operacion_inventario", type="smallint")
     */          
    private $operacionInventario = 0;
    
    /**
     * @ORM\Column(name="operacion_comercial", type="smallint")
     */          
    private $operacionComercial = 0;       
    
    /**
     * @ORM\Column(name="genera_cartera", type="boolean")
     */          
    private $generaCartera = false;

    /**
     * @ORM\Column(name="tipo_asiento_cartera", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoAsientoCartera;        
    
    /**
     * @ORM\Column(name="genera_tesoreria", type="boolean")
     */          
    private $generaTesoreria = false;

    /**
     * @ORM\Column(name="tipo_asiento_tesoreria", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoAsientoTesoreria;    
    
    /**
     * @ORM\Column(name="tipo_valor", type="smallint")
     * 0 - Ninguno
     * 1 - Compra
     * 2 - Venta
     */          
    private $tipoValor = 0;     
    
    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */          
    private $consecutivo = 0;      
    
    /**
     * @ORM\Column(name="tipo_cuenta_ingreso", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaIngreso = 0;     

    /**
     * @ORM\Column(name="tipo_cuenta_costo", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaCosto = 0;     
    
    /**
     * @ORM\Column(name="tipo_cuenta_iva", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaIva = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_iva_fk", type="string", length=15, nullable=true)
     */    
    private $codigo_cuenta_iva_fk;     

    /**
     * @ORM\Column(name="tipo_cuenta_retencion_fuente", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaRetencionFuente = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_fuente_fk", type="string", length=15, nullable=true)
     */    
    private $codigoCuentaRetencionFuenteFk;     

    /**
     * @ORM\Column(name="tipo_cuenta_retencion_cree", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaRetencionCREE = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_cree_fk", type="string", length=15, nullable=true)
     */    
    private $codigoCuentaRetencionCREEFk;    
    
    /**
     * @ORM\Column(name="tipo_cuenta_retencion_iva", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaRetencionIva = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_iva_fk", type="string", length=15, nullable=true)
     */    
    private $codigoCuentaRetencionIvaFk;    
    
    /**
     * @ORM\Column(name="tipo_cuenta_tesoreria", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaTesoreria = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_tesoreria_fk", type="string", length=15, nullable=true)
     */    
    private $codigoCuentaTesoreriaFk;     
    
    /**
     * @ORM\Column(name="tipo_cuenta_cartera", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaCartera = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_cartera_fk", type="string", length=15, nullable=true)
     */    
    private $codigoCuentaCarteraFk;    

    /**
     * @ORM\Column(name="asignar_consecutivo_creacion", type="boolean")
     */          
    private $asignarConsecutivoCreacion = false;    
    
    /**
     * @ORM\Column(name="asignar_consecutivo_impresion", type="boolean")
     */          
    private $asignarConsecutivoImpresion = false;     

    /**
     * @internal Para saber si el documento genera costo promedio
     * @ORM\Column(name="genera_costo_promedio", type="boolean")
     */          
    private $generaCostoPromedio = false;

    /**
     * @ORM\ManyToOne(targetEntity="InvDocumentoTipo", inversedBy="documentoTipoDocumentoRel")
     * @ORM\JoinColumn(name="codigo_documento_tipo_fk", referencedColumnName="codigo_documento_tipo_pk")
     */
    protected $documentoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoDocumentoPk()
    {
        return $this->codigoDocumentoPk;
    }

    /**
     * @param mixed $codigoDocumentoPk
     */
    public function setCodigoDocumentoPk($codigoDocumentoPk): void
    {
        $this->codigoDocumentoPk = $codigoDocumentoPk;
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
    public function getCodigoDocumentoTipoFk()
    {
        return $this->codigoDocumentoTipoFk;
    }

    /**
     * @param mixed $codigoDocumentoTipoFk
     */
    public function setCodigoDocumentoTipoFk($codigoDocumentoTipoFk): void
    {
        $this->codigoDocumentoTipoFk = $codigoDocumentoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * @param mixed $abreviatura
     */
    public function setAbreviatura($abreviatura): void
    {
        $this->abreviatura = $abreviatura;
    }

    /**
     * @return mixed
     */
    public function getOperacionInventario()
    {
        return $this->operacionInventario;
    }

    /**
     * @param mixed $operacionInventario
     */
    public function setOperacionInventario($operacionInventario): void
    {
        $this->operacionInventario = $operacionInventario;
    }

    /**
     * @return mixed
     */
    public function getOperacionComercial()
    {
        return $this->operacionComercial;
    }

    /**
     * @param mixed $operacionComercial
     */
    public function setOperacionComercial($operacionComercial): void
    {
        $this->operacionComercial = $operacionComercial;
    }

    /**
     * @return mixed
     */
    public function getGeneraCartera()
    {
        return $this->generaCartera;
    }

    /**
     * @param mixed $generaCartera
     */
    public function setGeneraCartera($generaCartera): void
    {
        $this->generaCartera = $generaCartera;
    }

    /**
     * @return mixed
     */
    public function getTipoAsientoCartera()
    {
        return $this->tipoAsientoCartera;
    }

    /**
     * @param mixed $tipoAsientoCartera
     */
    public function setTipoAsientoCartera($tipoAsientoCartera): void
    {
        $this->tipoAsientoCartera = $tipoAsientoCartera;
    }

    /**
     * @return mixed
     */
    public function getGeneraTesoreria()
    {
        return $this->generaTesoreria;
    }

    /**
     * @param mixed $generaTesoreria
     */
    public function setGeneraTesoreria($generaTesoreria): void
    {
        $this->generaTesoreria = $generaTesoreria;
    }

    /**
     * @return mixed
     */
    public function getTipoAsientoTesoreria()
    {
        return $this->tipoAsientoTesoreria;
    }

    /**
     * @param mixed $tipoAsientoTesoreria
     */
    public function setTipoAsientoTesoreria($tipoAsientoTesoreria): void
    {
        $this->tipoAsientoTesoreria = $tipoAsientoTesoreria;
    }

    /**
     * @return mixed
     */
    public function getTipoValor()
    {
        return $this->tipoValor;
    }

    /**
     * @param mixed $tipoValor
     */
    public function setTipoValor($tipoValor): void
    {
        $this->tipoValor = $tipoValor;
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
    public function getTipoCuentaIngreso()
    {
        return $this->tipoCuentaIngreso;
    }

    /**
     * @param mixed $tipoCuentaIngreso
     */
    public function setTipoCuentaIngreso($tipoCuentaIngreso): void
    {
        $this->tipoCuentaIngreso = $tipoCuentaIngreso;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaCosto()
    {
        return $this->tipoCuentaCosto;
    }

    /**
     * @param mixed $tipoCuentaCosto
     */
    public function setTipoCuentaCosto($tipoCuentaCosto): void
    {
        $this->tipoCuentaCosto = $tipoCuentaCosto;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaIva()
    {
        return $this->tipoCuentaIva;
    }

    /**
     * @param mixed $tipoCuentaIva
     */
    public function setTipoCuentaIva($tipoCuentaIva): void
    {
        $this->tipoCuentaIva = $tipoCuentaIva;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaIvaFk()
    {
        return $this->codigo_cuenta_iva_fk;
    }

    /**
     * @param mixed $codigo_cuenta_iva_fk
     */
    public function setCodigoCuentaIvaFk($codigo_cuenta_iva_fk): void
    {
        $this->codigo_cuenta_iva_fk = $codigo_cuenta_iva_fk;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaRetencionFuente()
    {
        return $this->tipoCuentaRetencionFuente;
    }

    /**
     * @param mixed $tipoCuentaRetencionFuente
     */
    public function setTipoCuentaRetencionFuente($tipoCuentaRetencionFuente): void
    {
        $this->tipoCuentaRetencionFuente = $tipoCuentaRetencionFuente;
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
    public function getTipoCuentaRetencionCREE()
    {
        return $this->tipoCuentaRetencionCREE;
    }

    /**
     * @param mixed $tipoCuentaRetencionCREE
     */
    public function setTipoCuentaRetencionCREE($tipoCuentaRetencionCREE): void
    {
        $this->tipoCuentaRetencionCREE = $tipoCuentaRetencionCREE;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaRetencionCREEFk()
    {
        return $this->codigoCuentaRetencionCREEFk;
    }

    /**
     * @param mixed $codigoCuentaRetencionCREEFk
     */
    public function setCodigoCuentaRetencionCREEFk($codigoCuentaRetencionCREEFk): void
    {
        $this->codigoCuentaRetencionCREEFk = $codigoCuentaRetencionCREEFk;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaRetencionIva()
    {
        return $this->tipoCuentaRetencionIva;
    }

    /**
     * @param mixed $tipoCuentaRetencionIva
     */
    public function setTipoCuentaRetencionIva($tipoCuentaRetencionIva): void
    {
        $this->tipoCuentaRetencionIva = $tipoCuentaRetencionIva;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaRetencionIvaFk()
    {
        return $this->codigoCuentaRetencionIvaFk;
    }

    /**
     * @param mixed $codigoCuentaRetencionIvaFk
     */
    public function setCodigoCuentaRetencionIvaFk($codigoCuentaRetencionIvaFk): void
    {
        $this->codigoCuentaRetencionIvaFk = $codigoCuentaRetencionIvaFk;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaTesoreria()
    {
        return $this->tipoCuentaTesoreria;
    }

    /**
     * @param mixed $tipoCuentaTesoreria
     */
    public function setTipoCuentaTesoreria($tipoCuentaTesoreria): void
    {
        $this->tipoCuentaTesoreria = $tipoCuentaTesoreria;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaTesoreriaFk()
    {
        return $this->codigoCuentaTesoreriaFk;
    }

    /**
     * @param mixed $codigoCuentaTesoreriaFk
     */
    public function setCodigoCuentaTesoreriaFk($codigoCuentaTesoreriaFk): void
    {
        $this->codigoCuentaTesoreriaFk = $codigoCuentaTesoreriaFk;
    }

    /**
     * @return mixed
     */
    public function getTipoCuentaCartera()
    {
        return $this->tipoCuentaCartera;
    }

    /**
     * @param mixed $tipoCuentaCartera
     */
    public function setTipoCuentaCartera($tipoCuentaCartera): void
    {
        $this->tipoCuentaCartera = $tipoCuentaCartera;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCarteraFk()
    {
        return $this->codigoCuentaCarteraFk;
    }

    /**
     * @param mixed $codigoCuentaCarteraFk
     */
    public function setCodigoCuentaCarteraFk($codigoCuentaCarteraFk): void
    {
        $this->codigoCuentaCarteraFk = $codigoCuentaCarteraFk;
    }

    /**
     * @return mixed
     */
    public function getAsignarConsecutivoCreacion()
    {
        return $this->asignarConsecutivoCreacion;
    }

    /**
     * @param mixed $asignarConsecutivoCreacion
     */
    public function setAsignarConsecutivoCreacion($asignarConsecutivoCreacion): void
    {
        $this->asignarConsecutivoCreacion = $asignarConsecutivoCreacion;
    }

    /**
     * @return mixed
     */
    public function getAsignarConsecutivoImpresion()
    {
        return $this->asignarConsecutivoImpresion;
    }

    /**
     * @param mixed $asignarConsecutivoImpresion
     */
    public function setAsignarConsecutivoImpresion($asignarConsecutivoImpresion): void
    {
        $this->asignarConsecutivoImpresion = $asignarConsecutivoImpresion;
    }

    /**
     * @return mixed
     */
    public function getGeneraCostoPromedio()
    {
        return $this->generaCostoPromedio;
    }

    /**
     * @param mixed $generaCostoPromedio
     */
    public function setGeneraCostoPromedio($generaCostoPromedio): void
    {
        $this->generaCostoPromedio = $generaCostoPromedio;
    }

    /**
     * @return mixed
     */
    public function getDocumentoTipoRel()
    {
        return $this->documentoTipoRel;
    }

    /**
     * @param mixed $documentoTipoRel
     */
    public function setDocumentoTipoRel($documentoTipoRel): void
    {
        $this->documentoTipoRel = $documentoTipoRel;
    }
}

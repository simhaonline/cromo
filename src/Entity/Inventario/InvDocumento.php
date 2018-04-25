<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documento")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentoRepository")
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
    private $generaTesoreria = 0;    

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
    private $generaCostoPromedio = 0;      

}

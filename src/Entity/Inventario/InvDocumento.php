<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="inv_documento")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvDocumentoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoDocumentoPk"},message="Ya existe el código del documento")
 */
class InvDocumento
{
    public $infoLog = [
        "primaryKey" => "codigoDocumentoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_pk",type="string",length=10)
     */
    private $codigoDocumentoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_documento_tipo_fk", type="string",length=10)
     */
    private $codigoDocumentoTipoFk;

    /**
     * @ORM\Column(name="abreviatura", type="string", length=10)
     */
    private $abreviatura;

    /**
     * @ORM\Column(name="operacion_inventario", type="smallint", nullable=true, options={"default" : 0})
     */
    private $operacionInventario = 0;

    /**
     * @ORM\Column(name="operacion_comercial", type="smallint")
     */
    private $operacionComercial = 0;

    /**
     * @ORM\Column(name="genera_cartera", type="boolean", options={"default":false})
     */
    private $generaCartera = false;

    /**
     * @ORM\Column(name="genera_tesoreria", type="boolean", options={"default":false})
     */
    private $generaTesoreria = false;

    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */
    private $consecutivo = 0;

    /**
     * @internal Para saber si el documento genera costo promedio
     * @ORM\Column(name="genera_costo_promedio", type="boolean", options={"default":false})
     */
    private $generaCostoPromedio = false;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaCobrarTipoFk;

    /**
     * @ORM\Column(name="adicionar", type="boolean",options={"default" : false}, nullable=true)
     */
    private $adicionar = false;

    /**
     * @ORM\Column(name="adicionar_importacion", type="boolean",options={"default" : false}, nullable=true)
     */
    private $adicionarImportacion = false;

    /**
     * @ORM\Column(name="adicionar_pedido", type="boolean",options={"default" : false}, nullable=true)
     */
    private $adicionarPedido = false;

    /**
     * @ORM\Column(name="adicionar_remision", type="boolean",options={"default" : false}, nullable=true)
     */
    private $adicionarRemision = false;

    /**
     * @ORM\Column(name="adicionar_orden", type="boolean",options={"default" : false}, nullable=true)
     */
    private $adicionarOrden = false;

    /**
     * @ORM\Column(name="nota_credito", type="boolean", nullable=true, options={"default":false})
     */
    private $notaCredito = 0;

    /**
     * @ORM\Column(name="contabilizar", type="boolean", nullable=true, options={"default":false})
     */
    private $contabilizar = 0;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_proveedor_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaProveedorFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cliente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaClienteFk;

    /**
     * @ORM\Column(name="compra_extranjera", type="boolean", nullable=true, options={"default":false})
     */
    private $compraExtranjera = false;

    /**
     * @ORM\ManyToOne(targetEntity="InvDocumentoTipo", inversedBy="documentosDocumentoTipoRel")
     * @ORM\JoinColumn(name="codigo_documento_tipo_fk", referencedColumnName="codigo_documento_tipo_pk")
     */
    protected $documentoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento", mappedBy="documentoRel")
     */
    protected $movimientosDocumentoRel;

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
    public function setCodigoDocumentoPk( $codigoDocumentoPk ): void
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
    public function setNombre( $nombre ): void
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
    public function setCodigoDocumentoTipoFk( $codigoDocumentoTipoFk ): void
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
    public function setAbreviatura( $abreviatura ): void
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
    public function setOperacionInventario( $operacionInventario ): void
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
    public function setOperacionComercial( $operacionComercial ): void
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
    public function setGeneraCartera( $generaCartera ): void
    {
        $this->generaCartera = $generaCartera;
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
    public function setGeneraTesoreria( $generaTesoreria ): void
    {
        $this->generaTesoreria = $generaTesoreria;
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
    public function setConsecutivo( $consecutivo ): void
    {
        $this->consecutivo = $consecutivo;
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
    public function setGeneraCostoPromedio( $generaCostoPromedio ): void
    {
        $this->generaCostoPromedio = $generaCostoPromedio;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarTipoFk()
    {
        return $this->codigoCuentaCobrarTipoFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarTipoFk
     */
    public function setCodigoCuentaCobrarTipoFk( $codigoCuentaCobrarTipoFk ): void
    {
        $this->codigoCuentaCobrarTipoFk = $codigoCuentaCobrarTipoFk;
    }

    /**
     * @return mixed
     */
    public function getAdicionar()
    {
        return $this->adicionar;
    }

    /**
     * @param mixed $adicionar
     */
    public function setAdicionar( $adicionar ): void
    {
        $this->adicionar = $adicionar;
    }

    /**
     * @return mixed
     */
    public function getAdicionarImportacion()
    {
        return $this->adicionarImportacion;
    }

    /**
     * @param mixed $adicionarImportacion
     */
    public function setAdicionarImportacion( $adicionarImportacion ): void
    {
        $this->adicionarImportacion = $adicionarImportacion;
    }

    /**
     * @return mixed
     */
    public function getAdicionarPedido()
    {
        return $this->adicionarPedido;
    }

    /**
     * @param mixed $adicionarPedido
     */
    public function setAdicionarPedido( $adicionarPedido ): void
    {
        $this->adicionarPedido = $adicionarPedido;
    }

    /**
     * @return mixed
     */
    public function getAdicionarRemision()
    {
        return $this->adicionarRemision;
    }

    /**
     * @param mixed $adicionarRemision
     */
    public function setAdicionarRemision( $adicionarRemision ): void
    {
        $this->adicionarRemision = $adicionarRemision;
    }

    /**
     * @return mixed
     */
    public function getAdicionarOrden()
    {
        return $this->adicionarOrden;
    }

    /**
     * @param mixed $adicionarOrden
     */
    public function setAdicionarOrden( $adicionarOrden ): void
    {
        $this->adicionarOrden = $adicionarOrden;
    }

    /**
     * @return mixed
     */
    public function getNotaCredito()
    {
        return $this->notaCredito;
    }

    /**
     * @param mixed $notaCredito
     */
    public function setNotaCredito( $notaCredito ): void
    {
        $this->notaCredito = $notaCredito;
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
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * @param mixed $codigoComprobanteFk
     */
    public function setCodigoComprobanteFk( $codigoComprobanteFk ): void
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaProveedorFk()
    {
        return $this->codigoCuentaProveedorFk;
    }

    /**
     * @param mixed $codigoCuentaProveedorFk
     */
    public function setCodigoCuentaProveedorFk( $codigoCuentaProveedorFk ): void
    {
        $this->codigoCuentaProveedorFk = $codigoCuentaProveedorFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaClienteFk()
    {
        return $this->codigoCuentaClienteFk;
    }

    /**
     * @param mixed $codigoCuentaClienteFk
     */
    public function setCodigoCuentaClienteFk( $codigoCuentaClienteFk ): void
    {
        $this->codigoCuentaClienteFk = $codigoCuentaClienteFk;
    }

    /**
     * @return mixed
     */
    public function getCompraExtranjera()
    {
        return $this->compraExtranjera;
    }

    /**
     * @param mixed $compraExtranjera
     */
    public function setCompraExtranjera( $compraExtranjera ): void
    {
        $this->compraExtranjera = $compraExtranjera;
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
    public function setDocumentoTipoRel( $documentoTipoRel ): void
    {
        $this->documentoTipoRel = $documentoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientosDocumentoRel()
    {
        return $this->movimientosDocumentoRel;
    }

    /**
     * @param mixed $movimientosDocumentoRel
     */
    public function setMovimientosDocumentoRel( $movimientosDocumentoRel ): void
    {
        $this->movimientosDocumentoRel = $movimientosDocumentoRel;
    }



}

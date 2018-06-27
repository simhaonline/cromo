<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvDocumentoTipoRepository")
 */
class InvDocumentoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_tipo_pk",type="string",length=10)
     */
    private $codigoDocumentoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="InvDocumento", mappedBy="documentoTipoRel")
     */
    protected $documentoTipoDocumentosRel;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento", mappedBy="documentoTipoRel")
     */
    protected $documentoTipoMovimientosRel;

    /**
     * @return mixed
     */
    public function getCodigoDocumentoTipoPk()
    {
        return $this->codigoDocumentoTipoPk;
    }

    /**
     * @param mixed $codigoDocumentoTipoPk
     */
    public function setCodigoDocumentoTipoPk($codigoDocumentoTipoPk): void
    {
        $this->codigoDocumentoTipoPk = $codigoDocumentoTipoPk;
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
    public function getDocumentoTipoDocumentosRel()
    {
        return $this->documentoTipoDocumentosRel;
    }

    /**
     * @param mixed $documentoTipoDocumentosRel
     */
    public function setDocumentoTipoDocumentosRel($documentoTipoDocumentosRel): void
    {
        $this->documentoTipoDocumentosRel = $documentoTipoDocumentosRel;
    }

    /**
     * @return mixed
     */
    public function getDocumentoTipoMovimientosRel()
    {
        return $this->documentoTipoMovimientosRel;
    }

    /**
     * @param mixed $documentoTipoMovimientosRel
     */
    public function setDocumentoTipoMovimientosRel($documentoTipoMovimientosRel): void
    {
        $this->documentoTipoMovimientosRel = $documentoTipoMovimientosRel;
    }
}


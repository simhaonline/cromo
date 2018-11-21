<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvDocumentoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoDocumentoTipoPk"},message="Ya existe el código del tipo ya existe")
 */
class InvDocumentoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoDocumentoTipoPk",
        "todos"     => true,
    ];
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
    protected $documentosDocumentoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento", mappedBy="documentoTipoRel")
     */
    protected $movimientosDocumentoTipoRel;

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
    public function getDocumentosDocumentoTipoRel()
    {
        return $this->documentosDocumentoTipoRel;
    }

    /**
     * @param mixed $documentosDocumentoTipoRel
     */
    public function setDocumentosDocumentoTipoRel($documentosDocumentoTipoRel): void
    {
        $this->documentosDocumentoTipoRel = $documentosDocumentoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getMovimientosDocumentoTipoRel()
    {
        return $this->movimientosDocumentoTipoRel;
    }

    /**
     * @param mixed $movimientosDocumentoTipoRel
     */
    public function setMovimientosDocumentoTipoRel($movimientosDocumentoTipoRel): void
    {
        $this->movimientosDocumentoTipoRel = $movimientosDocumentoTipoRel;
    }
}


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
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoDocumentoTipoPk;

    /**
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="InvDocumento", mappedBy="documentoTipoRel")
     */
    protected $documentoTipoDocumentoRel;

    /**
     * @ORM\OneToMany(targetEntity="InvDocumentoTipo", mappedBy="documentoTipoRel")
     */
    protected $documentoTipoMovimientoRel;

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
    public function getDocumentoTipoDocumentoRel()
    {
        return $this->documentoTipoDocumentoRel;
    }

    /**
     * @param mixed $documentoTipoDocumentoRel
     */
    public function setDocumentoTipoDocumentoRel($documentoTipoDocumentoRel): void
    {
        $this->documentoTipoDocumentoRel = $documentoTipoDocumentoRel;
    }

    /**
     * @return mixed
     */
    public function getDocumentoTipoMovimientoRel()
    {
        return $this->documentoTipoMovimientoRel;
    }

    /**
     * @param mixed $documentoTipoMovimientoRel
     */
    public function setDocumentoTipoMovimientoRel($documentoTipoMovimientoRel): void
    {
        $this->documentoTipoMovimientoRel = $documentoTipoMovimientoRel;
    }
}


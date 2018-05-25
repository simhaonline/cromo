<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvOrdenCompraDocumentoRepository")
 */
class InvOrdenCompraDocumento
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoOrdenCompraDocumentoPk;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="consecutivo", type="integer",nullable=true)
     */
    private $consecutivo;

    /**
     * @return mixed
     */
    public function getCodigoOrdenCompraDocumentoPk()
    {
        return $this->codigoOrdenCompraDocumentoPk;
    }

    /**
     * @param mixed $codigoOrdenCompraDocumentoPk
     */
    public function setCodigoOrdenCompraDocumentoPk($codigoOrdenCompraDocumentoPk): void
    {
        $this->codigoOrdenCompraDocumentoPk = $codigoOrdenCompraDocumentoPk;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return int
     */
    public function getConsecutivo(): int
    {
        return $this->consecutivo;
    }

    /**
     * @param int $consecutivo
     */
    public function setConsecutivo(int $consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }


}


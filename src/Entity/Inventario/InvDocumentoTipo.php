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


}


<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvOrdenCompraTipoRepository")
 */
class InvOrdenCompraTipo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoOrdenCompraTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer",nullable=true)
     */
    private $consecutivo;

    /**
     * @return mixed
     */
    public function getCodigoOrdenCompraTipoPk()
    {
        return $this->codigoOrdenCompraTipoPk;
    }

    /**
     * @param mixed $codigoOrdenCompraTipoPk
     */
    public function setCodigoOrdenCompraTipoPk($codigoOrdenCompraTipoPk): void
    {
        $this->codigoOrdenCompraTipoPk = $codigoOrdenCompraTipoPk;
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
}


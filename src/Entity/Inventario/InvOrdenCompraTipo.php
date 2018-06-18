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
     * @ORM\Column(name="consecutivo", type="integer",options={"default" : 0},nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\OneToMany(targetEntity="InvOrdenCompra", mappedBy="ordenCompraTipoRel")
     */
    protected $ordenCompraTipoOrdenesCompraRel;

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

    /**
     * @return mixed
     */
    public function getOrdenCompraTipoOrdenesCompraRel()
    {
        return $this->ordenCompraTipoOrdenesCompraRel;
    }

    /**
     * @param mixed $ordenCompraTipoOrdenesCompraRel
     */
    public function setOrdenCompraTipoOrdenesCompraRel($ordenCompraTipoOrdenesCompraRel): void
    {
        $this->ordenCompraTipoOrdenesCompraRel = $ordenCompraTipoOrdenesCompraRel;
    }


}


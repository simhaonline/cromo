<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvOrdenCompraTipoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoOrdenCompraTipoPk"},message="Ya existe el código del tipo")
 */
class InvOrdenCompraTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_orden_compra_tipo_pk",type="string",length=10)
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
    protected $ordenesComprasOrdenCompraTipoRel;

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
    public function getOrdenesComprasOrdenCompraTipoRel()
    {
        return $this->ordenesComprasOrdenCompraTipoRel;
    }

    /**
     * @param mixed $ordenesComprasOrdenCompraTipoRel
     */
    public function setOrdenesComprasOrdenCompraTipoRel($ordenesComprasOrdenCompraTipoRel): void
    {
        $this->ordenesComprasOrdenCompraTipoRel = $ordenesComprasOrdenCompraTipoRel;
    }
}


<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvOrdenTipoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoOrdenTipoPk"},message="Ya existe el código del tipo")
 */
class InvOrdenTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_orden_tipo_pk",type="string",length=10)
     */
    private $codigoOrdenTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer",options={"default" : 0},nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\OneToMany(targetEntity="InvOrden", mappedBy="ordenTipoRel")
     */
    protected $ordenesOrdenTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoOrdenTipoPk()
    {
        return $this->codigoOrdenTipoPk;
    }

    /**
     * @param mixed $codigoOrdenTipoPk
     */
    public function setCodigoOrdenTipoPk($codigoOrdenTipoPk): void
    {
        $this->codigoOrdenTipoPk = $codigoOrdenTipoPk;
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
    public function getOrdenesOrdenTipoRel()
    {
        return $this->ordenesOrdenTipoRel;
    }

    /**
     * @param mixed $ordenesOrdenTipoRel
     */
    public function setOrdenesOrdenTipoRel($ordenesOrdenTipoRel): void
    {
        $this->ordenesOrdenTipoRel = $ordenesOrdenTipoRel;
    }



}


<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvRemisionTipoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoRemisionTipoPk"},message="Ya existe el código del tipo")
 */
class InvRemisionTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10)
     */
    private $codigoRemisionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer",options={"default" : 0},nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="operacion_inventario", type="smallint", nullable=true, options={"default" : 0})
     */
    private $operacionInventario = 0;

    /**
     * @ORM\OneToMany(targetEntity="InvRemision", mappedBy="remisionTipoRel")
     */
    protected $remisionesRemisionTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoRemisionTipoPk()
    {
        return $this->codigoRemisionTipoPk;
    }

    /**
     * @param mixed $codigoRemisionTipoPk
     */
    public function setCodigoRemisionTipoPk($codigoRemisionTipoPk): void
    {
        $this->codigoRemisionTipoPk = $codigoRemisionTipoPk;
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
    public function getRemisionesRemisionTipoRel()
    {
        return $this->remisionesRemisionTipoRel;
    }

    /**
     * @param mixed $remisionesRemisionTipoRel
     */
    public function setRemisionesRemisionTipoRel($remisionesRemisionTipoRel): void
    {
        $this->remisionesRemisionTipoRel = $remisionesRemisionTipoRel;
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




}


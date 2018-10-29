<?php

namespace App\Entity\Inventario;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvBodegaUsuarioRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoBodegaUsuarioPk"},message="Ya existe ")
 */
class InvBodegaUsuario
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_bodega_usuario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoBodegaUsuarioPk;

    /**
     * @ORM\Column(name="codigo_bodega_fk", type="string", length=10, nullable=true)
     */
    private $codigoBodegaFk;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="InvBodega", inversedBy="bodegasUsuariosBodegaRel")
     * @ORM\JoinColumn(name="codigo_bodega_fk", referencedColumnName="codigo_bodega_pk")
     */
    protected $bodegaRel;

    /**
     * @return mixed
     */
    public function getCodigoBodegaUsuarioPk()
    {
        return $this->codigoBodegaUsuarioPk;
    }

    /**
     * @param mixed $codigoBodegaUsuarioPk
     */
    public function setCodigoBodegaUsuarioPk($codigoBodegaUsuarioPk): void
    {
        $this->codigoBodegaUsuarioPk = $codigoBodegaUsuarioPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoBodegaFk()
    {
        return $this->codigoBodegaFk;
    }

    /**
     * @param mixed $codigoBodegaFk
     */
    public function setCodigoBodegaFk($codigoBodegaFk): void
    {
        $this->codigoBodegaFk = $codigoBodegaFk;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getBodegaRel()
    {
        return $this->bodegaRel;
    }

    /**
     * @param mixed $bodegaRel
     */
    public function setBodegaRel($bodegaRel): void
    {
        $this->bodegaRel = $bodegaRel;
    }
}

<?php

namespace App\Entity\Inventario;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_bodega_usuario")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvBodegaUsuarioRepository")
 */
class InvBodegaUsuario
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_bodega_usuario_pk", type="string", length=10)
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



}

<?php

namespace App\Entity\Inventario;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_bodega")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvBodegaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoBodegaPk"},message="Ya existe el código de la bodega")
 */
class InvBodega
{
    public $infoLog = [
        "primaryKey" => "codigoBodegaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_bodega_pk", type="string", length=10)     
     */
    private $codigoBodegaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="InvLote", mappedBy="bodegaRel")
     */
    protected $lotesBodegaRel;

    /**
     * @ORM\OneToMany(targetEntity="InvBodegaUsuario", mappedBy="bodegaRel")
     */
    protected $bodegasUsuariosBodegaRel;

    /**
     * @return mixed
     */
    public function getCodigoBodegaPk()
    {
        return $this->codigoBodegaPk;
    }

    /**
     * @param mixed $codigoBodegaPk
     */
    public function setCodigoBodegaPk($codigoBodegaPk): void
    {
        $this->codigoBodegaPk = $codigoBodegaPk;
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
    public function getLotesBodegaRel()
    {
        return $this->lotesBodegaRel;
    }

    /**
     * @param mixed $lotesBodegaRel
     */
    public function setLotesBodegaRel($lotesBodegaRel): void
    {
        $this->lotesBodegaRel = $lotesBodegaRel;
    }

    /**
     * @return mixed
     */
    public function getBodegasUsuariosBodegaRel()
    {
        return $this->bodegasUsuariosBodegaRel;
    }

    /**
     * @param mixed $bodegasUsuariosBodegaRel
     */
    public function setBodegasUsuariosBodegaRel($bodegasUsuariosBodegaRel): void
    {
        $this->bodegasUsuariosBodegaRel = $bodegasUsuariosBodegaRel;
    }
}



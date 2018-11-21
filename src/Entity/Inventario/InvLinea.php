<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="inv_linea")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvLineaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoLineaPk"},message="Ya existe el código de la linea")
 */
class InvLinea
{
    public $infoLog = [
        "primaryKey" => "codigoLineaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_linea_pk", type="string",length=10)
     */     
    private $codigoLineaPk;

    /**
     * @ORM\Column(name="nombre", type="string",length=20,nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="InvGrupo",mappedBy="lineaRel")
     */
    protected $gruposLineaRel;

    /**
     * @ORM\OneToMany(targetEntity="InvSubgrupo",mappedBy="lineaRel")
     */
    protected $subgruposLineaRel;

    /**
     * @ORM\OneToMany(targetEntity="InvItem",mappedBy="lineaRel")
     */
    protected $itemsLineaRel;

    /**
     * @return mixed
     */
    public function getCodigoLineaPk()
    {
        return $this->codigoLineaPk;
    }

    /**
     * @param mixed $codigoLineaPk
     */
    public function setCodigoLineaPk($codigoLineaPk): void
    {
        $this->codigoLineaPk = $codigoLineaPk;
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
    public function getGruposLineaRel()
    {
        return $this->gruposLineaRel;
    }

    /**
     * @param mixed $gruposLineaRel
     */
    public function setGruposLineaRel($gruposLineaRel): void
    {
        $this->gruposLineaRel = $gruposLineaRel;
    }

    /**
     * @return mixed
     */
    public function getSubgruposLineaRel()
    {
        return $this->subgruposLineaRel;
    }

    /**
     * @param mixed $subgruposLineaRel
     */
    public function setSubgruposLineaRel($subgruposLineaRel): void
    {
        $this->subgruposLineaRel = $subgruposLineaRel;
    }

    /**
     * @return mixed
     */
    public function getItemsLineaRel()
    {
        return $this->itemsLineaRel;
    }

    /**
     * @param mixed $itemsLineaRel
     */
    public function setItemsLineaRel($itemsLineaRel): void
    {
        $this->itemsLineaRel = $itemsLineaRel;
    }
}

<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_marca")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvMarcaRepository")
 */
class InvMarca
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_marca_pk", type="string",length=20)
     */     
    private $codigoMarcaPk;

    /**
     * @ORM\Column(name="nombre", type="string",length=20,nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="InvItem",mappedBy="marcaRel")
     */
    protected $marcaItemRel;

    /**
     * @return mixed
     */
    public function getCodigoMarcaPk()
    {
        return $this->codigoMarcaPk;
    }

    /**
     * @param mixed $codigoMarcaPk
     */
    public function setCodigoMarcaPk($codigoMarcaPk): void
    {
        $this->codigoMarcaPk = $codigoMarcaPk;
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
    public function getMarcaItemRel()
    {
        return $this->marcaItemRel;
    }

    /**
     * @param mixed $marcaItemRel
     */
    public function setMarcaItemRel($marcaItemRel): void
    {
        $this->marcaItemRel = $marcaItemRel;
    }
}

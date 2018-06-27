<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="inv_linea")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvLineaRepository")
 */
class InvLinea
{
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
    protected $lineaGruposRel;

    /**
     * @ORM\OneToMany(targetEntity="InvSubgrupo",mappedBy="lineaRel")
     */
    protected $lineaSubgruposRel;

    /**
     * @ORM\OneToMany(targetEntity="InvItem",mappedBy="lineaRel")
     */
    protected $lineaItemsRel;

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
    public function getLineaGruposRel()
    {
        return $this->lineaGruposRel;
    }

    /**
     * @param mixed $lineaGruposRel
     */
    public function setLineaGruposRel($lineaGruposRel): void
    {
        $this->lineaGruposRel = $lineaGruposRel;
    }

    /**
     * @return mixed
     */
    public function getLineaSubgruposRel()
    {
        return $this->lineaSubgruposRel;
    }

    /**
     * @param mixed $lineaSubgruposRel
     */
    public function setLineaSubgruposRel($lineaSubgruposRel): void
    {
        $this->lineaSubgruposRel = $lineaSubgruposRel;
    }

    /**
     * @return mixed
     */
    public function getLineaItemsRel()
    {
        return $this->lineaItemsRel;
    }

    /**
     * @param mixed $lineaItemsRel
     */
    public function setLineaItemsRel($lineaItemsRel): void
    {
        $this->lineaItemsRel = $lineaItemsRel;
    }


}

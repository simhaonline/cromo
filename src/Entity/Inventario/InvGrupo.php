<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="inv_grupo")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvGrupoRepository")
 */
class InvGrupo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_grupo_pk", type="string",length=10)
     */     
    private $codigoGrupoPk;

    /**
     * @ORM\Column(name="codigo_linea_fk", type="string",length=20, nullable=true)
     */
    private $codigoLineaFk;

    /**
     * @ORM\Column(name="nombre", type="string",length=20,nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="InvLinea",inversedBy="gruposLineaRel")
     * @ORM\JoinColumn(name="codigo_linea_fk",referencedColumnName="codigo_linea_pk")
     * @Assert\NotBlank(
     *     message="El campo no puede estar vacio"
     * )
     */
    protected $lineaRel;

    /**
     * @ORM\OneToMany(targetEntity="InvSubgrupo",mappedBy="grupoRel")
     */
    protected $subgruposGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="InvItem",mappedBy="grupoRel")
     */
    protected $itemsGrupoRel;

    /**
     * @return mixed
     */
    public function getCodigoGrupoPk()
    {
        return $this->codigoGrupoPk;
    }

    /**
     * @param mixed $codigoGrupoPk
     */
    public function setCodigoGrupoPk($codigoGrupoPk): void
    {
        $this->codigoGrupoPk = $codigoGrupoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoLineaFk()
    {
        return $this->codigoLineaFk;
    }

    /**
     * @param mixed $codigoLineaFk
     */
    public function setCodigoLineaFk($codigoLineaFk): void
    {
        $this->codigoLineaFk = $codigoLineaFk;
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
    public function getLineaRel()
    {
        return $this->lineaRel;
    }

    /**
     * @param mixed $lineaRel
     */
    public function setLineaRel($lineaRel): void
    {
        $this->lineaRel = $lineaRel;
    }

    /**
     * @return mixed
     */
    public function getSubgruposGrupoRel()
    {
        return $this->subgruposGrupoRel;
    }

    /**
     * @param mixed $subgruposGrupoRel
     */
    public function setSubgruposGrupoRel($subgruposGrupoRel): void
    {
        $this->subgruposGrupoRel = $subgruposGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getItemsGrupoRel()
    {
        return $this->itemsGrupoRel;
    }

    /**
     * @param mixed $itemsGrupoRel
     */
    public function setItemsGrupoRel($itemsGrupoRel): void
    {
        $this->itemsGrupoRel = $itemsGrupoRel;
    }
}

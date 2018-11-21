<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="inv_subgrupo")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvSubgrupoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvSubgrupo
{
    public $infoLog = [
        "primaryKey" => "codigoSubgrupoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_subgrupo_pk", type="string", length=10)
     */     
    private $codigoSubgrupoPk;

    /**
     * @ORM\Column(name="codigo_linea_fk", type="string",length=20, nullable=true)
     */
    private $codigoLineaFk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="string",length=20, nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="nombre", type="string",length=20,nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="InvLinea",inversedBy="subgruposLineaRel")
     * @ORM\JoinColumn(name="codigo_linea_fk",referencedColumnName="codigo_linea_pk")
     * @Assert\NotBlank(
     *     message="El campo no puede estar vacio"
     * )
     */
    protected $lineaRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvGrupo",inversedBy="subgruposGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk",referencedColumnName="codigo_grupo_pk")
     * @Assert\NotBlank(
     *     message="El campo no puede estar vacio"
     * )
     */
    protected $grupoRel;

    /**
     * @ORM\OneToMany(targetEntity="InvItem",mappedBy="subgrupoRel")
     */
    protected $subgrupoItemsRel;

    /**
     * @return mixed
     */
    public function getCodigoSubgrupoPk()
    {
        return $this->codigoSubgrupoPk;
    }

    /**
     * @param mixed $codigoSubgrupoPk
     */
    public function setCodigoSubgrupoPk($codigoSubgrupoPk): void
    {
        $this->codigoSubgrupoPk = $codigoSubgrupoPk;
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
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk($codigoGrupoFk): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
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
    public function getGrupoRel()
    {
        return $this->grupoRel;
    }

    /**
     * @param mixed $grupoRel
     */
    public function setGrupoRel($grupoRel): void
    {
        $this->grupoRel = $grupoRel;
    }

    /**
     * @return mixed
     */
    public function getSubgrupoItemsRel()
    {
        return $this->subgrupoItemsRel;
    }

    /**
     * @param mixed $subgrupoItemsRel
     */
    public function setSubgrupoItemsRel($subgrupoItemsRel): void
    {
        $this->subgrupoItemsRel = $subgrupoItemsRel;
    }

}

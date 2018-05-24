<?php

namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvGrupo
 *
 * @ORM\Table(name="inv_grupo")
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvItemGrupoRepository")
 */
class InvItemGrupo
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoItemGrupoPk;

   /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @return int
     */
    public function getCodigoItemGrupoPk(): int
    {
        return $this->codigoItemGrupoPk;
    }

    /**
     * @param int $codigoItemGrupoPk
     */
    public function setCodigoItemGrupoPk(int $codigoItemGrupoPk): void
    {
        $this->codigoItemGrupoPk = $codigoItemGrupoPk;
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

}

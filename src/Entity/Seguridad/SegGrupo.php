<?php


namespace App\Entity\Seguridad;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Seguridad\SegGrupoRepository")
 */
class SegGrupo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_grupo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoGrupoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seguridad\Usuario", mappedBy="grupoRel")
     */
    protected $usuariosGrupoRel;

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
    public function getUsuariosGrupoRel()
    {
        return $this->usuariosGrupoRel;
    }

    /**
     * @param mixed $usuariosGrupoRel
     */
    public function setUsuariosGrupoRel($usuariosGrupoRel): void
    {
        $this->usuariosGrupoRel = $usuariosGrupoRel;
    }



}
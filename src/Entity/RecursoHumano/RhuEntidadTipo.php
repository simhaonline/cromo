<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEntidadTipoRepository")
 */

class RhuEntidadTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_tipo_pk", type="string", length=10)
     */
    private $codigoEntidadTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuEntidad",mappedBy="entidadTipoRel")
     */
    protected $entidadesEntidadTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoEntidadTipoPk()
    {
        return $this->codigoEntidadTipoPk;
    }

    /**
     * @param mixed $codigoEntidadTipoPk
     */
    public function setCodigoEntidadTipoPk($codigoEntidadTipoPk): void
    {
        $this->codigoEntidadTipoPk = $codigoEntidadTipoPk;
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
    public function getEntidadesEntidadTipoRel()
    {
        return $this->entidadesEntidadTipoRel;
    }

    /**
     * @param mixed $entidadesEntidadTipoRel
     */
    public function setEntidadesEntidadTipoRel($entidadesEntidadTipoRel): void
    {
        $this->entidadesEntidadTipoRel = $entidadesEntidadTipoRel;
    }
}

<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSubtipoCotizanteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSubtipoCotizante
{
    public $infoLog = [
        "primaryKey" => "codigoSubtipoCotizantePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_subtipo_cotizante_pk", type="string", length=10)
     */
    private $codigoSubtipoCotizantePk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="subtipoCotizanteRel")
     */
    protected $contratosSubtipoCotizanteRel;

    /**
     * @return mixed
     */
    public function getCodigoSubtipoCotizantePk()
    {
        return $this->codigoSubtipoCotizantePk;
    }

    /**
     * @param mixed $codigoSubtipoCotizantePk
     */
    public function setCodigoSubtipoCotizantePk($codigoSubtipoCotizantePk): void
    {
        $this->codigoSubtipoCotizantePk = $codigoSubtipoCotizantePk;
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
    public function getContratosSubtipoCotizanteRel()
    {
        return $this->contratosSubtipoCotizanteRel;
    }

    /**
     * @param mixed $contratosSubtipoCotizanteRel
     */
    public function setContratosSubtipoCotizanteRel($contratosSubtipoCotizanteRel): void
    {
        $this->contratosSubtipoCotizanteRel = $contratosSubtipoCotizanteRel;
    }
}

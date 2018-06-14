<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenEstudioTipoRepository")
 */
class GenEstudioTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_estudio_tipo_pk", type="string", length=10, nullable=true)
     */
    private $codigoEstudioTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="genEstudioTipoRel")
     */
    protected $rhuSolicitudesEstudioTiposRel;

    /**
     * @return mixed
     */
    public function getCodigoEstudioTipoPk()
    {
        return $this->codigoEstudioTipoPk;
    }

    /**
     * @param mixed $codigoEstudioTipoPk
     */
    public function setCodigoEstudioTipoPk($codigoEstudioTipoPk): void
    {
        $this->codigoEstudioTipoPk = $codigoEstudioTipoPk;
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
    public function getRhuSolicitudesEstudioTiposRel()
    {
        return $this->rhuSolicitudesEstudioTiposRel;
    }

    /**
     * @param mixed $rhuSolicitudesEstudioTiposRel
     */
    public function setRhuSolicitudesEstudioTiposRel($rhuSolicitudesEstudioTiposRel): void
    {
        $this->rhuSolicitudesEstudioTiposRel = $rhuSolicitudesEstudioTiposRel;
    }


}


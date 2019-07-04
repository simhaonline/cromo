<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurPuestoTipoRepository")
 */
class TurPuestoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_puesto_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPuestoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="puestoTipoRel")
     */
    protected $puestosPuestoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoPuestoTipoPk()
    {
        return $this->codigoPuestoTipoPk;
    }

    /**
     * @param mixed $codigoPuestoTipoPk
     */
    public function setCodigoPuestoTipoPk($codigoPuestoTipoPk): void
    {
        $this->codigoPuestoTipoPk = $codigoPuestoTipoPk;
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
    public function getPuestosPuestoTipoRel()
    {
        return $this->puestosPuestoTipoRel;
    }

    /**
     * @param mixed $puestosPuestoTipoRel
     */
    public function setPuestosPuestoTipoRel($puestosPuestoTipoRel): void
    {
        $this->puestosPuestoTipoRel = $puestosPuestoTipoRel;
    }


}


<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurZonaRepository")
 */
class TurZona
{

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_zona_pk", type="string", length=20)
     */
    private $codigoZonaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="zonaRel")
     */
    protected $puestosZonaRel;

    /**
     * @return mixed
     */
    public function getCodigoZonaPk()
    {
        return $this->codigoZonaPk;
    }

    /**
     * @param mixed $codigoZonaPk
     */
    public function setCodigoZonaPk($codigoZonaPk): void
    {
        $this->codigoZonaPk = $codigoZonaPk;
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
    public function getPuestosZonaRel()
    {
        return $this->puestosZonaRel;
    }

    /**
     * @param mixed $puestosZonaRel
     */
    public function setPuestosZonaRel($puestosZonaRel): void
    {
        $this->puestosZonaRel = $puestosZonaRel;
    }



}
<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenReligionRepository")
 */
class GenReligion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_religion_pk", type="string", length=10, nullable=true)
     */
    private $codigoReligionPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="religionRel")
     */
    protected $rhuSolicitudesReligicionRel;

    /**
     * @return mixed
     */
    public function getCodigoReligionPk()
    {
        return $this->codigoReligionPk;
    }

    /**
     * @param mixed $codigoReligionPk
     */
    public function setCodigoReligionPk($codigoReligionPk): void
    {
        $this->codigoReligionPk = $codigoReligionPk;
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
    public function getRhuSolicitudReligicionRel()
    {
        return $this->rhuSolicitudReligicionRel;
    }

    /**
     * @param mixed $rhuSolicitudReligicionRel
     */
    public function setRhuSolicitudReligicionRel($rhuSolicitudReligicionRel): void
    {
        $this->rhuSolicitudReligicionRel = $rhuSolicitudReligicionRel;
    }

}


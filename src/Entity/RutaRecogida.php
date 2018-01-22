<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RutaRecogidaRepository")
 */
class RutaRecogida
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoRutaRecogidaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="DespachoRecogida", mappedBy="rutaRecogidaRel")
     */
    protected $despachosRecogidasRutaRecogidaRel;

    /**
     * @return mixed
     */
    public function getCodigoRutaRecogidaPk()
    {
        return $this->codigoRutaRecogidaPk;
    }

    /**
     * @param mixed $codigoRutaRecogidaPk
     */
    public function setCodigoRutaRecogidaPk($codigoRutaRecogidaPk): void
    {
        $this->codigoRutaRecogidaPk = $codigoRutaRecogidaPk;
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
    public function getDespachosRecogidasRutaRecogidaRel()
    {
        return $this->despachosRecogidasRutaRecogidaRel;
    }

    /**
     * @param mixed $despachosRecogidasRutaRecogidaRel
     */
    public function setDespachosRecogidasRutaRecogidaRel($despachosRecogidasRutaRecogidaRel): void
    {
        $this->despachosRecogidasRutaRecogidaRel = $despachosRecogidasRutaRecogidaRel;
    }



}


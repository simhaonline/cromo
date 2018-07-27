<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoRecogidaTipoRepository")
 */
class TteDespachoRecogidaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoDespachoRecogidaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="genera_monitoreo", type="boolean", nullable=true, options={"default" : false})
     */
    private $generaMonitoreo = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespachoRecogida", mappedBy="despachoRecogidaTipoRel")
     */
    protected $despachosRecogidasDespachoRecogidaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoDespachoRecogidaTipoPk()
    {
        return $this->codigoDespachoRecogidaTipoPk;
    }

    /**
     * @param mixed $codigoDespachoRecogidaTipoPk
     */
    public function setCodigoDespachoRecogidaTipoPk($codigoDespachoRecogidaTipoPk): void
    {
        $this->codigoDespachoRecogidaTipoPk = $codigoDespachoRecogidaTipoPk;
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
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * @return mixed
     */
    public function getGeneraMonitoreo()
    {
        return $this->generaMonitoreo;
    }

    /**
     * @param mixed $generaMonitoreo
     */
    public function setGeneraMonitoreo($generaMonitoreo): void
    {
        $this->generaMonitoreo = $generaMonitoreo;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasDespachoRecogidaTipoRel()
    {
        return $this->despachosRecogidasDespachoRecogidaTipoRel;
    }

    /**
     * @param mixed $despachosRecogidasDespachoRecogidaTipoRel
     */
    public function setDespachosRecogidasDespachoRecogidaTipoRel($despachosRecogidasDespachoRecogidaTipoRel): void
    {
        $this->despachosRecogidasDespachoRecogidaTipoRel = $despachosRecogidasDespachoRecogidaTipoRel;
    }



}


<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteNovedadTipoRepository")
 */
class TteNovedadTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoNovedadTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TteNovedad", mappedBy="novedadTipoRel")
     */
    protected $novedadsNovedadTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoNovedadTipoPk()
    {
        return $this->codigoNovedadTipoPk;
    }

    /**
     * @param mixed $codigoNovedadTipoPk
     */
    public function setCodigoNovedadTipoPk($codigoNovedadTipoPk): void
    {
        $this->codigoNovedadTipoPk = $codigoNovedadTipoPk;
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
    public function getNovedadsNovedadTipoRel()
    {
        return $this->novedadsNovedadTipoRel;
    }

    /**
     * @param mixed $novedadsNovedadTipoRel
     */
    public function setNovedadsNovedadTipoRel($novedadsNovedadTipoRel): void
    {
        $this->novedadsNovedadTipoRel = $novedadsNovedadTipoRel;
    }

}


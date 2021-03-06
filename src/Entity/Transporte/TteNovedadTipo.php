<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteNovedadTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteNovedadTipo
{
    public $infoLog = [
        "primaryKey" => "codigoNovedadTipoPk",
        "todos"     => true,
    ];
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
     * @ORM\Column(name="interna", type="boolean", nullable=true, options={"default" : false})
     */
    private $interna = false;

    /**
     * @ORM\OneToMany(targetEntity="TteNovedad", mappedBy="novedadTipoRel")
     */
    protected $novedadesNovedadTipoRel;

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
    public function getNovedadesNovedadTipoRel()
    {
        return $this->novedadesNovedadTipoRel;
    }

    /**
     * @param mixed $novedadesNovedadTipoRel
     */
    public function setNovedadesNovedadTipoRel($novedadesNovedadTipoRel): void
    {
        $this->novedadesNovedadTipoRel = $novedadesNovedadTipoRel;
    }

    /**
     * @return mixed
     */
    public function getInterna()
    {
        return $this->interna;
    }

    /**
     * @param mixed $interna
     */
    public function setInterna($interna): void
    {
        $this->interna = $interna;
    }


}


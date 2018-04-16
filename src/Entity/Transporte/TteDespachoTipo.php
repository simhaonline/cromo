<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoTipoRepository")
 */
class TteDespachoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoDespachoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="exige_numero", type="boolean", nullable=true)
     */
    private $exigeNumero = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespacho", mappedBy="despachoTipoRel")
     */
    protected $despachosDespachoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoDespachoTipoPk()
    {
        return $this->codigoDespachoTipoPk;
    }

    /**
     * @param mixed $codigoDespachoTipoPk
     */
    public function setCodigoDespachoTipoPk($codigoDespachoTipoPk): void
    {
        $this->codigoDespachoTipoPk = $codigoDespachoTipoPk;
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
    public function getExigeNumero()
    {
        return $this->exigeNumero;
    }

    /**
     * @param mixed $exigeNumero
     */
    public function setExigeNumero($exigeNumero): void
    {
        $this->exigeNumero = $exigeNumero;
    }

    /**
     * @return mixed
     */
    public function getDespachosDespachoTipoRel()
    {
        return $this->despachosDespachoTipoRel;
    }

    /**
     * @param mixed $despachosDespachoTipoRel
     */
    public function setDespachosDespachoTipoRel($despachosDespachoTipoRel): void
    {
        $this->despachosDespachoTipoRel = $despachosDespachoTipoRel;
    }


}


<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteRutaRecogidaRepository")
 */
class TteRutaRecogida
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoRutaRecogidaPk;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="rutasRecogidasOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespachoRecogida", mappedBy="rutaRecogidaRel")
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
    public function getCodigoOperacionFk()
    {
        return $this->codigoOperacionFk;
    }

    /**
     * @param mixed $codigoOperacionFk
     */
    public function setCodigoOperacionFk($codigoOperacionFk): void
    {
        $this->codigoOperacionFk = $codigoOperacionFk;
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
    public function getOperacionRel()
    {
        return $this->operacionRel;
    }

    /**
     * @param mixed $operacionRel
     */
    public function setOperacionRel($operacionRel): void
    {
        $this->operacionRel = $operacionRel;
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


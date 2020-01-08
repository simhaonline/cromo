<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenSegmentoRepository")
 */
class GenSegmento
{

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_segmento_pk", type="string", length=10, nullable=false)
     */
    private $codigoSegmentoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCliente", mappedBy="segmentoRel")
     */
    protected $turClientesSegmentoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seguridad\Usuario",mappedBy="asesorRel")
     */
    protected $usuariosSegmentoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seguridad\SegUsuarioSegmento",mappedBy="segmentoRel")
     */
    protected $usuariosSegmentosSegmentoRel;

    /**
     * @return mixed
     */
    public function getCodigoSegmentoPk()
    {
        return $this->codigoSegmentoPk;
    }

    /**
     * @param mixed $codigoSegmentoPk
     */
    public function setCodigoSegmentoPk($codigoSegmentoPk): void
    {
        $this->codigoSegmentoPk = $codigoSegmentoPk;
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
    public function getTurClientesSegmentoRel()
    {
        return $this->turClientesSegmentoRel;
    }

    /**
     * @param mixed $turClientesSegmentoRel
     */
    public function setTurClientesSegmentoRel($turClientesSegmentoRel): void
    {
        $this->turClientesSegmentoRel = $turClientesSegmentoRel;
    }

    /**
     * @return mixed
     */
    public function getUsuariosSegmentoRel()
    {
        return $this->usuariosSegmentoRel;
    }

    /**
     * @param mixed $usuariosSegmentoRel
     */
    public function setUsuariosSegmentoRel($usuariosSegmentoRel): void
    {
        $this->usuariosSegmentoRel = $usuariosSegmentoRel;
    }


}


<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvSolicitudTipoRepository")
 */
class InvSolicitudTipo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoSolicitudTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer",options={"default" : 0},nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\OneToMany(targetEntity="InvSolicitud", mappedBy="solicitudTipoRel")
     */
    protected $solicitudTipoSolicitudRel;

    /**
     * @return mixed
     */
    public function getCodigoSolicitudTipoPk()
    {
        return $this->codigoSolicitudTipoPk;
    }

    /**
     * @param mixed $codigoSolicitudTipoPk
     */
    public function setCodigoSolicitudTipoPk($codigoSolicitudTipoPk): void
    {
        $this->codigoSolicitudTipoPk = $codigoSolicitudTipoPk;
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
    public function getSolicitudTipoSolicitudRel()
    {
        return $this->solicitudTipoSolicitudRel;
    }

    /**
     * @param mixed $solicitudTipoSolicitudRel
     */
    public function setSolicitudTipoSolicitudRel($solicitudTipoSolicitudRel): void
    {
        $this->solicitudTipoSolicitudRel = $solicitudTipoSolicitudRel;
    }
}


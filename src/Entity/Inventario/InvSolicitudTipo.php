<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvSolicitudTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoSolicitudTipoPk"},message="Ya existe el código del tipo")
 */
class InvSolicitudTipo
{
    public $infoLog = [
        "primaryKey" => "codigoSolicitudTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_solicitud_tipo_pk", type="string", length=10)
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
    protected $solicitudesSolicitudTipoRel;

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
    public function getSolicitudesSolicitudTipoRel()
    {
        return $this->solicitudesSolicitudTipoRel;
    }

    /**
     * @param mixed $solicitudesSolicitudTipoRel
     */
    public function setSolicitudesSolicitudTipoRel($solicitudesSolicitudTipoRel): void
    {
        $this->solicitudesSolicitudTipoRel = $solicitudesSolicitudTipoRel;
    }
}


<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteIntermediacionCompraRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteIntermediacionCompra
{
    public $infoLog = [
        "primaryKey" => "codigoIntermediacionCompraPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoIntermediacionCompraPk;

    /**
     * @ORM\Column(name="codigo_intermediacion_fk", type="integer", nullable=true)
     */
    private $codigoIntermediacionFk;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */
    private $anio;

    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */
    private $mes;

    /**
     * @ORM\Column(name="codigo_poseedor_fk", type="integer", nullable=true)
     */
    private $codigoPoseedorFk;

    /**
     * @ORM\Column(name="vr_flete", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_flete_participacion", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrFleteParticipacion = 0;

    /**
     * @ORM\Column(name="porcentaje_participacion", type="float", options={"default" : 0}, nullable=true)
     */
    private $porcentajeParticipacion = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteIntermediacion", inversedBy="intermediacionesComprasIntermediacionRel")
     * @ORM\JoinColumn(name="codigo_intermediacion_fk", referencedColumnName="codigo_intermediacion_pk")
     */
    private $intermediacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TtePoseedor", inversedBy="intermediacionesComprasPoseedorRel")
     * @ORM\JoinColumn(name="codigo_poseedor_fk", referencedColumnName="codigo_poseedor_pk")
     */
    private $poseedorRel;

    /**
     * @return mixed
     */
    public function getCodigoIntermediacionCompraPk()
    {
        return $this->codigoIntermediacionCompraPk;
    }

    /**
     * @param mixed $codigoIntermediacionCompraPk
     */
    public function setCodigoIntermediacionCompraPk($codigoIntermediacionCompraPk): void
    {
        $this->codigoIntermediacionCompraPk = $codigoIntermediacionCompraPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoIntermediacionFk()
    {
        return $this->codigoIntermediacionFk;
    }

    /**
     * @param mixed $codigoIntermediacionFk
     */
    public function setCodigoIntermediacionFk($codigoIntermediacionFk): void
    {
        $this->codigoIntermediacionFk = $codigoIntermediacionFk;
    }

    /**
     * @return mixed
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * @param mixed $anio
     */
    public function setAnio($anio): void
    {
        $this->anio = $anio;
    }

    /**
     * @return mixed
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param mixed $mes
     */
    public function setMes($mes): void
    {
        $this->mes = $mes;
    }

    /**
     * @return mixed
     */
    public function getCodigoPoseedorFk()
    {
        return $this->codigoPoseedorFk;
    }

    /**
     * @param mixed $codigoPoseedorFk
     */
    public function setCodigoPoseedorFk($codigoPoseedorFk): void
    {
        $this->codigoPoseedorFk = $codigoPoseedorFk;
    }

    /**
     * @return mixed
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * @param mixed $vrFlete
     */
    public function setVrFlete($vrFlete): void
    {
        $this->vrFlete = $vrFlete;
    }

    /**
     * @return mixed
     */
    public function getVrFleteParticipacion()
    {
        return $this->vrFleteParticipacion;
    }

    /**
     * @param mixed $vrFleteParticipacion
     */
    public function setVrFleteParticipacion($vrFleteParticipacion): void
    {
        $this->vrFleteParticipacion = $vrFleteParticipacion;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeParticipacion()
    {
        return $this->porcentajeParticipacion;
    }

    /**
     * @param mixed $porcentajeParticipacion
     */
    public function setPorcentajeParticipacion($porcentajeParticipacion): void
    {
        $this->porcentajeParticipacion = $porcentajeParticipacion;
    }

    /**
     * @return mixed
     */
    public function getIntermediacionRel()
    {
        return $this->intermediacionRel;
    }

    /**
     * @param mixed $intermediacionRel
     */
    public function setIntermediacionRel($intermediacionRel): void
    {
        $this->intermediacionRel = $intermediacionRel;
    }

    /**
     * @return mixed
     */
    public function getPoseedorRel()
    {
        return $this->poseedorRel;
    }

    /**
     * @param mixed $poseedorRel
     */
    public function setPoseedorRel($poseedorRel): void
    {
        $this->poseedorRel = $poseedorRel;
    }



}


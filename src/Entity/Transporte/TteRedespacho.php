<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteRedespachoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteRedespacho
{
    public $infoLog = [
        "primaryKey" => "codigoRedespachoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="codigo_redespacho_pk")
     */
    private $codigoRedespachoPk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */
    private $codigoGuiaFk;

    /**
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */
    private $codigoDespachoFk;

    /**
     * @ORM\Column(name="codigo_redespacho_motivo_fk", type="string", length=20,  nullable=true)
     */
    private $codigoRedespachoMotivoFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteGuia", inversedBy="redespachosGuiaRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    private $redespachoGuiaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteDespacho", inversedBy="redespachosDespachoRel")
     * @ORM\JoinColumn(name="codigo_despacho_fk", referencedColumnName="codigo_despacho_pk")
     */
    private $redespachoDespachoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteRedespachoMotivo", inversedBy="redespachoMotivoRel")
     * @ORM\JoinColumn(name="codigo_redespacho_motivo_fk", referencedColumnName="codigo_redespacho_motivo_pk")
     */
    private $redespachoMotivoRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoRedespachoPk()
    {
        return $this->codigoRedespachoPk;
    }

    /**
     * @param mixed $codigoRedespachoPk
     */
    public function setCodigoRedespachoPk($codigoRedespachoPk): void
    {
        $this->codigoRedespachoPk = $codigoRedespachoPk;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getCodigoGuiaFk()
    {
        return $this->codigoGuiaFk;
    }

    /**
     * @param mixed $codigoGuiaFk
     */
    public function setCodigoGuiaFk($codigoGuiaFk): void
    {
        $this->codigoGuiaFk = $codigoGuiaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoFk()
    {
        return $this->codigoDespachoFk;
    }

    /**
     * @param mixed $codigoDespachoFk
     */
    public function setCodigoDespachoFk($codigoDespachoFk): void
    {
        $this->codigoDespachoFk = $codigoDespachoFk;
    }

    /**
     * @return mixed
     */
    public function getRedespachoGuiaRel()
    {
        return $this->redespachoGuiaRel;
    }

    /**
     * @param mixed $redespachoGuiaRel
     */
    public function setRedespachoGuiaRel($redespachoGuiaRel): void
    {
        $this->redespachoGuiaRel = $redespachoGuiaRel;
    }

    /**
     * @return mixed
     */
    public function getRedespachoDespachoRel()
    {
        return $this->redespachoDespachoRel;
    }

    /**
     * @param mixed $redespachoDespachoRel
     */
    public function setRedespachoDespachoRel($redespachoDespachoRel): void
    {
        $this->redespachoDespachoRel = $redespachoDespachoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoRedespachoMotivoFk()
    {
        return $this->codigoRedespachoMotivoFk;
    }

    /**
     * @param mixed $codigoRedespachoMotivoFk
     */
    public function setCodigoRedespachoMotivoFk($codigoRedespachoMotivoFk): void
    {
        $this->codigoRedespachoMotivoFk = $codigoRedespachoMotivoFk;
    }

    /**
     * @return mixed
     */
    public function getRedespachoMotivoRel()
    {
        return $this->redespachoMotivoRel;
    }

    /**
     * @param mixed $redespachoMotivoRel
     */
    public function setRedespachoMotivoRel($redespachoMotivoRel): void
    {
        $this->redespachoMotivoRel = $redespachoMotivoRel;
    }


}


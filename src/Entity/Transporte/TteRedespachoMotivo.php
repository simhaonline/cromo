<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteRedespachoMotivoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteRedespachoMotivo
{
    public $infoLog = [
        "primaryKey" => "codigoRedespachoMotivoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_redespacho_motivo_pk" ,type="string", length=20, nullable=false, unique=true)
     */
    private $codigoRedespachoMotivoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteRedespacho", mappedBy="redespachoMotivoRel")
     */
    protected $redespachoMotivoRel;

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
    public function getCodigoRedespachoMotivoPk()
    {
        return $this->codigoRedespachoMotivoPk;
    }

    /**
     * @param mixed $codigoRedespachoMotivoPk
     */
    public function setCodigoRedespachoMotivoPk($codigoRedespachoMotivoPk): void
    {
        $this->codigoRedespachoMotivoPk = $codigoRedespachoMotivoPk;
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


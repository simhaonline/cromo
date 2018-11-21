<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuTipoCotizanteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuTipoCotizante
{
    public $infoLog = [
        "primaryKey" => "codigoTipoCotizantePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_cotizante_pk", type="string", length=10)
     */
    private $codigoTipoCotizantePk;   
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="tipoCotizanteRel")
     */
    protected $contratosTipoCotizanteRel;

    /**
     * @return mixed
     */
    public function getCodigoTipoCotizantePk()
    {
        return $this->codigoTipoCotizantePk;
    }

    /**
     * @param mixed $codigoTipoCotizantePk
     */
    public function setCodigoTipoCotizantePk($codigoTipoCotizantePk): void
    {
        $this->codigoTipoCotizantePk = $codigoTipoCotizantePk;
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
    public function getContratosTipoCotizanteRel()
    {
        return $this->contratosTipoCotizanteRel;
    }

    /**
     * @param mixed $contratosTipoCotizanteRel
     */
    public function setContratosTipoCotizanteRel($contratosTipoCotizanteRel): void
    {
        $this->contratosTipoCotizanteRel = $contratosTipoCotizanteRel;
    }
}

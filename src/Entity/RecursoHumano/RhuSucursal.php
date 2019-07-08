<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSucursalRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSucursal {

    public $infoLog = [
        "primaryKey" => "codigoSucursalPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sucursal_pk", type="string", length=10 )
     */
    private $codigoSucursalPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=160, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="estado_activo",type="boolean", nullable=true,options={"default":false})
     */
    private $estadoActivo = false;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="sucursalRel")
     */
    protected $contratosSucursalRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAportePlanilla", mappedBy="sucursalRel")
     */
    protected $aportesPlanillasSucursalRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporteContrato", mappedBy="sucursalRel")
     */
    protected $aportesContratosSucursalRel;

    /**
     * @return mixed
     */
    public function getCodigoSucursalPk()
    {
        return $this->codigoSucursalPk;
    }

    /**
     * @param mixed $codigoSucursalPk
     */
    public function setCodigoSucursalPk($codigoSucursalPk): void
    {
        $this->codigoSucursalPk = $codigoSucursalPk;
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
    public function getEstadoActivo()
    {
        return $this->estadoActivo;
    }

    /**
     * @param mixed $estadoActivo
     */
    public function setEstadoActivo($estadoActivo): void
    {
        $this->estadoActivo = $estadoActivo;
    }

    /**
     * @return mixed
     */
    public function getContratosSucursalRel()
    {
        return $this->contratosSucursalRel;
    }

    /**
     * @param mixed $contratosSucursalRel
     */
    public function setContratosSucursalRel($contratosSucursalRel): void
    {
        $this->contratosSucursalRel = $contratosSucursalRel;
    }

    /**
     * @return mixed
     */
    public function getAportesPlanillasSucursalRel()
    {
        return $this->aportesPlanillasSucursalRel;
    }

    /**
     * @param mixed $aportesPlanillasSucursalRel
     */
    public function setAportesPlanillasSucursalRel($aportesPlanillasSucursalRel): void
    {
        $this->aportesPlanillasSucursalRel = $aportesPlanillasSucursalRel;
    }

    /**
     * @return mixed
     */
    public function getAportesContratosSucursalRel()
    {
        return $this->aportesContratosSucursalRel;
    }

    /**
     * @param mixed $aportesContratosSucursalRel
     */
    public function setAportesContratosSucursalRel($aportesContratosSucursalRel): void
    {
        $this->aportesContratosSucursalRel = $aportesContratosSucursalRel;
    }


}


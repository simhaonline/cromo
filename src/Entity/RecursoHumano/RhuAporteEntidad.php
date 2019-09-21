<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAporteEntidadRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAporteEntidad
{
    public $infoLog = [
        "primaryKey" => "codigoAporteEntidadPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aporte_entidad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAporteEntidadPk;

    /**
     * @ORM\Column(name="codigo_aporte_fk", type="integer")
     */
    private $codigoAporteFk;

    /**
     * @ORM\Column(name="tipo", type="string", length=10, nullable=true)
     */
    private $tipo;

    /**
     * @ORM\Column(name="codigo_entidad", type="integer", nullable=true)
     */
    private $codigoEntidadFk;

    /**
     * @ORM\Column(name="vr_aporte", type="float" , nullable=true, options={"default" : 0})
     */
    private $vrAporte = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuAporte", inversedBy="aportesEntidadesAporteRel")
     * @ORM\JoinColumn(name="codigo_aporte_fk",referencedColumnName="codigo_aporte_pk")
     */
    protected $aporteRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="aportesEntidadesEntidadRel")
     * @ORM\JoinColumn(name="codigo_entidad_fk",referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadRel;

    /**
     * @return mixed
     */
    public function getCodigoAporteEntidadPk()
    {
        return $this->codigoAporteEntidadPk;
    }

    /**
     * @param mixed $codigoAporteEntidadPk
     */
    public function setCodigoAporteEntidadPk($codigoAporteEntidadPk): void
    {
        $this->codigoAporteEntidadPk = $codigoAporteEntidadPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAporteFk()
    {
        return $this->codigoAporteFk;
    }

    /**
     * @param mixed $codigoAporteFk
     */
    public function setCodigoAporteFk($codigoAporteFk): void
    {
        $this->codigoAporteFk = $codigoAporteFk;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadFk()
    {
        return $this->codigoEntidadFk;
    }

    /**
     * @param mixed $codigoEntidadFk
     */
    public function setCodigoEntidadFk($codigoEntidadFk): void
    {
        $this->codigoEntidadFk = $codigoEntidadFk;
    }

    /**
     * @return mixed
     */
    public function getAporteRel()
    {
        return $this->aporteRel;
    }

    /**
     * @param mixed $aporteRel
     */
    public function setAporteRel($aporteRel): void
    {
        $this->aporteRel = $aporteRel;
    }

    /**
     * @return mixed
     */
    public function getEntidadRel()
    {
        return $this->entidadRel;
    }

    /**
     * @param mixed $entidadRel
     */
    public function setEntidadRel($entidadRel): void
    {
        $this->entidadRel = $entidadRel;
    }

    /**
     * @return mixed
     */
    public function getVrAporte()
    {
        return $this->vrAporte;
    }

    /**
     * @param mixed $vrAporte
     */
    public function setVrAporte($vrAporte): void
    {
        $this->vrAporte = $vrAporte;
    }



}

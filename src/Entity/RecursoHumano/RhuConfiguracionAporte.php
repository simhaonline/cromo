<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="rhu_configuracion_aporte")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConfiguracionAporteRepository")
 */
class RhuConfiguracionAporte
{
    public $infoLog = [
        "primaryKey" => "codigoConfiguracionAportePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_aporte_pk", type="integer")
     */
    private $codigoConfiguracionAportePk;

    /**
     * @ORM\Column(name="codigo_entidad_riesgo_fk", type="integer", nullable=true)
     */
    private $codigoEntidadRiesgoFk;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidad", inversedBy="configuracionesAportesEntidadRiesgosRel")
     * @ORM\JoinColumn(name="codigo_entidad_riesgos_fk",referencedColumnName="codigo_entidad_pk")
     */
    protected $entidadRiesgosRel;

    /**
     * @return mixed
     */
    public function getCodigoConfiguracionAportePk()
    {
        return $this->codigoConfiguracionAportePk;
    }

    /**
     * @param mixed $codigoConfiguracionAportePk
     */
    public function setCodigoConfiguracionAportePk($codigoConfiguracionAportePk): void
    {
        $this->codigoConfiguracionAportePk = $codigoConfiguracionAportePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadRiesgoFk()
    {
        return $this->codigoEntidadRiesgoFk;
    }

    /**
     * @param mixed $codigoEntidadRiesgoFk
     */
    public function setCodigoEntidadRiesgoFk($codigoEntidadRiesgoFk): void
    {
        $this->codigoEntidadRiesgoFk = $codigoEntidadRiesgoFk;
    }

    /**
     * @return mixed
     */
    public function getEntidadRiesgosRel()
    {
        return $this->entidadRiesgosRel;
    }

    /**
     * @param mixed $entidadRiesgosRel
     */
    public function setEntidadRiesgosRel($entidadRiesgosRel): void
    {
        $this->entidadRiesgosRel = $entidadRiesgosRel;
    }



}

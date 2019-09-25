<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAccidenteDetalleParticipanteInvestigacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAccidenteDetalleParticipanteInvestigacion
{

    public $infoLog = [
        "primaryKey" => "codigoAccidenteAgentePk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_accidente_detalle_participante_investigacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccidenteDetalleParticipanteInvestigacionPk;

    /**
     * @ORM\Column(name="codigo_accidente_trabajo_fk", type="integer", nullable=true)
     */
    private $codigoAccidenteFk;

    /**
     * @ORM\Column(name="nombre_participante", type="string",length=100, nullable=true)
     */
    private $nombreParticipante;

    /**
     * @ORM\Column(name="cargo_participante", type="string",length=100, nullable=true)
     */
    private $cargoParticipante;

    /**
     * @return mixed
     */
    public function getCodigoAccidenteDetalleParticipanteInvestigacionPk()
    {
        return $this->codigoAccidenteDetalleParticipanteInvestigacionPk;
    }

    /**
     * @param mixed $codigoAccidenteDetalleParticipanteInvestigacionPk
     */
    public function setCodigoAccidenteDetalleParticipanteInvestigacionPk($codigoAccidenteDetalleParticipanteInvestigacionPk): void
    {
        $this->codigoAccidenteDetalleParticipanteInvestigacionPk = $codigoAccidenteDetalleParticipanteInvestigacionPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAccidenteFk()
    {
        return $this->codigoAccidenteFk;
    }

    /**
     * @param mixed $codigoAccidenteFk
     */
    public function setCodigoAccidenteFk($codigoAccidenteFk): void
    {
        $this->codigoAccidenteFk = $codigoAccidenteFk;
    }

    /**
     * @return mixed
     */
    public function getNombreParticipante()
    {
        return $this->nombreParticipante;
    }

    /**
     * @param mixed $nombreParticipante
     */
    public function setNombreParticipante($nombreParticipante): void
    {
        $this->nombreParticipante = $nombreParticipante;
    }

    /**
     * @return mixed
     */
    public function getCargoParticipante()
    {
        return $this->cargoParticipante;
    }

    /**
     * @param mixed $cargoParticipante
     */
    public function setCargoParticipante($cargoParticipante): void
    {
        $this->cargoParticipante = $cargoParticipante;
    }




}

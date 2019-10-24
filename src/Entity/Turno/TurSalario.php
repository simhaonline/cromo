<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurSalarioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurSalario
{
    public $infoLog = [
        "primaryKey" => "codigoSalarioPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_salario_pk", type="string", length=10)
     */
    private $codigoSalarioPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="vr_salario", type="float", nullable=true)
     */
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="vr_hora_diurna", type="float", nullable=true)
     */
    private $vrHoraDiurna = 0;

    /**
     * @ORM\Column(name="vr_hora_nocturna", type="float", nullable=true)
     */
    private $vrHoraNocturna = 0;

    /**
     * @ORM\Column(name="vr_turno_dia", type="float", nullable=true)
     */
    private $vrTurnoDia = 0;

    /**
     * @ORM\Column(name="vr_turno_noche", type="float", nullable=true)
     */
    private $vrTurnoNoche = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurPuesto", mappedBy="salarioRel")
     */
    protected $puestosSalariosRel;

    /**
     * @return mixed
     */
    public function getCodigoSalarioPk()
    {
        return $this->codigoSalarioPk;
    }

    /**
     * @param mixed $codigoSalarioPk
     */
    public function setCodigoSalarioPk($codigoSalarioPk): void
    {
        $this->codigoSalarioPk = $codigoSalarioPk;
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
    public function getVrHoraDiurna()
    {
        return $this->vrHoraDiurna;
    }

    /**
     * @param mixed $vrHoraDiurna
     */
    public function setVrHoraDiurna($vrHoraDiurna): void
    {
        $this->vrHoraDiurna = $vrHoraDiurna;
    }

    /**
     * @return mixed
     */
    public function getVrHoraNocturna()
    {
        return $this->vrHoraNocturna;
    }

    /**
     * @param mixed $vrHoraNocturna
     */
    public function setVrHoraNocturna($vrHoraNocturna): void
    {
        $this->vrHoraNocturna = $vrHoraNocturna;
    }

    /**
     * @return mixed
     */
    public function getVrTurnoDia()
    {
        return $this->vrTurnoDia;
    }

    /**
     * @param mixed $vrTurnoDia
     */
    public function setVrTurnoDia($vrTurnoDia): void
    {
        $this->vrTurnoDia = $vrTurnoDia;
    }

    /**
     * @return mixed
     */
    public function getVrTurnoNoche()
    {
        return $this->vrTurnoNoche;
    }

    /**
     * @param mixed $vrTurnoNoche
     */
    public function setVrTurnoNoche($vrTurnoNoche): void
    {
        $this->vrTurnoNoche = $vrTurnoNoche;
    }

    /**
     * @return mixed
     */
    public function getPuestosSalariosRel()
    {
        return $this->puestosSalariosRel;
    }

    /**
     * @param mixed $puestosSalariosRel
     */
    public function setPuestosSalariosRel($puestosSalariosRel): void
    {
        $this->puestosSalariosRel = $puestosSalariosRel;
    }

    /**
     * @return mixed
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * @param mixed $vrSalario
     */
    public function setVrSalario($vrSalario): void
    {
        $this->vrSalario = $vrSalario;
    }



}


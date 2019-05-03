<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurProgramadorRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurProgramador
{
    public $infoLog = [
        "primaryKey" => "codigoProgramacionPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programador_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramadorPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=300)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     max = 300,
     *     maxMessage="El campo no puede contener mas de 300 caracteres"
     * )
     */
    private $nombre;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurPuesto", mappedBy="programadorRel")
     */
    protected $turProgramadorPuestosRel;

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
    public function getCodigoProgramadorPk()
    {
        return $this->codigoProgramadorPk;
    }

    /**
     * @param mixed $codigoProgramadorPk
     */
    public function setCodigoProgramadorPk($codigoProgramadorPk): void
    {
        $this->codigoProgramadorPk = $codigoProgramadorPk;
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
    public function getTurProgramadorPuestosRel()
    {
        return $this->turProgramadorPuestosRel;
    }

    /**
     * @param mixed $turProgramadorPuestosRel
     */
    public function setTurProgramadorPuestosRel($turProgramadorPuestosRel): void
    {
        $this->turProgramadorPuestosRel = $turProgramadorPuestosRel;
    }

}
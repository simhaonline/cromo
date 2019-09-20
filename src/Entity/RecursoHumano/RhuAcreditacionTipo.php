<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAcreditacionTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAcreditacionTipo
{
    public $infoLog = [
        "primaryKey" => "codigoAcreditacionTipoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_acreditacion_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAcreditacionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_interno", type="integer", nullable=true)
     */
    private $codigoInterno;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\OneToMany(targetEntity="RhuAcreditacion", mappedBy="acreditacionTipoRel")
     */
    protected $acreditacionesAcreditacionTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="acreditacionesTiposCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

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
    public function getCodigoAcreditacionTipoPk()
    {
        return $this->codigoAcreditacionTipoPk;
    }

    /**
     * @param mixed $codigoAcreditacionTipoPk
     */
    public function setCodigoAcreditacionTipoPk($codigoAcreditacionTipoPk): void
    {
        $this->codigoAcreditacionTipoPk = $codigoAcreditacionTipoPk;
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
    public function getCodigoInterno()
    {
        return $this->codigoInterno;
    }

    /**
     * @param mixed $codigoInterno
     */
    public function setCodigoInterno($codigoInterno): void
    {
        $this->codigoInterno = $codigoInterno;
    }

    /**
     * @return mixed
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * @param mixed $codigoCargoFk
     */
    public function setCodigoCargoFk($codigoCargoFk): void
    {
        $this->codigoCargoFk = $codigoCargoFk;
    }

    /**
     * @return mixed
     */
    public function getAcreditacionesAcreditacionTipoRel()
    {
        return $this->acreditacionesAcreditacionTipoRel;
    }

    /**
     * @param mixed $acreditacionesAcreditacionTipoRel
     */
    public function setAcreditacionesAcreditacionTipoRel($acreditacionesAcreditacionTipoRel): void
    {
        $this->acreditacionesAcreditacionTipoRel = $acreditacionesAcreditacionTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * @param mixed $cargoRel
     */
    public function setCargoRel($cargoRel): void
    {
        $this->cargoRel = $cargoRel;
    }


}

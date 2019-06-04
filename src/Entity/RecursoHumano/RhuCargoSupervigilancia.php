<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCargoSupervigilanciaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuCargoSupervigilancia
{
    public $infoLog = [
        "primaryKey" => "codigoCargoSupervigilanciaPk",
        "todos"     => true,
    ];

     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cargo_supervigilancia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCargoSupervigilanciaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;
        
    /**
     * @ORM\OneToMany(targetEntity="RhuCargo", mappedBy="cargoSupervigilanciaRel")
     */
    protected $cargosCargoSupervigilanciaRel;

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
    public function getCodigoCargoSupervigilanciaPk()
    {
        return $this->codigoCargoSupervigilanciaPk;
    }

    /**
     * @param mixed $codigoCargoSupervigilanciaPk
     */
    public function setCodigoCargoSupervigilanciaPk($codigoCargoSupervigilanciaPk): void
    {
        $this->codigoCargoSupervigilanciaPk = $codigoCargoSupervigilanciaPk;
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
    public function getCargosCargoSupervigilanciaRel()
    {
        return $this->cargosCargoSupervigilanciaRel;
    }

    /**
     * @param mixed $cargosCargoSupervigilanciaRel
     */
    public function setCargosCargoSupervigilanciaRel($cargosCargoSupervigilanciaRel): void
    {
        $this->cargosCargoSupervigilanciaRel = $cargosCargoSupervigilanciaRel;
    }
}

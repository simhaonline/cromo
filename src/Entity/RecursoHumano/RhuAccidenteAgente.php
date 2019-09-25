<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAccidenteAgenteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAccidenteAgente {

    public $infoLog = [
        "primaryKey" => "codigoAccidenteAgentePk",
        "todos" => true,
    ];

    /**
     * @ORM\Column(name="codigo_accidente_agente_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccidenteAgentePk;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @return mixed
     */
    public function getCodigoAccidenteAgentePk()
    {
        return $this->codigoAccidenteAgentePk;
    }

    /**
     * @param mixed $codigoAccidenteAgentePk
     */
    public function setCodigoAccidenteAgentePk($codigoAccidenteAgentePk): void
    {
        $this->codigoAccidenteAgentePk = $codigoAccidenteAgentePk;
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
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }



}

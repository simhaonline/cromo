<?php

namespace App\Entity\Seguridad;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Seguridad\SegGrupoProcesoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class SegGrupoProceso
{
    public $infoLog = [
        "primaryKey" => "codigoGrupoProcesoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_grupo_proceso_pk")
     */
    private $codigoGrupoProcesoPk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="integer")
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="codigo_proceso_fk", type="string", length=10)
     */
    private $codigoProcesoFk;

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
    public function getCodigoGrupoProcesoPk()
    {
        return $this->codigoGrupoProcesoPk;
    }

    /**
     * @param mixed $codigoGrupoProcesoPk
     */
    public function setCodigoGrupoProcesoPk($codigoGrupoProcesoPk): void
    {
        $this->codigoGrupoProcesoPk = $codigoGrupoProcesoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk($codigoGrupoFk): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoProcesoFk()
    {
        return $this->codigoProcesoFk;
    }

    /**
     * @param mixed $codigoProcesoFk
     */
    public function setCodigoProcesoFk($codigoProcesoFk): void
    {
        $this->codigoProcesoFk = $codigoProcesoFk;
    }


}

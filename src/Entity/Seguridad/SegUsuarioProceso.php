<?php

namespace App\Entity\Seguridad;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Seguridad\SegUsuarioProcesoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class SegUsuarioProceso
{
    public $infoLog = [
        "primaryKey" => "codigoSeguridadUsuarioProcesoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_seguridad_usuario_proceso_pk", unique=true)
     */
    private $codigoSeguridadUsuarioProcesoPk;

    /**
     * @ORM\Column(name="codigo_usuario_fk", type="string")
     */
    private $codigoUsuarioFk;

    /**
     * @ORM\Column(name="codigo_proceso_fk", type="string", length=10)
     */
    private $codigoProcesoFk;

    /**
     * @return mixed
     */
    public function getCodigoSeguridadUsuarioProcesoPk()
    {
        return $this->codigoSeguridadUsuarioProcesoPk;
    }

    /**
     * @param mixed $codigoSeguridadUsuarioProcesoPk
     */
    public function setCodigoSeguridadUsuarioProcesoPk($codigoSeguridadUsuarioProcesoPk): void
    {
        $this->codigoSeguridadUsuarioProcesoPk = $codigoSeguridadUsuarioProcesoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * @param mixed $codigoUsuarioFk
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk): void
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;
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

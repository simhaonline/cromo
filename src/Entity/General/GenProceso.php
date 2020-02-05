<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenProcesoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenProceso
{
    public $infoLog = [
        "primaryKey" => "codigoProcesoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, name="codigo_proceso_pk")
     */
    private $codigoProcesoPk;

    /**
     * @ORM\Column(name="codigo_proceso_tipo_fk", length=1, type="string")
     */
    private $codigoProcesoTipoFk;

    /**
     * @ORM\Column(name="codigo_modulo_fk", length=80, type="string")
     */
    private $codigoModuloFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=400, nullable=true)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoProcesoPk()
    {
        return $this->codigoProcesoPk;
    }

    /**
     * @param mixed $codigoProcesoPk
     */
    public function setCodigoProcesoPk($codigoProcesoPk): void
    {
        $this->codigoProcesoPk = $codigoProcesoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoProcesoTipoFk()
    {
        return $this->codigoProcesoTipoFk;
    }

    /**
     * @param mixed $codigoProcesoTipoFk
     */
    public function setCodigoProcesoTipoFk($codigoProcesoTipoFk): void
    {
        $this->codigoProcesoTipoFk = $codigoProcesoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoModuloFk()
    {
        return $this->codigoModuloFk;
    }

    /**
     * @param mixed $codigoModuloFk
     */
    public function setCodigoModuloFk($codigoModuloFk): void
    {
        $this->codigoModuloFk = $codigoModuloFk;
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



}


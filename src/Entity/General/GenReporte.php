<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenReporteRepository")
 */
class GenReporte
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, nullable=false, unique=true)
     */
    private $codigoReportePk;

    /**
     * @ORM\Column(name="modulo", type="string", length=50, nullable=true)
     */
    private $modulo;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="archivo", type="string", length=100, nullable=true)
     */
    private $archivo;

    /**
     * @return mixed
     */
    public function getCodigoReportePk()
    {
        return $this->codigoReportePk;
    }

    /**
     * @param mixed $codigoReportePk
     */
    public function setCodigoReportePk($codigoReportePk): void
    {
        $this->codigoReportePk = $codigoReportePk;
    }

    /**
     * @return mixed
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * @param mixed $modulo
     */
    public function setModulo($modulo): void
    {
        $this->modulo = $modulo;
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
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * @param mixed $archivo
     */
    public function setArchivo($archivo): void
    {
        $this->archivo = $archivo;
    }



}


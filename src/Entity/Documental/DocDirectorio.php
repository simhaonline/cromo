<?php


namespace App\Entity\Documental;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Documental\DocDirectorioRepository")
 */
class DocDirectorio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoDirectorioPk;

    /**
     * @ORM\Column(name="tipo", type="string", length=1, nullable=true)
     */
    private $tipo;

    /**
     * @ORM\Column(name="clase", type="string", length=50, nullable=true)
     */
    private $clase;

    /**
     * @ORM\Column(name="codigo_masivo_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoMasivoTipoFk;

    /**
     * @ORM\Column(name="directorio", type="integer", options={"default" : 0})
     */
    private $directorio = 0;

    /**
     * @ORM\Column(name="numero_archivos", type="integer", options={"default" : 0})
     */
    private $numeroArchivos = 0;

    /**
     * @return mixed
     */
    public function getCodigoDirectorioPk()
    {
        return $this->codigoDirectorioPk;
    }

    /**
     * @param mixed $codigoDirectorioPk
     */
    public function setCodigoDirectorioPk($codigoDirectorioPk): void
    {
        $this->codigoDirectorioPk = $codigoDirectorioPk;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getClase()
    {
        return $this->clase;
    }

    /**
     * @param mixed $clase
     */
    public function setClase($clase): void
    {
        $this->clase = $clase;
    }

    /**
     * @return mixed
     */
    public function getCodigoMasivoTipoFk()
    {
        return $this->codigoMasivoTipoFk;
    }

    /**
     * @param mixed $codigoMasivoTipoFk
     */
    public function setCodigoMasivoTipoFk($codigoMasivoTipoFk): void
    {
        $this->codigoMasivoTipoFk = $codigoMasivoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getDirectorio()
    {
        return $this->directorio;
    }

    /**
     * @param mixed $directorio
     */
    public function setDirectorio($directorio): void
    {
        $this->directorio = $directorio;
    }

    /**
     * @return mixed
     */
    public function getNumeroArchivos()
    {
        return $this->numeroArchivos;
    }

    /**
     * @param mixed $numeroArchivos
     */
    public function setNumeroArchivos($numeroArchivos): void
    {
        $this->numeroArchivos = $numeroArchivos;
    }


}


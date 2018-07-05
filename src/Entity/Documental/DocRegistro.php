<?php


namespace App\Entity\Documental;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Documental\DocRegistroRepository")
 */
class DocRegistro
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoRegistroPk;

    /**
     * @ORM\Column(name="identificador", type="string", length=50, nullable=true)
     */
    private $identificador;

    /**
     * @ORM\Column(name="codigo_masivo_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoMasivoTipoFk;

    /**
     * @ORM\Column(name="archivo", type="string", length=200, nullable=true)
     */
    private $archivo;

    /**
     * @ORM\Column(name="directorio", type="string", length=200, nullable=true)
     */
    private $directorio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Documental\DocMasivoTipo", inversedBy="registrosMasivoTipoRel")
     * @ORM\JoinColumn(name="codigo_masivo_tipo_fk", referencedColumnName="codigo_masivo_tipo_pk")
     */
    protected $masivoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoRegistroPk()
    {
        return $this->codigoRegistroPk;
    }

    /**
     * @param mixed $codigoRegistroPk
     */
    public function setCodigoRegistroPk($codigoRegistroPk): void
    {
        $this->codigoRegistroPk = $codigoRegistroPk;
    }

    /**
     * @return mixed
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * @param mixed $identificador
     */
    public function setIdentificador($identificador): void
    {
        $this->identificador = $identificador;
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
    public function getMasivoTipoRel()
    {
        return $this->masivoTipoRel;
    }

    /**
     * @param mixed $masivoTipoRel
     */
    public function setMasivoTipoRel($masivoTipoRel): void
    {
        $this->masivoTipoRel = $masivoTipoRel;
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



}


<?php


namespace App\Entity\Documental;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Documental\DocMasivoTipoRepository")
 */
class DocMasivoTipo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="string", length=20)
     */
    private $codigoMasivoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Documental\DocRegistro", mappedBy="masivoTipoRel")
     */
    protected $registrosMasivoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoMasivoTipoPk()
    {
        return $this->codigoMasivoTipoPk;
    }

    /**
     * @param mixed $codigoMasivoTipoPk
     */
    public function setCodigoMasivoTipoPk($codigoMasivoTipoPk): void
    {
        $this->codigoMasivoTipoPk = $codigoMasivoTipoPk;
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
    public function getRegistrosMasivoTipoRel()
    {
        return $this->registrosMasivoTipoRel;
    }

    /**
     * @param mixed $registrosMasivoTipoRel
     */
    public function setRegistrosMasivoTipoRel($registrosMasivoTipoRel): void
    {
        $this->registrosMasivoTipoRel = $registrosMasivoTipoRel;
    }



}


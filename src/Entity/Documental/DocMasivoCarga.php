<?php


namespace App\Entity\Documental;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Documental\DocMasivoCargaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class DocMasivoCarga
{
    public $infoLog = [
        "primaryKey" => "codigoMasivoCargaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoMasivoCargaPk;

    /**
     * @ORM\Column(name="identificador", type="string", length=50, nullable=true)
     */
    private $identificador;

    /**
     * @ORM\Column(name="archivo", type="string", length=200, nullable=true)
     */
    private $archivo;

    /**
     * @ORM\Column(name="extension", type="string", length=10, nullable=true)
     */
    private $extension;

    /**
     * @ORM\Column(name="tamano", type="float", options={"default" : 0})
     */
    private $tamano = 0;

    /**
     * @return mixed
     */
    public function getCodigoMasivoCargaPk()
    {
        return $this->codigoMasivoCargaPk;
    }

    /**
     * @param mixed $codigoMasivoCargaPk
     */
    public function setCodigoMasivoCargaPk($codigoMasivoCargaPk): void
    {
        $this->codigoMasivoCargaPk = $codigoMasivoCargaPk;
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
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension): void
    {
        $this->extension = $extension;
    }

    /**
     * @return mixed
     */
    public function getTamano()
    {
        return $this->tamano;
    }

    /**
     * @param mixed $tamano
     */
    public function setTamano($tamano): void
    {
        $this->tamano = $tamano;
    }



}


<?php


namespace App\Entity\Documental;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Documental\DocImagenRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class DocImagen
{
    public $infoLog = [
        "primaryKey" => "codigoImagenPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoImagenPk;

    /**
     * @ORM\Column(name="identificador", type="string", length=50, nullable=true)
     */
    private $identificador;

    /**
     * @ORM\Column(type="string", length=80, name="codigo_modelo_fk", nullable=false)
     */
    private $codigoModeloFk;

    /**
     * @ORM\Column(name="archivo", type="string", length=200, nullable=true)
     */
    private $archivo;

    /**
     * @ORM\Column(name="archivo_destino", type="string", length=200, nullable=true)
     */
    private $archivoDestino;

    /**
     * @ORM\Column(name="directorio", type="string", length=200, nullable=true)
     */
    private $directorio;

    /**
     * @ORM\Column(name="extension", type="string", length=10, nullable=true)
     */
    private $extension;

    /**
     * @ORM\Column(name="tamano", type="float", options={"default" : 0})
     */
    private $tamano = 0;

    /**
     * @ORM\Column(name="nombre", type="string", length=500, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="extension_original", type="string", length=250, nullable=true)
     */
    private $extensionOriginal;

    /**
     * @ORM\Column(name="tipo", type="string", length=250, nullable=true)
     */
    private $tipo;

    /**
     * @ORM\Column(name="fecha_hasta", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @return mixed
     */
    public function getCodigoImagenPk()
    {
        return $this->codigoImagenPk;
    }

    /**
     * @param mixed $codigoImagenPk
     */
    public function setCodigoImagenPk( $codigoImagenPk ): void
    {
        $this->codigoImagenPk = $codigoImagenPk;
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
    public function setIdentificador( $identificador ): void
    {
        $this->identificador = $identificador;
    }

    /**
     * @return mixed
     */
    public function getCodigoModeloFk()
    {
        return $this->codigoModeloFk;
    }

    /**
     * @param mixed $codigoModeloFk
     */
    public function setCodigoModeloFk( $codigoModeloFk ): void
    {
        $this->codigoModeloFk = $codigoModeloFk;
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
    public function setArchivo( $archivo ): void
    {
        $this->archivo = $archivo;
    }

    /**
     * @return mixed
     */
    public function getArchivoDestino()
    {
        return $this->archivoDestino;
    }

    /**
     * @param mixed $archivoDestino
     */
    public function setArchivoDestino( $archivoDestino ): void
    {
        $this->archivoDestino = $archivoDestino;
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
    public function setDirectorio( $directorio ): void
    {
        $this->directorio = $directorio;
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
    public function setExtension( $extension ): void
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
    public function setTamano( $tamano ): void
    {
        $this->tamano = $tamano;
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
    public function setNombre( $nombre ): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getExtensionOriginal()
    {
        return $this->extensionOriginal;
    }

    /**
     * @param mixed $extensionOriginal
     */
    public function setExtensionOriginal( $extensionOriginal ): void
    {
        $this->extensionOriginal = $extensionOriginal;
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
    public function setTipo( $tipo ): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha( $fecha ): void
    {
        $this->fecha = $fecha;
    }

    


}


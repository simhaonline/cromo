<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenCalidadFormatoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenFormato
{
    public $infoLog = [
        "primaryKey" => "codigoFormatoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_formato_pk", type="integer")
     */
    private $codigoFormatoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="titulo", type="string", length=300, nullable=true)
     */
    private $titulo;

    /**
     * @ORM\Column(name="contenido", type="text", nullable=true)
     */
    private $contenido;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_actualizacion", type="datetime", nullable=true)
     */
    private $fechaActualizacion;

    /**
     * @ORM\Column(name="nombre_firma", type="string", length=50, nullable=true)
     */
    private $nombreFirma;

    /**
     * @ORM\Column(name="cargo_firma", type="string", length=150, nullable=true)
     */
    private $cargoFirma;

    /**
     * @ORM\Column(name="version", type="string", length=20, nullable=true)
     */
    private $version;

    /**
     * @ORM\Column(name="codigo_modelo_fk",type="string", length=80, nullable=true)
     */
    private $codigoModeloFk;

    /**
     * @ORM\Column(name="codigo",type="string", length=20, nullable=true)
     */
    private $codigo;

    /**
     * @ORM\Column(name="etiquetas", type="text", nullable=true)
     */
    private $etiquetas;

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
    public function getCodigoFormatoPk()
    {
        return $this->codigoFormatoPk;
    }

    /**
     * @param mixed $codigoFormatoPk
     */
    public function setCodigoFormatoPk($codigoFormatoPk): void
    {
        $this->codigoFormatoPk = $codigoFormatoPk;
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
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo): void
    {
        $this->titulo = $titulo;
    }

    /**
     * @return mixed
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * @param mixed $contenido
     */
    public function setContenido($contenido): void
    {
        $this->contenido = $contenido;
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
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * @param mixed $fechaActualizacion
     */
    public function setFechaActualizacion($fechaActualizacion): void
    {
        $this->fechaActualizacion = $fechaActualizacion;
    }

    /**
     * @return mixed
     */
    public function getNombreFirma()
    {
        return $this->nombreFirma;
    }

    /**
     * @param mixed $nombreFirma
     */
    public function setNombreFirma($nombreFirma): void
    {
        $this->nombreFirma = $nombreFirma;
    }

    /**
     * @return mixed
     */
    public function getCargoFirma()
    {
        return $this->cargoFirma;
    }

    /**
     * @param mixed $cargoFirma
     */
    public function setCargoFirma($cargoFirma): void
    {
        $this->cargoFirma = $cargoFirma;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version): void
    {
        $this->version = $version;
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
    public function setCodigoModeloFk($codigoModeloFk): void
    {
        $this->codigoModeloFk = $codigoModeloFk;
    }

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo): void
    {
        $this->codigo = $codigo;
    }

    /**
     * @return mixed
     */
    public function getEtiquetas()
    {
        return $this->etiquetas;
    }

    /**
     * @param mixed $etiquetas
     */
    public function setEtiquetas($etiquetas): void
    {
        $this->etiquetas = $etiquetas;
    }

}


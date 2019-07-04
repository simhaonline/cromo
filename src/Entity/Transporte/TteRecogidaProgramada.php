<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteRecogidaProgramadaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteRecogidaProgramada
{
    public $infoLog = [
        "primaryKey" => "codigoRecogidaProgramadaPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoRecogidaProgramadaPk;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_ruta_recogida_fk", type="string", length=20, nullable=true)
     */
    private $codigoRutaRecogidaFk;

    /**
     * @ORM\Column(name="fecha_ultima_generada", type="date", nullable=true)
     */
    private $fechaUltimaGenerada;

    /**
     * @ORM\Column(name="hora", type="time", nullable=true)
     */
    private $hora;

    /**
     * @ORM\Column(name="anunciante", type="string", length=200, nullable=true)
     */
    private $anunciante;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadFk;


    /**
     * @ORM\Column(name="direccion", type="string", length=200, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=50, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="recogidasProgramadasOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="recogidasProgramadasCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="recogidasProgramadasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteRutaRecogida", inversedBy="recogidasProgramadasRutaRecogidaRel")
     * @ORM\JoinColumn(name="codigo_ruta_recogida_fk", referencedColumnName="codigo_ruta_recogida_pk")
     */
    private $rutaRecogidaRel;

    /**
     * @return mixed
     */
    public function getCodigoRecogidaProgramadaPk()
    {
        return $this->codigoRecogidaProgramadaPk;
    }

    /**
     * @param mixed $codigoRecogidaProgramadaPk
     */
    public function setCodigoRecogidaProgramadaPk($codigoRecogidaProgramadaPk): void
    {
        $this->codigoRecogidaProgramadaPk = $codigoRecogidaProgramadaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOperacionFk()
    {
        return $this->codigoOperacionFk;
    }

    /**
     * @param mixed $codigoOperacionFk
     */
    public function setCodigoOperacionFk($codigoOperacionFk): void
    {
        $this->codigoOperacionFk = $codigoOperacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * @param mixed $codigoClienteFk
     */
    public function setCodigoClienteFk($codigoClienteFk): void
    {
        $this->codigoClienteFk = $codigoClienteFk;
    }

    /**
     * @return mixed
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param mixed $hora
     */
    public function setHora($hora): void
    {
        $this->hora = $hora;
    }

    /**
     * @return mixed
     */
    public function getAnunciante()
    {
        return $this->anunciante;
    }

    /**
     * @param mixed $anunciante
     */
    public function setAnunciante($anunciante): void
    {
        $this->anunciante = $anunciante;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * @param mixed $codigoCiudadFk
     */
    public function setCodigoCiudadFk($codigoCiudadFk): void
    {
        $this->codigoCiudadFk = $codigoCiudadFk;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return mixed
     */
    public function getOperacionRel()
    {
        return $this->operacionRel;
    }

    /**
     * @param mixed $operacionRel
     */
    public function setOperacionRel($operacionRel): void
    {
        $this->operacionRel = $operacionRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * @param mixed $ciudadRel
     */
    public function setCiudadRel($ciudadRel): void
    {
        $this->ciudadRel = $ciudadRel;
    }

    /**
     * @return mixed
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * @param mixed $clienteRel
     */
    public function setClienteRel($clienteRel): void
    {
        $this->clienteRel = $clienteRel;
    }

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
    public function getCodigoRutaRecogidaFk()
    {
        return $this->codigoRutaRecogidaFk;
    }

    /**
     * @param mixed $codigoRutaRecogidaFk
     */
    public function setCodigoRutaRecogidaFk($codigoRutaRecogidaFk): void
    {
        $this->codigoRutaRecogidaFk = $codigoRutaRecogidaFk;
    }

    /**
     * @return mixed
     */
    public function getRutaRecogidaRel()
    {
        return $this->rutaRecogidaRel;
    }

    /**
     * @param mixed $rutaRecogidaRel
     */
    public function setRutaRecogidaRel($rutaRecogidaRel): void
    {
        $this->rutaRecogidaRel = $rutaRecogidaRel;
    }

    /**
     * @return mixed
     */
    public function getFechaUltimaGenerada()
    {
        return $this->fechaUltimaGenerada;
    }

    /**
     * @param mixed $fechaUltimaGenerada
     */
    public function setFechaUltimaGenerada($fechaUltimaGenerada): void
    {
        $this->fechaUltimaGenerada = $fechaUltimaGenerada;
    }



}


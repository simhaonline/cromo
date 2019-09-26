<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCapacitacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuCapacitacion
{
    public $infoLog = [
        "primaryKey" => "codigoCapacitacionPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionPk;

    /**
     * @ORM\Column(name="codigo_capacitacion_tipo_fk", type="integer", nullable=true)
     */
    private $codigoCapacitacionTipoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_capacitacion", type="datetime", nullable=true)
     */
    private $fechaCapacitacion;

    /**
     * @ORM\Column(name="usuario", type="string",length=80, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="tema", type="string", length=150, nullable=true)
     * @Assert\Length(
     *     max = 150,
     *     maxMessage="El campo no puede contener mas de 150 caracteres"
     * )
     */
    private $tema;

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */
    private $codigoPuestoFk;

    /**
     * @ORM\Column(name="vr_capacitacion", type="float")
     */
    private $VrCapacitacion = 0;

    /**
     * @ORM\Column(name="contenido", type="text", nullable=true)
     */
    private $contenido;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="numero_personas_capacitar", type="integer", nullable=true)
     */
    private $numeroPersonasCapacitar;

    /**
     * @ORM\Column(name="numero_personas_asistieron", type="integer", nullable=true)
     */
    private $numeroPersonasAsistieron;

    /**
     * @ORM\Column(name="lugar", type="string", length=150, nullable=true)
     * @Assert\Length(
     *     max = 150,
     *     maxMessage="El campo no puede contener mas de 150 caracteres"
     * )
     */
    private $lugar;

    /**
     * @ORM\Column(name="duracion", type="string", length=20, nullable=false)
     * @Assert\Length(
     *     max = 20,
     *     maxMessage="El campo no puede tener mas de 20 caracteres"
     * )
     */
    private $duracion;

    /**
     * @ORM\Column(name="objetivo", type="text", nullable=true)
     */
    private $objetivo;

    /**
     * @ORM\Column(name="numero_identificacion_facilitador", type="string", length=20, nullable=false)
     * @Assert\Length(
     *     max = 20,
     *     maxMessage="El campo no puede contener mas de 20 caracteres"
     * )
     */
    private $numeroIdentificacionFacilitador;

    /**
     * @ORM\Column(name="facilitador", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max = 100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $facilitador;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_capacitacion_metodologia_fk", type="integer", nullable=true)
     */
    private $codigoCapacitacionMetodologiaFk;

    /**
     * @ORM\Column(name="codigo_zona_fk", type="integer", nullable=true)
     */
    private $codigoZonaFk;

    /**
     * @return mixed
     */
    public function getCodigoCapacitacionPk()
    {
        return $this->codigoCapacitacionPk;
    }

    /**
     * @param mixed $codigoCapacitacionPk
     */
    public function setCodigoCapacitacionPk($codigoCapacitacionPk): void
    {
        $this->codigoCapacitacionPk = $codigoCapacitacionPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCapacitacionTipoFk()
    {
        return $this->codigoCapacitacionTipoFk;
    }

    /**
     * @param mixed $codigoCapacitacionTipoFk
     */
    public function setCodigoCapacitacionTipoFk($codigoCapacitacionTipoFk): void
    {
        $this->codigoCapacitacionTipoFk = $codigoCapacitacionTipoFk;
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
    public function getFechaCapacitacion()
    {
        return $this->fechaCapacitacion;
    }

    /**
     * @param mixed $fechaCapacitacion
     */
    public function setFechaCapacitacion($fechaCapacitacion): void
    {
        $this->fechaCapacitacion = $fechaCapacitacion;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getTema()
    {
        return $this->tema;
    }

    /**
     * @param mixed $tema
     */
    public function setTema($tema): void
    {
        $this->tema = $tema;
    }

    /**
     * @return mixed
     */
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
    }

    /**
     * @param mixed $codigoPuestoFk
     */
    public function setCodigoPuestoFk($codigoPuestoFk): void
    {
        $this->codigoPuestoFk = $codigoPuestoFk;
    }

    /**
     * @return mixed
     */
    public function getVrCapacitacion()
    {
        return $this->VrCapacitacion;
    }

    /**
     * @param mixed $VrCapacitacion
     */
    public function setVrCapacitacion($VrCapacitacion): void
    {
        $this->VrCapacitacion = $VrCapacitacion;
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
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @param mixed $estadoAutorizado
     */
    public function setEstadoAutorizado($estadoAutorizado): void
    {
        $this->estadoAutorizado = $estadoAutorizado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param mixed $estadoAnulado
     */
    public function setEstadoAnulado($estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
    }

    /**
     * @return mixed
     */
    public function getNumeroPersonasCapacitar()
    {
        return $this->numeroPersonasCapacitar;
    }

    /**
     * @param mixed $numeroPersonasCapacitar
     */
    public function setNumeroPersonasCapacitar($numeroPersonasCapacitar): void
    {
        $this->numeroPersonasCapacitar = $numeroPersonasCapacitar;
    }

    /**
     * @return mixed
     */
    public function getNumeroPersonasAsistieron()
    {
        return $this->numeroPersonasAsistieron;
    }

    /**
     * @param mixed $numeroPersonasAsistieron
     */
    public function setNumeroPersonasAsistieron($numeroPersonasAsistieron): void
    {
        $this->numeroPersonasAsistieron = $numeroPersonasAsistieron;
    }

    /**
     * @return mixed
     */
    public function getLugar()
    {
        return $this->lugar;
    }

    /**
     * @param mixed $lugar
     */
    public function setLugar($lugar): void
    {
        $this->lugar = $lugar;
    }

    /**
     * @return mixed
     */
    public function getDuracion()
    {
        return $this->duracion;
    }

    /**
     * @param mixed $duracion
     */
    public function setDuracion($duracion): void
    {
        $this->duracion = $duracion;
    }

    /**
     * @return mixed
     */
    public function getObjetivo()
    {
        return $this->objetivo;
    }

    /**
     * @param mixed $objetivo
     */
    public function setObjetivo($objetivo): void
    {
        $this->objetivo = $objetivo;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacionFacilitador()
    {
        return $this->numeroIdentificacionFacilitador;
    }

    /**
     * @param mixed $numeroIdentificacionFacilitador
     */
    public function setNumeroIdentificacionFacilitador($numeroIdentificacionFacilitador): void
    {
        $this->numeroIdentificacionFacilitador = $numeroIdentificacionFacilitador;
    }

    /**
     * @return mixed
     */
    public function getFacilitador()
    {
        return $this->facilitador;
    }

    /**
     * @param mixed $facilitador
     */
    public function setFacilitador($facilitador): void
    {
        $this->facilitador = $facilitador;
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
    public function getCodigoCapacitacionMetodologiaFk()
    {
        return $this->codigoCapacitacionMetodologiaFk;
    }

    /**
     * @param mixed $codigoCapacitacionMetodologiaFk
     */
    public function setCodigoCapacitacionMetodologiaFk($codigoCapacitacionMetodologiaFk): void
    {
        $this->codigoCapacitacionMetodologiaFk = $codigoCapacitacionMetodologiaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoZonaFk()
    {
        return $this->codigoZonaFk;
    }

    /**
     * @param mixed $codigoZonaFk
     */
    public function setCodigoZonaFk($codigoZonaFk): void
    {
        $this->codigoZonaFk = $codigoZonaFk;
    }

}
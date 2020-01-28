<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAspiranteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAspirante
{
    public $infoLog = [
        "primaryKey" => "codigoAspirantePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aspirante_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoAspirantePk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string",length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=10, nullable=true)
     */
    private $codigoEstadoCivilFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_ciudad_expedicion_fk", type="integer", nullable=true)
     */
    private $codigoCiudadExpedicionFk;

    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */
    private $codigoSexoFk;

    /**
     * @ORM\Column(name="codigo_rh_fk",type="string", length=10, nullable=true)
     */
    private $codigoRhFk;

    /**
     * @ORM\Column(name="codigo_zona_fk", type="string", length=10, nullable=true)
     */
    private $codigoZonaFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false, unique=true)
     * @Assert\NotBlank(message="El campo no puede estar vacio")
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="libreta_militar", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max=20,
     *     maxMessage="El campo no puede contener mas de 20 caracteres")
     * )
     */

    private $libretaMilitar;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=120, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="nombre1", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres")
     */
    private $nombre1;

    /**
     * @ORM\Column(name="nombre2", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres")
     *
     */
    private $nombre2;

    /**
     * @ORM\Column(name="apellido1", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres")
     */
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede contener mas de 30 caracteres")
     */
    private $apellido2;

    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     * @Assert\Length(
     *     max=15,
     *     maxMessage="El campo no puede contener mas de 15 caracteres")
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max=20,
     *     maxMessage="El campo no puede contener mas de 20 caracteres")
     */
    private $celular;

    /**
     * @ORM\Column(name="direccion", type="string", length=60, nullable=true)
     * @Assert\Length(
     *     max=60,
     *     maxMessage="El campo no puede contener mas de 60 caracteres")
     */
    private $direccion;

    /**
     * @ORM\Column(name="barrio", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres")
     */
    private $barrio;

    /**
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max=80,
     *     maxMessage="El campo no puede contener mas de 80 caracteres")
     */
    private $correo;

    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */
    private $fechaNacimiento;

    /**
     * @ORM\Column(name="codigo_ciudad_nacimiento_fk", type="integer", nullable=true)
     */
    private $codigoCiudadNacimientoFk;

    /**
     * @ORM\Column(name="peso", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max=20,
     *     maxMessage="El campo no puede contener mas de 20 caracteres")
     */
    private $peso;

    /**
     * @ORM\Column(name="estatura", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max=20,
     *     maxMessage="El campo no puede contener mas de 20 caracteres")
     */
    private $estatura;

    /**
     * @ORM\Column(name="cargo_aspira", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max=50,
     *     maxMessage="El campo no puede contener mas de 50 caracteres")
     */
    private $cargoAspira;

    /**
     * @ORM\Column(name="recomendado", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max=80,
     *     maxMessage="El campo no puede contener mas de 80 caracteres")
     */
    private $recomendado;

    /**
     * @ORM\Column(name="reintegro", type="boolean", options={"default":false})
     */
    private $reintegro = false;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", options={"default":false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default":false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", options={"default":false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_bloqueado", type="boolean", options={"default":false})
     */
    private $estadoBloqueado = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenIdentificacion", inversedBy="rhuAspirantesIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_identificacion_fk",referencedColumnName="codigo_identificacion_pk")
     */
    protected $identificacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenEstadoCivil", inversedBy="rhuAspirantesEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuAspirantesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuAspirantesCiudadExpedicionRel")
     * @ORM\JoinColumn(name="codigo_ciudad_expedicion_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadExpedicionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuAspirantesCiudadNacimientoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_nacimiento_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadNacimientoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenSexo", inversedBy="rhuAspirantesSexoRel")
     * @ORM\JoinColumn(name="codigo_sexo_fk", referencedColumnName="codigo_sexo_pk")
     */
    protected $sexoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuRh", inversedBy="rhuAspirantesRhRel")
     * @ORM\JoinColumn(name="codigo_rh_fk", referencedColumnName="codigo_rh_pk")
     */
    protected $rhRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuZona", inversedBy="aspirantesZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    protected $zonaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuCargo", inversedBy="rhuAspirantesCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitudAspirante", mappedBy="aspiranteRel")
     */
    protected $solicitudesAspiranteRel;

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
    public function getCodigoAspirantePk()
    {
        return $this->codigoAspirantePk;
    }

    /**
     * @param mixed $codigoAspirantePk
     */
    public function setCodigoAspirantePk($codigoAspirantePk): void
    {
        $this->codigoAspirantePk = $codigoAspirantePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoIdentificacionFk()
    {
        return $this->codigoIdentificacionFk;
    }

    /**
     * @param mixed $codigoIdentificacionFk
     */
    public function setCodigoIdentificacionFk($codigoIdentificacionFk): void
    {
        $this->codigoIdentificacionFk = $codigoIdentificacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEstadoCivilFk()
    {
        return $this->codigoEstadoCivilFk;
    }

    /**
     * @param mixed $codigoEstadoCivilFk
     */
    public function setCodigoEstadoCivilFk($codigoEstadoCivilFk): void
    {
        $this->codigoEstadoCivilFk = $codigoEstadoCivilFk;
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
    public function getCodigoCiudadExpedicionFk()
    {
        return $this->codigoCiudadExpedicionFk;
    }

    /**
     * @param mixed $codigoCiudadExpedicionFk
     */
    public function setCodigoCiudadExpedicionFk($codigoCiudadExpedicionFk): void
    {
        $this->codigoCiudadExpedicionFk = $codigoCiudadExpedicionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }

    /**
     * @param mixed $codigoSexoFk
     */
    public function setCodigoSexoFk($codigoSexoFk): void
    {
        $this->codigoSexoFk = $codigoSexoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoRhFk()
    {
        return $this->codigoRhFk;
    }

    /**
     * @param mixed $codigoRhFk
     */
    public function setCodigoRhFk($codigoRhFk): void
    {
        $this->codigoRhFk = $codigoRhFk;
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
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param mixed $numeroIdentificacion
     */
    public function setNumeroIdentificacion($numeroIdentificacion): void
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
    }

    /**
     * @return mixed
     */
    public function getLibretaMilitar()
    {
        return $this->libretaMilitar;
    }

    /**
     * @param mixed $libretaMilitar
     */
    public function setLibretaMilitar($libretaMilitar): void
    {
        $this->libretaMilitar = $libretaMilitar;
    }

    /**
     * @return mixed
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * @param mixed $nombreCorto
     */
    public function setNombreCorto($nombreCorto): void
    {
        $this->nombreCorto = $nombreCorto;
    }

    /**
     * @return mixed
     */
    public function getNombre1()
    {
        return $this->nombre1;
    }

    /**
     * @param mixed $nombre1
     */
    public function setNombre1($nombre1): void
    {
        $this->nombre1 = $nombre1;
    }

    /**
     * @return mixed
     */
    public function getNombre2()
    {
        return $this->nombre2;
    }

    /**
     * @param mixed $nombre2
     */
    public function setNombre2($nombre2): void
    {
        $this->nombre2 = $nombre2;
    }

    /**
     * @return mixed
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * @param mixed $apellido1
     */
    public function setApellido1($apellido1): void
    {
        $this->apellido1 = $apellido1;
    }

    /**
     * @return mixed
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * @param mixed $apellido2
     */
    public function setApellido2($apellido2): void
    {
        $this->apellido2 = $apellido2;
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
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param mixed $celular
     */
    public function setCelular($celular): void
    {
        $this->celular = $celular;
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
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * @param mixed $barrio
     */
    public function setBarrio($barrio): void
    {
        $this->barrio = $barrio;
    }

    /**
     * @return mixed
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param mixed $correo
     */
    public function setCorreo($correo): void
    {
        $this->correo = $correo;
    }

    /**
     * @return mixed
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * @param mixed $fechaNacimiento
     */
    public function setFechaNacimiento($fechaNacimiento): void
    {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadNacimientoFk()
    {
        return $this->codigoCiudadNacimientoFk;
    }

    /**
     * @param mixed $codigoCiudadNacimientoFk
     */
    public function setCodigoCiudadNacimientoFk($codigoCiudadNacimientoFk): void
    {
        $this->codigoCiudadNacimientoFk = $codigoCiudadNacimientoFk;
    }

    /**
     * @return mixed
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * @param mixed $peso
     */
    public function setPeso($peso): void
    {
        $this->peso = $peso;
    }

    /**
     * @return mixed
     */
    public function getEstatura()
    {
        return $this->estatura;
    }

    /**
     * @param mixed $estatura
     */
    public function setEstatura($estatura): void
    {
        $this->estatura = $estatura;
    }

    /**
     * @return mixed
     */
    public function getCargoAspira()
    {
        return $this->cargoAspira;
    }

    /**
     * @param mixed $cargoAspira
     */
    public function setCargoAspira($cargoAspira): void
    {
        $this->cargoAspira = $cargoAspira;
    }

    /**
     * @return mixed
     */
    public function getRecomendado()
    {
        return $this->recomendado;
    }

    /**
     * @param mixed $recomendado
     */
    public function setRecomendado($recomendado): void
    {
        $this->recomendado = $recomendado;
    }

    /**
     * @return mixed
     */
    public function getReintegro()
    {
        return $this->reintegro;
    }

    /**
     * @param mixed $reintegro
     */
    public function setReintegro($reintegro): void
    {
        $this->reintegro = $reintegro;
    }

    /**
     * @return mixed
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * @param mixed $codigoCargoFk
     */
    public function setCodigoCargoFk($codigoCargoFk): void
    {
        $this->codigoCargoFk = $codigoCargoFk;
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
    public function getEstadoBloqueado()
    {
        return $this->estadoBloqueado;
    }

    /**
     * @param mixed $estadoBloqueado
     */
    public function setEstadoBloqueado($estadoBloqueado): void
    {
        $this->estadoBloqueado = $estadoBloqueado;
    }

    /**
     * @return mixed
     */
    public function getIdentificacionRel()
    {
        return $this->identificacionRel;
    }

    /**
     * @param mixed $identificacionRel
     */
    public function setIdentificacionRel($identificacionRel): void
    {
        $this->identificacionRel = $identificacionRel;
    }

    /**
     * @return mixed
     */
    public function getEstadoCivilRel()
    {
        return $this->estadoCivilRel;
    }

    /**
     * @param mixed $estadoCivilRel
     */
    public function setEstadoCivilRel($estadoCivilRel): void
    {
        $this->estadoCivilRel = $estadoCivilRel;
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
    public function getCiudadExpedicionRel()
    {
        return $this->ciudadExpedicionRel;
    }

    /**
     * @param mixed $ciudadExpedicionRel
     */
    public function setCiudadExpedicionRel($ciudadExpedicionRel): void
    {
        $this->ciudadExpedicionRel = $ciudadExpedicionRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadNacimientoRel()
    {
        return $this->ciudadNacimientoRel;
    }

    /**
     * @param mixed $ciudadNacimientoRel
     */
    public function setCiudadNacimientoRel($ciudadNacimientoRel): void
    {
        $this->ciudadNacimientoRel = $ciudadNacimientoRel;
    }

    /**
     * @return mixed
     */
    public function getSexoRel()
    {
        return $this->sexoRel;
    }

    /**
     * @param mixed $sexoRel
     */
    public function setSexoRel($sexoRel): void
    {
        $this->sexoRel = $sexoRel;
    }

    /**
     * @return mixed
     */
    public function getRhRel()
    {
        return $this->rhRel;
    }

    /**
     * @param mixed $rhRel
     */
    public function setRhRel($rhRel): void
    {
        $this->rhRel = $rhRel;
    }

    /**
     * @return mixed
     */
    public function getZonaRel()
    {
        return $this->zonaRel;
    }

    /**
     * @param mixed $zonaRel
     */
    public function setZonaRel($zonaRel): void
    {
        $this->zonaRel = $zonaRel;
    }

    /**
     * @return mixed
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * @param mixed $cargoRel
     */
    public function setCargoRel($cargoRel): void
    {
        $this->cargoRel = $cargoRel;
    }

    /**
     * @return mixed
     */
    public function getSolicitudesAspiranteRel()
    {
        return $this->solicitudesAspiranteRel;
    }

    /**
     * @param mixed $solicitudesAspiranteRel
     */
    public function setSolicitudesAspiranteRel($solicitudesAspiranteRel): void
    {
        $this->solicitudesAspiranteRel = $solicitudesAspiranteRel;
    }


}

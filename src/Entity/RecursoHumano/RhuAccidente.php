<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAccidenteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAccidente
{
    public $infoLog = [
        "primaryKey" => "codigoAccidente",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_accidente_trabajo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccidentePk;

    /**
     * @ORM\Column(name="codigo_furat", type="string", length=100, nullable=true)
     */
    private $codigoFurat;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="integer", nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="codigo_entidad_riesgo_fk", type="integer", nullable=true)
     */
    private $codigoEntidadRiesgoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_tipo_accidente_fk", type="integer", nullable=true)
     */
    private $codigoTipoAccidenteFk;

    /**
     * @ORM\Column(name="codigo_incapacidad_diagnostico_fk", type="integer", nullable=true)
     */
    private $codigoIncapacidadDiagnosticoFk;

    /**
     * @ORM\Column(name="codigo_accidente_agente_fk", type="integer", nullable=true)
     */
    private $codigoAccidenteAgenteFk;

    /**
     * @ORM\Column(name="codigo_accidente_cuerpo_afectado_fk", type="integer", nullable=true)
     */
    private $codigoAccidenteCuerpoAfectadoFk;

    /**
     * @ORM\Column(name="codigo_accidente_mecanismo_accidente_fk", type="integer", nullable=true)
     */
    private $codigoAccidenteMecanismoAccidenteFk;

    /**
     * @ORM\Column(name="codigo_accidente_naturaleza_lesion_fk", type="integer", nullable=true)
     */
    private $codigoAccidenteNaturalezaLesionFk;

    /**
     * @ORM\Column(name="fecha_accidente", type="date", nullable=true)
     */
    private $fechaAccidente;

    /**
     * @ORM\Column(name="fecha_envia_investigacion", type="date", nullable=true)
     */
    private $fechaEnviaInvestigacion;

    /**
     * @ORM\Column(name="coordinador_encargado", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $coordinadorEncargado;

    /**
     * @ORM\Column(name="cargo_coordinador_encargado", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $cargoCoordinadorEncargado;

    /**
     * @ORM\Column(name="codigo_ciudad_accidente_fk", type="integer", nullable=true)
     */
    private $codigoCiudadAccidenteFk;

    /**
     * @ORM\Column(name="fecha_incapacidad_desde", type="date", nullable=true)
     */
    private $fechaIncapacidadDesde;

    /**
     * @ORM\Column(name="fecha_incapacidad_hasta", type="date", nullable=true)
     */
    private $fechaIncapacidadHasta;

    /**
     * @ORM\Column(name="dias", type="integer", nullable=true)
     */
    private $dias;

    /**
     * @ORM\Column(name="cie10", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max=20,
     *     maxMessage="El campo no puede contener mas de 20 caracteres"
     * )
     */
    private $cie10;

    /**
     * @ORM\Column(name="diagnostico", type="string", length=1000, nullable=true)
     * @Assert\Length(
     *     max=1000,
     *     maxMessage="El campo no puede contener mas de 1000 caracteres"
     * )
     */
    private $diagnostico;

    /**
     * @ORM\Column(name="naturaleza_lesion", type="string", length=1000, nullable=true)
     * @Assert\Length(
     *     max=1000,
     *     maxMessage="El campo no puede contener mas de 1000 caracteres"
     * )
     * @Assert\NotBlank(
     *     message="El campo no puede estar vacio"
     * )
     */
    private $naturalezaLesion;

    /**
     * @ORM\Column(name="cuerpo_afectado", type="string", length=200, nullable=true)
     * @Assert\Length(
     *     max=200,
     *     maxMessage="El campo no puede contener mas de 200 caracteres"
     * )
     * @Assert\NotBlank(
     *     message="El campo no puede estar vacio"
     * )
     */
    private $cuerpoAfectado;

    /**
     * @ORM\Column(name="agente", type="string", length=200, nullable=true)
     * @Assert\Length(
     *     max=200,
     *     maxMessage="El campo no puede contener mas de 200 caracteres"
     * )
     * @Assert\NotBlank(
     *     message="El campo no puede estar vacio"
     * )
     */
    private $agente;

    /**
     * @ORM\Column(name="mecanismo_accidente", type="string", length=200, nullable=true)
     * @Assert\Length(
     *     max=200,
     *     maxMessage="El campo no puede contener mas de 200 caracteres"
     * )
     * @Assert\NotBlank(
     *     message="El campo no puede estar vacio"
     * )
     */
    private $mecanismoAccidente;

    /**
     * @ORM\Column(name="oficio_habitual", type="string", length=120, nullable=true)
     * @Assert\Length(
     *     max=120,
     *     maxMessage="El campo no puede contener mas de 120 caracteres"
     * )
     */
    private $oficioHabitual;

    /**
     * @ORM\Column(name="lugar_accidente", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $lugarAccidente;

    /**
     * @ORM\Column(name="tiempo_servicio_empleado", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max=20,
     *     maxMessage="El campo no puede contener mas de 20 caracteres"
     * )
     */
    private $tiempoServicioEmpleado;

    /**
     * @ORM\Column(name="tarea_desarrollada_momento_accidente", type="string", length=120, nullable=true)
     * @Assert\Length(
     *     max=120,
     *     maxMessage="El campo no puede contener mas de 120 caracteres"
     * )
     */
    private $tareaDesarrolladamomentoAccidente;

    /**
     * @ORM\Column(name="accidente_ocurrido_lugar_habitual", type="boolean")
     */
    private $accidenteOcurrioLugarHabitual = 0;

    /**
     * @ORM\Column(name="descripcion_accidente", type="string", length=3000, nullable=true)
     * @Assert\Length(
     *     max=3000,
     *     maxMessage="El campo no puede contener mas de 3000 caracteres"
     * )
     */
    private $descripcionAccidente;

    /**
     * @ORM\Column(name="acto_inseguro", type="text", nullable=true)
     */
    private $actoInseguro;

    /**
     * @ORM\Column(name="condicion_insegura", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $condicionInsegura;

    /**
     * @ORM\Column(name="factor_personal", type="string", length=200, nullable=true)
     * @Assert\Length(
     *     max=200,
     *     maxMessage="El campo no puede contener mas de 200 caracteres"
     * )
     */
    private $factorPersonal;

    /**
     * @ORM\Column(name="factor_trabajo", type="string", length=200, nullable=true)
     * @Assert\Length(
     *     max=200,
     *     maxMessage="El campo no puede contener mas de 200 caracteres"
     * )
     */
    private $factorTrabajo;

    /**
     * @ORM\Column(name="plan_accion_1", type="string", length=200, nullable=true)
     * @Assert\Length(
     *     max=200,
     *     maxMessage="El campo no puede contener mas de 200 caracteres"
     * )
     */
    private $planAccion1;

    /**
     * @ORM\Column(name="codigo_accidente_trabajo_tipo_control_uno_fk", type="integer", nullable=true)
     */
    private $codigoAccidenteTrabajoTipoControlUnoFk;

    /**
     * @ORM\Column(name="fecha_verificacion_1", type="date", nullable=true)
     */
    private $fechaVerificacion1;

    /**
     * @ORM\Column(name="area_responsable_1", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max=50,
     *     maxMessage="El campo no puede contener mas de 50 caracteres"
     * )
     */
    private $areaResponsable1;

    /**
     * @ORM\Column(name="plan_accion_2", type="string", length=200, nullable=true)
     * @Assert\Length(
     *     max=200,
     *     maxMessage="El campo no puede contener mas de 200 caracteres"
     * )
     */
    private $planAccion2;

    /**
     * @ORM\Column(name="codigo_accidente_trabajo_tipo_control_dos_fk", type="integer", nullable=true)
     */
    private $codigoAccidenteTrabajoTipoControlDosFk;

    /**
     * @ORM\Column(name="fecha_verificacion_2", type="date", nullable=true)
     */
    private $fechaVerificacion2;

    /**
     * @ORM\Column(name="area_responsable_2", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max=50,
     *     maxMessage="El campo no puede contener mas de 50 caracteres"
     * )
     */
    private $areaResponsable2;

    /**
     * @ORM\Column(name="plan_accion_3", type="string", length=200, nullable=true)
     * @Assert\Length(
     *     max=200,
     *     maxMessage="El campo no puede contener mas de 200 caracteres"
     * )
     */
    private $planAccion3;

    /**
     * @ORM\Column(name="codigo_accidente_trabajo_tipo_control_tres_fk", type="integer", nullable=true)
     */
    private $codigoAccidenteTrabajoTipoControlTresFk;

    /**
     * @ORM\Column(name="fecha_verificacion_3", type="date", nullable=true)
     */
    private $fechaVerificacion3;

    /**
     * @ORM\Column(name="area_responsable_3", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max=50,
     *     maxMessage="El campo no puede contener mas de 50 caracteres"
     * )
     */
    private $areaResponsable3;

    /**
     * @ORM\Column(name="participante_investigacion_1", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $participanteInvestigacion1;

    /**
     * @ORM\Column(name="cargo_participante_investigacion_1", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $cargoParticipanteInvestigacion1;

    /**
     * @ORM\Column(name="participante_investigacion_2", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $participanteInvestigacion2;

    /**
     * @ORM\Column(name="cargo_participante_investigacion_2", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $cargoParticipanteInvestigacion2;

    /**
     * @ORM\Column(name="participante_investigacion_3", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $participanteInvestigacion3;

    /**
     * @ORM\Column(name="cargo_participante_investigacion_3", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $cargoParticipanteInvestigacion3;

    /**
     * @ORM\Column(name="representante_legal", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $representanteLegal;

    /**
     * @ORM\Column(name="cargo_representante_legal", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $cargoRepresentanteLegal;

    /**
     * @ORM\Column(name="documento_representante_legal", type="string", length=15, nullable=true)
     * @Assert\Length(
     *     max=15,
     *     maxMessage="El campo no puede contener mas de 15 caracteres"
     * )
     */
    private $documentoRepresentanteLegal;

    /**
     * @ORM\Column(name="licencia", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max=20,
     *     maxMessage="El campo no puede contener mas de 20 caracteres"
     * )
     */
    private $licencia;

    /**
     * @ORM\Column(name="fecha_verificacion", type="date", nullable=true)
     */
    private $fechaVerificacion;

    /**
     * @ORM\Column(name="responsable_verificacion", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede contener mas de 100 caracteres"
     * )
     */
    private $responsableVerificacion;

    /**
     * @ORM\Column(name="estado_accidente", type="boolean")
     */
    private $estadoAccidente = 0;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max=50,
     *     maxMessage="El campo no puede contener mas de 50 caracteres"
     * )
     */
    private $codigoUsuario;

    /**
     * @return mixed
     */
    public function getCodigoAccidentePk()
    {
        return $this->codigoAccidentePk;
    }

    /**
     * @param mixed $codigoAccidentePk
     */
    public function setCodigoAccidentePk($codigoAccidentePk): void
    {
        $this->codigoAccidentePk = $codigoAccidentePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFurat()
    {
        return $this->codigoFurat;
    }

    /**
     * @param mixed $codigoFurat
     */
    public function setCodigoFurat($codigoFurat): void
    {
        $this->codigoFurat = $codigoFurat;
    }

    /**
     * @return mixed
     */
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk($codigoGrupoFk): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadRiesgoFk()
    {
        return $this->codigoEntidadRiesgoFk;
    }

    /**
     * @param mixed $codigoEntidadRiesgoFk
     */
    public function setCodigoEntidadRiesgoFk($codigoEntidadRiesgoFk): void
    {
        $this->codigoEntidadRiesgoFk = $codigoEntidadRiesgoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * @param mixed $codigoEmpleadoFk
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk): void
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoTipoAccidenteFk()
    {
        return $this->codigoTipoAccidenteFk;
    }

    /**
     * @param mixed $codigoTipoAccidenteFk
     */
    public function setCodigoTipoAccidenteFk($codigoTipoAccidenteFk): void
    {
        $this->codigoTipoAccidenteFk = $codigoTipoAccidenteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoIncapacidadDiagnosticoFk()
    {
        return $this->codigoIncapacidadDiagnosticoFk;
    }

    /**
     * @param mixed $codigoIncapacidadDiagnosticoFk
     */
    public function setCodigoIncapacidadDiagnosticoFk($codigoIncapacidadDiagnosticoFk): void
    {
        $this->codigoIncapacidadDiagnosticoFk = $codigoIncapacidadDiagnosticoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAccidenteAgenteFk()
    {
        return $this->codigoAccidenteAgenteFk;
    }

    /**
     * @param mixed $codigoAccidenteAgenteFk
     */
    public function setCodigoAccidenteAgenteFk($codigoAccidenteAgenteFk): void
    {
        $this->codigoAccidenteAgenteFk = $codigoAccidenteAgenteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAccidenteCuerpoAfectadoFk()
    {
        return $this->codigoAccidenteCuerpoAfectadoFk;
    }

    /**
     * @param mixed $codigoAccidenteCuerpoAfectadoFk
     */
    public function setCodigoAccidenteCuerpoAfectadoFk($codigoAccidenteCuerpoAfectadoFk): void
    {
        $this->codigoAccidenteCuerpoAfectadoFk = $codigoAccidenteCuerpoAfectadoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAccidenteMecanismoAccidenteFk()
    {
        return $this->codigoAccidenteMecanismoAccidenteFk;
    }

    /**
     * @param mixed $codigoAccidenteMecanismoAccidenteFk
     */
    public function setCodigoAccidenteMecanismoAccidenteFk($codigoAccidenteMecanismoAccidenteFk): void
    {
        $this->codigoAccidenteMecanismoAccidenteFk = $codigoAccidenteMecanismoAccidenteFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAccidenteNaturalezaLesionFk()
    {
        return $this->codigoAccidenteNaturalezaLesionFk;
    }

    /**
     * @param mixed $codigoAccidenteNaturalezaLesionFk
     */
    public function setCodigoAccidenteNaturalezaLesionFk($codigoAccidenteNaturalezaLesionFk): void
    {
        $this->codigoAccidenteNaturalezaLesionFk = $codigoAccidenteNaturalezaLesionFk;
    }

    /**
     * @return mixed
     */
    public function getFechaAccidente()
    {
        return $this->fechaAccidente;
    }

    /**
     * @param mixed $fechaAccidente
     */
    public function setFechaAccidente($fechaAccidente): void
    {
        $this->fechaAccidente = $fechaAccidente;
    }

    /**
     * @return mixed
     */
    public function getFechaEnviaInvestigacion()
    {
        return $this->fechaEnviaInvestigacion;
    }

    /**
     * @param mixed $fechaEnviaInvestigacion
     */
    public function setFechaEnviaInvestigacion($fechaEnviaInvestigacion): void
    {
        $this->fechaEnviaInvestigacion = $fechaEnviaInvestigacion;
    }

    /**
     * @return mixed
     */
    public function getCoordinadorEncargado()
    {
        return $this->coordinadorEncargado;
    }

    /**
     * @param mixed $coordinadorEncargado
     */
    public function setCoordinadorEncargado($coordinadorEncargado): void
    {
        $this->coordinadorEncargado = $coordinadorEncargado;
    }

    /**
     * @return mixed
     */
    public function getCargoCoordinadorEncargado()
    {
        return $this->cargoCoordinadorEncargado;
    }

    /**
     * @param mixed $cargoCoordinadorEncargado
     */
    public function setCargoCoordinadorEncargado($cargoCoordinadorEncargado): void
    {
        $this->cargoCoordinadorEncargado = $cargoCoordinadorEncargado;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadAccidenteFk()
    {
        return $this->codigoCiudadAccidenteFk;
    }

    /**
     * @param mixed $codigoCiudadAccidenteFk
     */
    public function setCodigoCiudadAccidenteFk($codigoCiudadAccidenteFk): void
    {
        $this->codigoCiudadAccidenteFk = $codigoCiudadAccidenteFk;
    }

    /**
     * @return mixed
     */
    public function getFechaIncapacidadDesde()
    {
        return $this->fechaIncapacidadDesde;
    }

    /**
     * @param mixed $fechaIncapacidadDesde
     */
    public function setFechaIncapacidadDesde($fechaIncapacidadDesde): void
    {
        $this->fechaIncapacidadDesde = $fechaIncapacidadDesde;
    }

    /**
     * @return mixed
     */
    public function getFechaIncapacidadHasta()
    {
        return $this->fechaIncapacidadHasta;
    }

    /**
     * @param mixed $fechaIncapacidadHasta
     */
    public function setFechaIncapacidadHasta($fechaIncapacidadHasta): void
    {
        $this->fechaIncapacidadHasta = $fechaIncapacidadHasta;
    }

    /**
     * @return mixed
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
    }

    /**
     * @return mixed
     */
    public function getCie10()
    {
        return $this->cie10;
    }

    /**
     * @param mixed $cie10
     */
    public function setCie10($cie10): void
    {
        $this->cie10 = $cie10;
    }

    /**
     * @return mixed
     */
    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    /**
     * @param mixed $diagnostico
     */
    public function setDiagnostico($diagnostico): void
    {
        $this->diagnostico = $diagnostico;
    }

    /**
     * @return mixed
     */
    public function getNaturalezaLesion()
    {
        return $this->naturalezaLesion;
    }

    /**
     * @param mixed $naturalezaLesion
     */
    public function setNaturalezaLesion($naturalezaLesion): void
    {
        $this->naturalezaLesion = $naturalezaLesion;
    }

    /**
     * @return mixed
     */
    public function getCuerpoAfectado()
    {
        return $this->cuerpoAfectado;
    }

    /**
     * @param mixed $cuerpoAfectado
     */
    public function setCuerpoAfectado($cuerpoAfectado): void
    {
        $this->cuerpoAfectado = $cuerpoAfectado;
    }

    /**
     * @return mixed
     */
    public function getAgente()
    {
        return $this->agente;
    }

    /**
     * @param mixed $agente
     */
    public function setAgente($agente): void
    {
        $this->agente = $agente;
    }

    /**
     * @return mixed
     */
    public function getMecanismoAccidente()
    {
        return $this->mecanismoAccidente;
    }

    /**
     * @param mixed $mecanismoAccidente
     */
    public function setMecanismoAccidente($mecanismoAccidente): void
    {
        $this->mecanismoAccidente = $mecanismoAccidente;
    }

    /**
     * @return mixed
     */
    public function getOficioHabitual()
    {
        return $this->oficioHabitual;
    }

    /**
     * @param mixed $oficioHabitual
     */
    public function setOficioHabitual($oficioHabitual): void
    {
        $this->oficioHabitual = $oficioHabitual;
    }

    /**
     * @return mixed
     */
    public function getLugarAccidente()
    {
        return $this->lugarAccidente;
    }

    /**
     * @param mixed $lugarAccidente
     */
    public function setLugarAccidente($lugarAccidente): void
    {
        $this->lugarAccidente = $lugarAccidente;
    }

    /**
     * @return mixed
     */
    public function getTiempoServicioEmpleado()
    {
        return $this->tiempoServicioEmpleado;
    }

    /**
     * @param mixed $tiempoServicioEmpleado
     */
    public function setTiempoServicioEmpleado($tiempoServicioEmpleado): void
    {
        $this->tiempoServicioEmpleado = $tiempoServicioEmpleado;
    }

    /**
     * @return mixed
     */
    public function getTareaDesarrolladamomentoAccidente()
    {
        return $this->tareaDesarrolladamomentoAccidente;
    }

    /**
     * @param mixed $tareaDesarrolladamomentoAccidente
     */
    public function setTareaDesarrolladamomentoAccidente($tareaDesarrolladamomentoAccidente): void
    {
        $this->tareaDesarrolladamomentoAccidente = $tareaDesarrolladamomentoAccidente;
    }

    /**
     * @return mixed
     */
    public function getAccidenteOcurrioLugarHabitual()
    {
        return $this->accidenteOcurrioLugarHabitual;
    }

    /**
     * @param mixed $accidenteOcurrioLugarHabitual
     */
    public function setAccidenteOcurrioLugarHabitual($accidenteOcurrioLugarHabitual): void
    {
        $this->accidenteOcurrioLugarHabitual = $accidenteOcurrioLugarHabitual;
    }

    /**
     * @return mixed
     */
    public function getDescripcionAccidente()
    {
        return $this->descripcionAccidente;
    }

    /**
     * @param mixed $descripcionAccidente
     */
    public function setDescripcionAccidente($descripcionAccidente): void
    {
        $this->descripcionAccidente = $descripcionAccidente;
    }

    /**
     * @return mixed
     */
    public function getActoInseguro()
    {
        return $this->actoInseguro;
    }

    /**
     * @param mixed $actoInseguro
     */
    public function setActoInseguro($actoInseguro): void
    {
        $this->actoInseguro = $actoInseguro;
    }

    /**
     * @return mixed
     */
    public function getCondicionInsegura()
    {
        return $this->condicionInsegura;
    }

    /**
     * @param mixed $condicionInsegura
     */
    public function setCondicionInsegura($condicionInsegura): void
    {
        $this->condicionInsegura = $condicionInsegura;
    }

    /**
     * @return mixed
     */
    public function getFactorPersonal()
    {
        return $this->factorPersonal;
    }

    /**
     * @param mixed $factorPersonal
     */
    public function setFactorPersonal($factorPersonal): void
    {
        $this->factorPersonal = $factorPersonal;
    }

    /**
     * @return mixed
     */
    public function getFactorTrabajo()
    {
        return $this->factorTrabajo;
    }

    /**
     * @param mixed $factorTrabajo
     */
    public function setFactorTrabajo($factorTrabajo): void
    {
        $this->factorTrabajo = $factorTrabajo;
    }

    /**
     * @return mixed
     */
    public function getPlanAccion1()
    {
        return $this->planAccion1;
    }

    /**
     * @param mixed $planAccion1
     */
    public function setPlanAccion1($planAccion1): void
    {
        $this->planAccion1 = $planAccion1;
    }

    /**
     * @return mixed
     */
    public function getCodigoAccidenteTrabajoTipoControlUnoFk()
    {
        return $this->codigoAccidenteTrabajoTipoControlUnoFk;
    }

    /**
     * @param mixed $codigoAccidenteTrabajoTipoControlUnoFk
     */
    public function setCodigoAccidenteTrabajoTipoControlUnoFk($codigoAccidenteTrabajoTipoControlUnoFk): void
    {
        $this->codigoAccidenteTrabajoTipoControlUnoFk = $codigoAccidenteTrabajoTipoControlUnoFk;
    }

    /**
     * @return mixed
     */
    public function getFechaVerificacion1()
    {
        return $this->fechaVerificacion1;
    }

    /**
     * @param mixed $fechaVerificacion1
     */
    public function setFechaVerificacion1($fechaVerificacion1): void
    {
        $this->fechaVerificacion1 = $fechaVerificacion1;
    }

    /**
     * @return mixed
     */
    public function getAreaResponsable1()
    {
        return $this->areaResponsable1;
    }

    /**
     * @param mixed $areaResponsable1
     */
    public function setAreaResponsable1($areaResponsable1): void
    {
        $this->areaResponsable1 = $areaResponsable1;
    }

    /**
     * @return mixed
     */
    public function getPlanAccion2()
    {
        return $this->planAccion2;
    }

    /**
     * @param mixed $planAccion2
     */
    public function setPlanAccion2($planAccion2): void
    {
        $this->planAccion2 = $planAccion2;
    }

    /**
     * @return mixed
     */
    public function getCodigoAccidenteTrabajoTipoControlDosFk()
    {
        return $this->codigoAccidenteTrabajoTipoControlDosFk;
    }

    /**
     * @param mixed $codigoAccidenteTrabajoTipoControlDosFk
     */
    public function setCodigoAccidenteTrabajoTipoControlDosFk($codigoAccidenteTrabajoTipoControlDosFk): void
    {
        $this->codigoAccidenteTrabajoTipoControlDosFk = $codigoAccidenteTrabajoTipoControlDosFk;
    }

    /**
     * @return mixed
     */
    public function getFechaVerificacion2()
    {
        return $this->fechaVerificacion2;
    }

    /**
     * @param mixed $fechaVerificacion2
     */
    public function setFechaVerificacion2($fechaVerificacion2): void
    {
        $this->fechaVerificacion2 = $fechaVerificacion2;
    }

    /**
     * @return mixed
     */
    public function getAreaResponsable2()
    {
        return $this->areaResponsable2;
    }

    /**
     * @param mixed $areaResponsable2
     */
    public function setAreaResponsable2($areaResponsable2): void
    {
        $this->areaResponsable2 = $areaResponsable2;
    }

    /**
     * @return mixed
     */
    public function getPlanAccion3()
    {
        return $this->planAccion3;
    }

    /**
     * @param mixed $planAccion3
     */
    public function setPlanAccion3($planAccion3): void
    {
        $this->planAccion3 = $planAccion3;
    }

    /**
     * @return mixed
     */
    public function getCodigoAccidenteTrabajoTipoControlTresFk()
    {
        return $this->codigoAccidenteTrabajoTipoControlTresFk;
    }

    /**
     * @param mixed $codigoAccidenteTrabajoTipoControlTresFk
     */
    public function setCodigoAccidenteTrabajoTipoControlTresFk($codigoAccidenteTrabajoTipoControlTresFk): void
    {
        $this->codigoAccidenteTrabajoTipoControlTresFk = $codigoAccidenteTrabajoTipoControlTresFk;
    }

    /**
     * @return mixed
     */
    public function getFechaVerificacion3()
    {
        return $this->fechaVerificacion3;
    }

    /**
     * @param mixed $fechaVerificacion3
     */
    public function setFechaVerificacion3($fechaVerificacion3): void
    {
        $this->fechaVerificacion3 = $fechaVerificacion3;
    }

    /**
     * @return mixed
     */
    public function getAreaResponsable3()
    {
        return $this->areaResponsable3;
    }

    /**
     * @param mixed $areaResponsable3
     */
    public function setAreaResponsable3($areaResponsable3): void
    {
        $this->areaResponsable3 = $areaResponsable3;
    }

    /**
     * @return mixed
     */
    public function getParticipanteInvestigacion1()
    {
        return $this->participanteInvestigacion1;
    }

    /**
     * @param mixed $participanteInvestigacion1
     */
    public function setParticipanteInvestigacion1($participanteInvestigacion1): void
    {
        $this->participanteInvestigacion1 = $participanteInvestigacion1;
    }

    /**
     * @return mixed
     */
    public function getCargoParticipanteInvestigacion1()
    {
        return $this->cargoParticipanteInvestigacion1;
    }

    /**
     * @param mixed $cargoParticipanteInvestigacion1
     */
    public function setCargoParticipanteInvestigacion1($cargoParticipanteInvestigacion1): void
    {
        $this->cargoParticipanteInvestigacion1 = $cargoParticipanteInvestigacion1;
    }

    /**
     * @return mixed
     */
    public function getParticipanteInvestigacion2()
    {
        return $this->participanteInvestigacion2;
    }

    /**
     * @param mixed $participanteInvestigacion2
     */
    public function setParticipanteInvestigacion2($participanteInvestigacion2): void
    {
        $this->participanteInvestigacion2 = $participanteInvestigacion2;
    }

    /**
     * @return mixed
     */
    public function getCargoParticipanteInvestigacion2()
    {
        return $this->cargoParticipanteInvestigacion2;
    }

    /**
     * @param mixed $cargoParticipanteInvestigacion2
     */
    public function setCargoParticipanteInvestigacion2($cargoParticipanteInvestigacion2): void
    {
        $this->cargoParticipanteInvestigacion2 = $cargoParticipanteInvestigacion2;
    }

    /**
     * @return mixed
     */
    public function getParticipanteInvestigacion3()
    {
        return $this->participanteInvestigacion3;
    }

    /**
     * @param mixed $participanteInvestigacion3
     */
    public function setParticipanteInvestigacion3($participanteInvestigacion3): void
    {
        $this->participanteInvestigacion3 = $participanteInvestigacion3;
    }

    /**
     * @return mixed
     */
    public function getCargoParticipanteInvestigacion3()
    {
        return $this->cargoParticipanteInvestigacion3;
    }

    /**
     * @param mixed $cargoParticipanteInvestigacion3
     */
    public function setCargoParticipanteInvestigacion3($cargoParticipanteInvestigacion3): void
    {
        $this->cargoParticipanteInvestigacion3 = $cargoParticipanteInvestigacion3;
    }

    /**
     * @return mixed
     */
    public function getRepresentanteLegal()
    {
        return $this->representanteLegal;
    }

    /**
     * @param mixed $representanteLegal
     */
    public function setRepresentanteLegal($representanteLegal): void
    {
        $this->representanteLegal = $representanteLegal;
    }

    /**
     * @return mixed
     */
    public function getCargoRepresentanteLegal()
    {
        return $this->cargoRepresentanteLegal;
    }

    /**
     * @param mixed $cargoRepresentanteLegal
     */
    public function setCargoRepresentanteLegal($cargoRepresentanteLegal): void
    {
        $this->cargoRepresentanteLegal = $cargoRepresentanteLegal;
    }

    /**
     * @return mixed
     */
    public function getDocumentoRepresentanteLegal()
    {
        return $this->documentoRepresentanteLegal;
    }

    /**
     * @param mixed $documentoRepresentanteLegal
     */
    public function setDocumentoRepresentanteLegal($documentoRepresentanteLegal): void
    {
        $this->documentoRepresentanteLegal = $documentoRepresentanteLegal;
    }

    /**
     * @return mixed
     */
    public function getLicencia()
    {
        return $this->licencia;
    }

    /**
     * @param mixed $licencia
     */
    public function setLicencia($licencia): void
    {
        $this->licencia = $licencia;
    }

    /**
     * @return mixed
     */
    public function getFechaVerificacion()
    {
        return $this->fechaVerificacion;
    }

    /**
     * @param mixed $fechaVerificacion
     */
    public function setFechaVerificacion($fechaVerificacion): void
    {
        $this->fechaVerificacion = $fechaVerificacion;
    }

    /**
     * @return mixed
     */
    public function getResponsableVerificacion()
    {
        return $this->responsableVerificacion;
    }

    /**
     * @param mixed $responsableVerificacion
     */
    public function setResponsableVerificacion($responsableVerificacion): void
    {
        $this->responsableVerificacion = $responsableVerificacion;
    }

    /**
     * @return mixed
     */
    public function getEstadoAccidente()
    {
        return $this->estadoAccidente;
    }

    /**
     * @param mixed $estadoAccidente
     */
    public function setEstadoAccidente($estadoAccidente): void
    {
        $this->estadoAccidente = $estadoAccidente;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * @param mixed $codigoUsuario
     */
    public function setCodigoUsuario($codigoUsuario): void
    {
        $this->codigoUsuario = $codigoUsuario;
    }


}
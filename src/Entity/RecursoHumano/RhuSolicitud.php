<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSolicitudRepository")
 */
class RhuSolicitud
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_solicitud_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSolicitudPk;

    /**
     * @ORM\Column(name="codigo_grupo_pago_fk", type="integer", nullable=true)
     */
    private $codigoGrupoPagoFk;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */
    private $codigoCodigoCargoFk;

    /**
     * @ORM\Column(name="codigo_solicitud_motivo_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudMotivoFk;

    /**
     * @ORM\Column(name="codigo_experiencia_solicitud_fk", type="integer", nullable=true)
     */
    private $codigoExperienciaSolicitudFk;

    /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    private $codigoEstadoCivilFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_estudio_tipo_fk", type="integer", length=4, nullable=true)
     */
    private $codigoEstudioTipoFk;

    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer", nullable=true)
     */
    private $codigoClasificacionRiesgoFk;

    /**
     * @ORM\Column(name="disponibilidad", type="string", length=20, nullable=true)
     */
    private $disponbilidad;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_contratacion", type="date", nullable=true)
     */
    private $fechaContratacion;

    /**
     * @ORM\Column(name="fecha_vencimiento", type="date", nullable=true)
     */
    private $fechaVencimiento;

    /**
     * @ORM\Column(name="nombre", type="string", nullable=true)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $nombre;

    /**
     * @ORM\Column(name="cantidad_solicitada", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $cantidadSolicitada;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;

    /**
     * @ORM\Column(name="vr_no_salarial", type="float", nullable=true)
     */
    private $VrNoSalarial = 0;

    /**
     * @ORM\Column(name="salario_fijo", type="boolean")
     */
    private $salarioFijo = false;

    /**
     * @ORM\Column(name="salario_variable", type="boolean")
     */
    private $salarioVariable = false;

    /**
     * @ORM\Column(name="fecha_pruebas", type="datetime", nullable=true)
     */
    private $fechaPruebas;

    /**
     * @ORM\Column(name="edad_minima", type="string", length=20, nullable=true)
     */
    private $edadMinima;

    /**
     * @ORM\Column(name="edad_maxima", type="string", length=20, nullable=true)
     */
    private $edadMaxima;

    /**
     * @ORM\Column(name="numero_hijos", type="integer", nullable=true)
     */
    private $numeroHijos;

    /**
     * @ORM\Column(name="codigo_licencia_carro_fk", type="string", length=30, nullable=true)
     */
    private $codigoLicenciaCarroFk;

    /**
     * @ORM\Column(name="codigo_licencia_moto_fk", type="string", length=30, nullable=true)
     */
    private $codigoLicenciaMotoFk;

    /**
     * @ORM\Column(name="experiencia_solicitud", type="string", nullable=true)
     */
    private $experienciaSolicitud;

    /**
     * @ORM\Column(name="vehiculo", type="string", nullable=true)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $vehiculo;

    /**
     * @ORM\Column(name="comentarios", type="string", length=300, nullable=true)
     * @Assert\Length(
     *     max=300,
     *     maxMessage="El comentario no puede contener mas de 300 caracteres"
     * )
     */
    private $comentarios;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = false;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

}

<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEmpleadoRepository")
 */
class RhuEmpleado
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoEmpleadoPk;

    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer", nullable=true)
     */
    private $codigoClasificacionRiesgoFk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string", length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false, unique=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="nombre1", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede tener mas de 30 caracteres"
     * )
     */
    private $nombre1;

    /**
     * @ORM\Column(name="nombre2", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede tener mas de 30 caracteres"
     * )
     */
    private $nombre2;

    /**
     * @ORM\Column(name="apellido1", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede tener mas de 30 caracteres"
     * )
     */
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=30, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede tener mas de 30 caracteres"
     * )
     */
    private $apellido2;


    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     * @Assert\Length(
     *     max=15,
     *     maxMessage="El campo no puede tener mas de 15 caracteres"
     * )
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     * @Assert\Length(max=20, maxMessage="El campo celular no puede contener mas de 20 caracteres.")
     *
     */
    private $celular;

    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     * @Assert\Length(
     *     max=120,
     *     maxMessage="El campo no puede tener mas de 120 caracteres"
     * )
     */
    private $direccion;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_ciudad_expedicion_fk", type="integer", nullable=true)
     */
    private $codigoCiudadExpedicionFk;

    /**
     * @ORM\Column(name="fecha_expedicion_identificacion", type="date", nullable=true)
     */
    private $fechaExpedicionIdentificacion;

    /**
     * @ORM\Column(name="barrio", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="El campo no puede tener mas de 100 caracteres"
     * )
     */
    private $barrio;

    /**
     * @ORM\Column(name="codigo_rh_fk", type="integer", nullable=true)
     */
    private $codigoRhFk;

    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */
    private $codigoSexoFk;

    /**
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max=80,
     *     maxMessage="El campo no puede tener mas de 80 caracteres"
     * )
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
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    private $codigoEstadoCivilFk;

    /**
     * @ORM\Column(name="cuenta", type="string", length=80, nullable=true)
     * @Assert\Length(
     *     max=80,
     *     maxMessage="El campo no puede tener mas de 80 caracteres"
     * )
     */
    private $cuenta;

    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer", nullable=true)
     */
    private $codigoBancoFk;

    /**
     * @ORM\Column(name="estatura", type="integer", nullable=true)
     */
    private $estatura = 0;

    /**
     * @ORM\Column(name="peso", type="integer", nullable=true)
     */
    private $peso = 0;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="cargo_descripcion", type="string", length=60, nullable=true)
     * @Assert\Length(
     *     max=60,
     *     maxMessage="El campo no puede tener mas de 60 caracteres"
     * )
     */
    private $cargoDescripcion;

    /**
     * @ORM\Column(name="auxilio_transporte", type="boolean")
     */
    private $auxilioTransporte = false;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;

}

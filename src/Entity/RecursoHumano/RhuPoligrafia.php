<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPoligrafiaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuPoligrafia
{
    public $infoLog = [
        "primaryKey" => "codigoPermisoPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_permiso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPoligrafiaPk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string", length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="codigo_poligrafia_tipo_fk", type="integer")
     */
    private $codigoPoligrafiaTipoFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="estado_cobrado", type="boolean", nullable=true)
     */
    private $estadoCobrado = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="comentario", type="string", length=1500, nullable=true)
     */
    private $comentario;

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
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="poligrafiasEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenIdentificacion", inversedBy="rhuPoligrafiasIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_identificacion_fk",referencedColumnName="codigo_identificacion_pk")
     */
    protected $identificacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuPoligrafiaTipo", inversedBy="poligrafiasPoligrafiaTipoRel")
     * @ORM\JoinColumn(name="codigo_poligrafia_tipo_fk", referencedColumnName="codigo_poligrafia_tipo_pk")
     */
    protected $poligrafiaTipoRel;

}
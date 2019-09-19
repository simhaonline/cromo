<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuVacacionCambioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuVisita
{
    public $infoLog = [
        "primaryKey" => "codigoVisitaPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_visita_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVisitaPk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string", length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @ORM\Column(name="codigo_visita_tipo_fk", type="integer")
     */
    private $codigoVisitaTipoFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="codigo_cobro_fk", type="integer", nullable=true)
     */
    private $codigoCobroFk;

    /**
     * @ORM\Column(name="fecha_vence", type="date")
     */
    private $fechaVence;

    /**
     * @ORM\Column(name="validar_vencimiento", type="boolean")
     */
    private $validarVencimiento = false;

    /**
     * @ORM\Column(name="nombre_quien_visita", type="string", length=100, nullable=true)
     */
    private $nombreQuienVisita;

    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;
    /**
     * @ORM\Column(name="nombre_corto", type="text", nullable=true)
     */
    private $nombreCorto;
    /**
     * @ORM\Column(name="numero_identificacion", type="text", nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = false;

    /**
     * @ORM\Column(name="estado_cobrado", type="boolean")
     */
    private $estadoCobrado = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

}
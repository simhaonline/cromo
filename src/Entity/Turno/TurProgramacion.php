<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurProgramacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurProgramacion
{
    public $infoLog = [
        "primaryKey" => "codigoProgramacionPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */
    private $codigoPedidoDetalleFk;

    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */
    private $codigoRecursoFk;

    /**
     * @ORM\Column(name="dia_1", type="string", length=5, nullable=true)
     */
    private $dia1;

    /**
     * @ORM\Column(name="dia_2", type="string", length=5, nullable=true)
     */
    private $dia2;

    /**
     * @ORM\Column(name="dia_3", type="string", length=5, nullable=true)
     */
    private $dia3;

    /**
     * @ORM\Column(name="dia_4", type="string", length=5, nullable=true)
     */
    private $dia4;

    /**
     * @ORM\Column(name="dia_5", type="string", length=5, nullable=true)
     */
    private $dia5;

    /**
     * @ORM\Column(name="dia_6", type="string", length=5, nullable=true)
     */
    private $dia6;

    /**
     * @ORM\Column(name="dia_7", type="string", length=5, nullable=true)
     */
    private $dia7;

    /**
     * @ORM\Column(name="dia_8", type="string", length=5, nullable=true)
     */
    private $dia8;

    /**
     * @ORM\Column(name="dia_9", type="string", length=5, nullable=true)
     */
    private $dia9;

    /**
     * @ORM\Column(name="dia_10", type="string", length=5, nullable=true)
     */
    private $dia10;

    /**
     * @ORM\Column(name="dia_11", type="string", length=5, nullable=true)
     */
    private $dia11;

    /**
     * @ORM\Column(name="dia_12", type="string", length=5, nullable=true)
     */
    private $dia12;

    /**
     * @ORM\Column(name="dia_13", type="string", length=5, nullable=true)
     */
    private $dia13;

    /**
     * @ORM\Column(name="dia_14", type="string", length=5, nullable=true)
     */
    private $dia14;

    /**
     * @ORM\Column(name="dia_15", type="string", length=5, nullable=true)
     */
    private $dia15;

    /**
     * @ORM\Column(name="dia_16", type="string", length=5, nullable=true)
     */
    private $dia16;

    /**
     * @ORM\Column(name="dia_17", type="string", length=5, nullable=true)
     */
    private $dia17;

    /**
     * @ORM\Column(name="dia_18", type="string", length=5, nullable=true)
     */
    private $dia18;

    /**
     * @ORM\Column(name="dia_19", type="string", length=5, nullable=true)
     */
    private $dia19;

    /**
     * @ORM\Column(name="dia_20", type="string", length=5, nullable=true)
     */
    private $dia20;

    /**
     * @ORM\Column(name="dia_21", type="string", length=5, nullable=true)
     */
    private $dia21;

    /**
     * @ORM\Column(name="dia_22", type="string", length=5, nullable=true)
     */
    private $dia22;

    /**
     * @ORM\Column(name="dia_23", type="string", length=5, nullable=true)
     */
    private $dia23;

    /**
     * @ORM\Column(name="dia_24", type="string", length=5, nullable=true)
     */
    private $dia24;

    /**
     * @ORM\Column(name="dia_25", type="string", length=5, nullable=true)
     */
    private $dia25;

    /**
     * @ORM\Column(name="dia_26", type="string", length=5, nullable=true)
     */
    private $dia26;

    /**
     * @ORM\Column(name="dia_27", type="string", length=5, nullable=true)
     */
    private $dia27;

    /**
     * @ORM\Column(name="dia_28", type="string", length=5, nullable=true)
     */
    private $dia28;

    /**
     * @ORM\Column(name="dia_29", type="string", length=5, nullable=true)
     */
    private $dia29;

    /**
     * @ORM\Column(name="dia_30", type="string", length=5, nullable=true)
     */
    private $dia30;

    /**
     * @ORM\Column(name="dia_31", type="string", length=5, nullable=true)
     */
    private $dia31;

    /**
     * @ORM\Column(name="horas", type="float")
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */
    private $horasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */
    private $horasNocturnas = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="programacionesPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="programacionesRecursoRel")
     * @ORM\JoinColumn(name="codigo_recurso_fk", referencedColumnName="codigo_recurso_pk")
     */
    protected $recursoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="programacionesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;


}


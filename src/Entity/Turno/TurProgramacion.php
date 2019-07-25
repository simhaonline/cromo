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
     * @ORM\Column(name="codigo_pedido_fk", nullable=true, type="integer")
     */
    private $codigoPedidoFk;

    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */
    private $codigoPedidoDetalleFk;

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */
    private $codigoPuestoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

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
     * @ORM\Column(name="horas", type="float", options={"default" : 0})
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="horas_diurnas", type="float", options={"default" : 0})
     */
    private $horasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_nocturnas", type="float", options={"default" : 0})
     */
    private $horasNocturnas = 0;

    /**
     * @ORM\Column(name="complementario", type="boolean", nullable=true, options={"default":false})
     */
    private $complementario = false;

    /**
     * @ORM\Column(name="adicional", type="boolean", nullable=true, options={"default":false})
     */
    private $adicional = false;

    /**
     * @ORM\ManyToOne(targetEntity="TurPedido", inversedBy="programacionesPedidoRel")
     * @ORM\JoinColumn(name="codigo_pedido_fk", referencedColumnName="codigo_pedido_pk")
     */
    protected $pedidoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="programacionesPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="programacionesPuestoRel")
     * @ORM\JoinColumn(name="codigo_pedido_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", inversedBy="programacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk",referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurSoporteHora", mappedBy="programacionRel")
     */
    protected $soportesHorasProgramacionRel;

    /**
     * @return mixed
     */
    public function getCodigoProgramacionPk()
    {
        return $this->codigoProgramacionPk;
    }

    /**
     * @param mixed $codigoProgramacionPk
     */
    public function setCodigoProgramacionPk($codigoProgramacionPk): void
    {
        $this->codigoProgramacionPk = $codigoProgramacionPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPedidoFk()
    {
        return $this->codigoPedidoFk;
    }

    /**
     * @param mixed $codigoPedidoFk
     */
    public function setCodigoPedidoFk($codigoPedidoFk): void
    {
        $this->codigoPedidoFk = $codigoPedidoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPedidoDetalleFk()
    {
        return $this->codigoPedidoDetalleFk;
    }

    /**
     * @param mixed $codigoPedidoDetalleFk
     */
    public function setCodigoPedidoDetalleFk($codigoPedidoDetalleFk): void
    {
        $this->codigoPedidoDetalleFk = $codigoPedidoDetalleFk;
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
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * @param mixed $anio
     */
    public function setAnio($anio): void
    {
        $this->anio = $anio;
    }

    /**
     * @return mixed
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param mixed $mes
     */
    public function setMes($mes): void
    {
        $this->mes = $mes;
    }

    /**
     * @return mixed
     */
    public function getDia1()
    {
        return $this->dia1;
    }

    /**
     * @param mixed $dia1
     */
    public function setDia1($dia1): void
    {
        $this->dia1 = $dia1;
    }

    /**
     * @return mixed
     */
    public function getDia2()
    {
        return $this->dia2;
    }

    /**
     * @param mixed $dia2
     */
    public function setDia2($dia2): void
    {
        $this->dia2 = $dia2;
    }

    /**
     * @return mixed
     */
    public function getDia3()
    {
        return $this->dia3;
    }

    /**
     * @param mixed $dia3
     */
    public function setDia3($dia3): void
    {
        $this->dia3 = $dia3;
    }

    /**
     * @return mixed
     */
    public function getDia4()
    {
        return $this->dia4;
    }

    /**
     * @param mixed $dia4
     */
    public function setDia4($dia4): void
    {
        $this->dia4 = $dia4;
    }

    /**
     * @return mixed
     */
    public function getDia5()
    {
        return $this->dia5;
    }

    /**
     * @param mixed $dia5
     */
    public function setDia5($dia5): void
    {
        $this->dia5 = $dia5;
    }

    /**
     * @return mixed
     */
    public function getDia6()
    {
        return $this->dia6;
    }

    /**
     * @param mixed $dia6
     */
    public function setDia6($dia6): void
    {
        $this->dia6 = $dia6;
    }

    /**
     * @return mixed
     */
    public function getDia7()
    {
        return $this->dia7;
    }

    /**
     * @param mixed $dia7
     */
    public function setDia7($dia7): void
    {
        $this->dia7 = $dia7;
    }

    /**
     * @return mixed
     */
    public function getDia8()
    {
        return $this->dia8;
    }

    /**
     * @param mixed $dia8
     */
    public function setDia8($dia8): void
    {
        $this->dia8 = $dia8;
    }

    /**
     * @return mixed
     */
    public function getDia9()
    {
        return $this->dia9;
    }

    /**
     * @param mixed $dia9
     */
    public function setDia9($dia9): void
    {
        $this->dia9 = $dia9;
    }

    /**
     * @return mixed
     */
    public function getDia10()
    {
        return $this->dia10;
    }

    /**
     * @param mixed $dia10
     */
    public function setDia10($dia10): void
    {
        $this->dia10 = $dia10;
    }

    /**
     * @return mixed
     */
    public function getDia11()
    {
        return $this->dia11;
    }

    /**
     * @param mixed $dia11
     */
    public function setDia11($dia11): void
    {
        $this->dia11 = $dia11;
    }

    /**
     * @return mixed
     */
    public function getDia12()
    {
        return $this->dia12;
    }

    /**
     * @param mixed $dia12
     */
    public function setDia12($dia12): void
    {
        $this->dia12 = $dia12;
    }

    /**
     * @return mixed
     */
    public function getDia13()
    {
        return $this->dia13;
    }

    /**
     * @param mixed $dia13
     */
    public function setDia13($dia13): void
    {
        $this->dia13 = $dia13;
    }

    /**
     * @return mixed
     */
    public function getDia14()
    {
        return $this->dia14;
    }

    /**
     * @param mixed $dia14
     */
    public function setDia14($dia14): void
    {
        $this->dia14 = $dia14;
    }

    /**
     * @return mixed
     */
    public function getDia15()
    {
        return $this->dia15;
    }

    /**
     * @param mixed $dia15
     */
    public function setDia15($dia15): void
    {
        $this->dia15 = $dia15;
    }

    /**
     * @return mixed
     */
    public function getDia16()
    {
        return $this->dia16;
    }

    /**
     * @param mixed $dia16
     */
    public function setDia16($dia16): void
    {
        $this->dia16 = $dia16;
    }

    /**
     * @return mixed
     */
    public function getDia17()
    {
        return $this->dia17;
    }

    /**
     * @param mixed $dia17
     */
    public function setDia17($dia17): void
    {
        $this->dia17 = $dia17;
    }

    /**
     * @return mixed
     */
    public function getDia18()
    {
        return $this->dia18;
    }

    /**
     * @param mixed $dia18
     */
    public function setDia18($dia18): void
    {
        $this->dia18 = $dia18;
    }

    /**
     * @return mixed
     */
    public function getDia19()
    {
        return $this->dia19;
    }

    /**
     * @param mixed $dia19
     */
    public function setDia19($dia19): void
    {
        $this->dia19 = $dia19;
    }

    /**
     * @return mixed
     */
    public function getDia20()
    {
        return $this->dia20;
    }

    /**
     * @param mixed $dia20
     */
    public function setDia20($dia20): void
    {
        $this->dia20 = $dia20;
    }

    /**
     * @return mixed
     */
    public function getDia21()
    {
        return $this->dia21;
    }

    /**
     * @param mixed $dia21
     */
    public function setDia21($dia21): void
    {
        $this->dia21 = $dia21;
    }

    /**
     * @return mixed
     */
    public function getDia22()
    {
        return $this->dia22;
    }

    /**
     * @param mixed $dia22
     */
    public function setDia22($dia22): void
    {
        $this->dia22 = $dia22;
    }

    /**
     * @return mixed
     */
    public function getDia23()
    {
        return $this->dia23;
    }

    /**
     * @param mixed $dia23
     */
    public function setDia23($dia23): void
    {
        $this->dia23 = $dia23;
    }

    /**
     * @return mixed
     */
    public function getDia24()
    {
        return $this->dia24;
    }

    /**
     * @param mixed $dia24
     */
    public function setDia24($dia24): void
    {
        $this->dia24 = $dia24;
    }

    /**
     * @return mixed
     */
    public function getDia25()
    {
        return $this->dia25;
    }

    /**
     * @param mixed $dia25
     */
    public function setDia25($dia25): void
    {
        $this->dia25 = $dia25;
    }

    /**
     * @return mixed
     */
    public function getDia26()
    {
        return $this->dia26;
    }

    /**
     * @param mixed $dia26
     */
    public function setDia26($dia26): void
    {
        $this->dia26 = $dia26;
    }

    /**
     * @return mixed
     */
    public function getDia27()
    {
        return $this->dia27;
    }

    /**
     * @param mixed $dia27
     */
    public function setDia27($dia27): void
    {
        $this->dia27 = $dia27;
    }

    /**
     * @return mixed
     */
    public function getDia28()
    {
        return $this->dia28;
    }

    /**
     * @param mixed $dia28
     */
    public function setDia28($dia28): void
    {
        $this->dia28 = $dia28;
    }

    /**
     * @return mixed
     */
    public function getDia29()
    {
        return $this->dia29;
    }

    /**
     * @param mixed $dia29
     */
    public function setDia29($dia29): void
    {
        $this->dia29 = $dia29;
    }

    /**
     * @return mixed
     */
    public function getDia30()
    {
        return $this->dia30;
    }

    /**
     * @param mixed $dia30
     */
    public function setDia30($dia30): void
    {
        $this->dia30 = $dia30;
    }

    /**
     * @return mixed
     */
    public function getDia31()
    {
        return $this->dia31;
    }

    /**
     * @param mixed $dia31
     */
    public function setDia31($dia31): void
    {
        $this->dia31 = $dia31;
    }

    /**
     * @return mixed
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * @param mixed $horas
     */
    public function setHoras($horas): void
    {
        $this->horas = $horas;
    }

    /**
     * @return mixed
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * @param mixed $horasDiurnas
     */
    public function setHorasDiurnas($horasDiurnas): void
    {
        $this->horasDiurnas = $horasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * @param mixed $horasNocturnas
     */
    public function setHorasNocturnas($horasNocturnas): void
    {
        $this->horasNocturnas = $horasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getComplementario()
    {
        return $this->complementario;
    }

    /**
     * @param mixed $complementario
     */
    public function setComplementario($complementario): void
    {
        $this->complementario = $complementario;
    }

    /**
     * @return mixed
     */
    public function getAdicional()
    {
        return $this->adicional;
    }

    /**
     * @param mixed $adicional
     */
    public function setAdicional($adicional): void
    {
        $this->adicional = $adicional;
    }

    /**
     * @return mixed
     */
    public function getPedidoRel()
    {
        return $this->pedidoRel;
    }

    /**
     * @param mixed $pedidoRel
     */
    public function setPedidoRel($pedidoRel): void
    {
        $this->pedidoRel = $pedidoRel;
    }

    /**
     * @return mixed
     */
    public function getPedidoDetalleRel()
    {
        return $this->pedidoDetalleRel;
    }

    /**
     * @param mixed $pedidoDetalleRel
     */
    public function setPedidoDetalleRel($pedidoDetalleRel): void
    {
        $this->pedidoDetalleRel = $pedidoDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * @param mixed $empleadoRel
     */
    public function setEmpleadoRel($empleadoRel): void
    {
        $this->empleadoRel = $empleadoRel;
    }

    /**
     * @return mixed
     */
    public function getSoportesHorasProgramacionRel()
    {
        return $this->soportesHorasProgramacionRel;
    }

    /**
     * @param mixed $soportesHorasProgramacionRel
     */
    public function setSoportesHorasProgramacionRel($soportesHorasProgramacionRel): void
    {
        $this->soportesHorasProgramacionRel = $soportesHorasProgramacionRel;
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
    public function getPuestoRel()
    {
        return $this->puestoRel;
    }

    /**
     * @param mixed $puestoRel
     */
    public function setPuestoRel($puestoRel): void
    {
        $this->puestoRel = $puestoRel;
    }


}


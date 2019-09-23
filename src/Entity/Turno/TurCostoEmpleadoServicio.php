<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurCostoEmpleadoServicioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurCostoEmpleadoServicio
{
    public $infoLog = [
        "primaryKey" => "codigoCostoEmpleadoServicioPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_empleado_servicio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCostoEmpleadoServicioPk;

    /**
     * @ORM\Column(name="codigo_cierre_fk", type="integer")
     */
    private $codigoCierreFk;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */
    private $anio;

    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */
    private $mes;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */
    private $codigoPedidoDetalleFk;

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */
    private $codigoPuestoFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

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
     * @ORM\Column(name="horas_festivas_diurnas", type="float")
     */
    private $horasFestivasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_festivas_nocturnas", type="float")
     */
    private $horasFestivasNocturnas = 0;

    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas", type="float")
     */
    private $horasExtrasOrdinariasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas", type="float")
     */
    private $horasExtrasOrdinariasNocturnas = 0;

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas", type="float")
     */
    private $horasExtrasFestivasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas", type="float")
     */
    private $horasExtrasFestivasNocturnas = 0;

    /**
     * @ORM\Column(name="horas_recargo_nocturno", type="float")
     */
    private $horasRecargoNocturno = 0;

    /**
     * @ORM\Column(name="horas_recargo_festivo_diurno", type="float")
     */
    private $horasRecargoFestivoDiurno = 0;

    /**
     * @ORM\Column(name="horas_recargo_festivo_nocturno", type="float")
     */
    private $horasRecargoFestivoNocturno = 0;

    /**
     * @ORM\Column(name="horas_descanso", type="float")
     */
    private $horasDescanso = 0;

    /**
     * @ORM\Column(name="horas_diurnas_costo", type="float")
     */
    private $horasDiurnasCosto = 0;

    /**
     * @ORM\Column(name="horas_nocturnas_costo", type="float")
     */
    private $horasNocturnasCosto = 0;

    /**
     * @ORM\Column(name="horas_festivas_diurnas_costo", type="float")
     */
    private $horasFestivasDiurnasCosto = 0;

    /**
     * @ORM\Column(name="horas_festivas_nocturnas_costo", type="float")
     */
    private $horasFestivasNocturnasCosto = 0;

    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas_costo", type="float")
     */
    private $horasExtrasOrdinariasDiurnasCosto = 0;

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas_costo", type="float")
     */
    private $horasExtrasOrdinariasNocturnasCosto = 0;

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas_costo", type="float")
     */
    private $horasExtrasFestivasDiurnasCosto = 0;

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas_costo", type="float")
     */
    private $horasExtrasFestivasNocturnasCosto = 0;

    /**
     * @ORM\Column(name="horas_recargo_nocturno_costo", type="float")
     */
    private $horasRecargoNocturnoCosto = 0;

    /**
     * @ORM\Column(name="horas_recargo_festivo_diurno_costo", type="float")
     */
    private $horasRecargoFestivoDiurnoCosto = 0;

    /**
     * @ORM\Column(name="horas_recargo_festivo_nocturno_costo", type="float")
     */
    private $horasRecargoFestivoNocturnoCosto = 0;

    /**
     * @ORM\Column(name="horas_descanso_costo", type="float")
     */
    private $horasDescansoCosto = 0;

    /**
     * @ORM\Column(name="peso", type="float")
     */
    private $peso = 0;

    /**
     * @ORM\Column(name="participacion", type="float")
     */
    private $participacion = 0;

    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;

    /**
     * @ORM\Column(name="vr_nomina", type="float")
     */
    private $vrNomina = 0;

    /**
     * @ORM\Column(name="vr_aporte", type="float")
     */
    private $vrAporte = 0;

    /**
     * @ORM\Column(name="vr_provision", type="float")
     */
    private $vrProvision = 0;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurCierre", inversedBy="costosEmpleadosServiciosCierreRel")
     * @ORM\JoinColumn(name="codigo_cierre_fk", referencedColumnName="codigo_cierre_pk")
     */
    protected $cierreRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", inversedBy="costosEmpleadosServiciosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="costosEmpleadosServiciosPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="costosEmpleadosServiciosPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="costosEmpleadosServiciosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Financiero\FinCentroCosto", inversedBy="costosEmpleadosServiciosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;

    /**
     * @return mixed
     */
    public function getCodigoCostoEmpleadoServicioPk()
    {
        return $this->codigoCostoEmpleadoServicioPk;
    }

    /**
     * @param mixed $codigoCostoEmpleadoServicioPk
     */
    public function setCodigoCostoEmpleadoServicioPk($codigoCostoEmpleadoServicioPk): void
    {
        $this->codigoCostoEmpleadoServicioPk = $codigoCostoEmpleadoServicioPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCierreFk()
    {
        return $this->codigoCierreFk;
    }

    /**
     * @param mixed $codigoCierreFk
     */
    public function setCodigoCierreFk($codigoCierreFk): void
    {
        $this->codigoCierreFk = $codigoCierreFk;
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
    public function getHorasFestivasDiurnas()
    {
        return $this->horasFestivasDiurnas;
    }

    /**
     * @param mixed $horasFestivasDiurnas
     */
    public function setHorasFestivasDiurnas($horasFestivasDiurnas): void
    {
        $this->horasFestivasDiurnas = $horasFestivasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasFestivasNocturnas()
    {
        return $this->horasFestivasNocturnas;
    }

    /**
     * @param mixed $horasFestivasNocturnas
     */
    public function setHorasFestivasNocturnas($horasFestivasNocturnas): void
    {
        $this->horasFestivasNocturnas = $horasFestivasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasOrdinariasDiurnas()
    {
        return $this->horasExtrasOrdinariasDiurnas;
    }

    /**
     * @param mixed $horasExtrasOrdinariasDiurnas
     */
    public function setHorasExtrasOrdinariasDiurnas($horasExtrasOrdinariasDiurnas): void
    {
        $this->horasExtrasOrdinariasDiurnas = $horasExtrasOrdinariasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasOrdinariasNocturnas()
    {
        return $this->horasExtrasOrdinariasNocturnas;
    }

    /**
     * @param mixed $horasExtrasOrdinariasNocturnas
     */
    public function setHorasExtrasOrdinariasNocturnas($horasExtrasOrdinariasNocturnas): void
    {
        $this->horasExtrasOrdinariasNocturnas = $horasExtrasOrdinariasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasFestivasDiurnas()
    {
        return $this->horasExtrasFestivasDiurnas;
    }

    /**
     * @param mixed $horasExtrasFestivasDiurnas
     */
    public function setHorasExtrasFestivasDiurnas($horasExtrasFestivasDiurnas): void
    {
        $this->horasExtrasFestivasDiurnas = $horasExtrasFestivasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasFestivasNocturnas()
    {
        return $this->horasExtrasFestivasNocturnas;
    }

    /**
     * @param mixed $horasExtrasFestivasNocturnas
     */
    public function setHorasExtrasFestivasNocturnas($horasExtrasFestivasNocturnas): void
    {
        $this->horasExtrasFestivasNocturnas = $horasExtrasFestivasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoNocturno()
    {
        return $this->horasRecargoNocturno;
    }

    /**
     * @param mixed $horasRecargoNocturno
     */
    public function setHorasRecargoNocturno($horasRecargoNocturno): void
    {
        $this->horasRecargoNocturno = $horasRecargoNocturno;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoFestivoDiurno()
    {
        return $this->horasRecargoFestivoDiurno;
    }

    /**
     * @param mixed $horasRecargoFestivoDiurno
     */
    public function setHorasRecargoFestivoDiurno($horasRecargoFestivoDiurno): void
    {
        $this->horasRecargoFestivoDiurno = $horasRecargoFestivoDiurno;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoFestivoNocturno()
    {
        return $this->horasRecargoFestivoNocturno;
    }

    /**
     * @param mixed $horasRecargoFestivoNocturno
     */
    public function setHorasRecargoFestivoNocturno($horasRecargoFestivoNocturno): void
    {
        $this->horasRecargoFestivoNocturno = $horasRecargoFestivoNocturno;
    }

    /**
     * @return mixed
     */
    public function getHorasDescanso()
    {
        return $this->horasDescanso;
    }

    /**
     * @param mixed $horasDescanso
     */
    public function setHorasDescanso($horasDescanso): void
    {
        $this->horasDescanso = $horasDescanso;
    }

    /**
     * @return mixed
     */
    public function getHorasDiurnasCosto()
    {
        return $this->horasDiurnasCosto;
    }

    /**
     * @param mixed $horasDiurnasCosto
     */
    public function setHorasDiurnasCosto($horasDiurnasCosto): void
    {
        $this->horasDiurnasCosto = $horasDiurnasCosto;
    }

    /**
     * @return mixed
     */
    public function getHorasNocturnasCosto()
    {
        return $this->horasNocturnasCosto;
    }

    /**
     * @param mixed $horasNocturnasCosto
     */
    public function setHorasNocturnasCosto($horasNocturnasCosto): void
    {
        $this->horasNocturnasCosto = $horasNocturnasCosto;
    }

    /**
     * @return mixed
     */
    public function getHorasFestivasDiurnasCosto()
    {
        return $this->horasFestivasDiurnasCosto;
    }

    /**
     * @param mixed $horasFestivasDiurnasCosto
     */
    public function setHorasFestivasDiurnasCosto($horasFestivasDiurnasCosto): void
    {
        $this->horasFestivasDiurnasCosto = $horasFestivasDiurnasCosto;
    }

    /**
     * @return mixed
     */
    public function getHorasFestivasNocturnasCosto()
    {
        return $this->horasFestivasNocturnasCosto;
    }

    /**
     * @param mixed $horasFestivasNocturnasCosto
     */
    public function setHorasFestivasNocturnasCosto($horasFestivasNocturnasCosto): void
    {
        $this->horasFestivasNocturnasCosto = $horasFestivasNocturnasCosto;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasOrdinariasDiurnasCosto()
    {
        return $this->horasExtrasOrdinariasDiurnasCosto;
    }

    /**
     * @param mixed $horasExtrasOrdinariasDiurnasCosto
     */
    public function setHorasExtrasOrdinariasDiurnasCosto($horasExtrasOrdinariasDiurnasCosto): void
    {
        $this->horasExtrasOrdinariasDiurnasCosto = $horasExtrasOrdinariasDiurnasCosto;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasOrdinariasNocturnasCosto()
    {
        return $this->horasExtrasOrdinariasNocturnasCosto;
    }

    /**
     * @param mixed $horasExtrasOrdinariasNocturnasCosto
     */
    public function setHorasExtrasOrdinariasNocturnasCosto($horasExtrasOrdinariasNocturnasCosto): void
    {
        $this->horasExtrasOrdinariasNocturnasCosto = $horasExtrasOrdinariasNocturnasCosto;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasFestivasDiurnasCosto()
    {
        return $this->horasExtrasFestivasDiurnasCosto;
    }

    /**
     * @param mixed $horasExtrasFestivasDiurnasCosto
     */
    public function setHorasExtrasFestivasDiurnasCosto($horasExtrasFestivasDiurnasCosto): void
    {
        $this->horasExtrasFestivasDiurnasCosto = $horasExtrasFestivasDiurnasCosto;
    }

    /**
     * @return mixed
     */
    public function getHorasExtrasFestivasNocturnasCosto()
    {
        return $this->horasExtrasFestivasNocturnasCosto;
    }

    /**
     * @param mixed $horasExtrasFestivasNocturnasCosto
     */
    public function setHorasExtrasFestivasNocturnasCosto($horasExtrasFestivasNocturnasCosto): void
    {
        $this->horasExtrasFestivasNocturnasCosto = $horasExtrasFestivasNocturnasCosto;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoNocturnoCosto()
    {
        return $this->horasRecargoNocturnoCosto;
    }

    /**
     * @param mixed $horasRecargoNocturnoCosto
     */
    public function setHorasRecargoNocturnoCosto($horasRecargoNocturnoCosto): void
    {
        $this->horasRecargoNocturnoCosto = $horasRecargoNocturnoCosto;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoFestivoDiurnoCosto()
    {
        return $this->horasRecargoFestivoDiurnoCosto;
    }

    /**
     * @param mixed $horasRecargoFestivoDiurnoCosto
     */
    public function setHorasRecargoFestivoDiurnoCosto($horasRecargoFestivoDiurnoCosto): void
    {
        $this->horasRecargoFestivoDiurnoCosto = $horasRecargoFestivoDiurnoCosto;
    }

    /**
     * @return mixed
     */
    public function getHorasRecargoFestivoNocturnoCosto()
    {
        return $this->horasRecargoFestivoNocturnoCosto;
    }

    /**
     * @param mixed $horasRecargoFestivoNocturnoCosto
     */
    public function setHorasRecargoFestivoNocturnoCosto($horasRecargoFestivoNocturnoCosto): void
    {
        $this->horasRecargoFestivoNocturnoCosto = $horasRecargoFestivoNocturnoCosto;
    }

    /**
     * @return mixed
     */
    public function getHorasDescansoCosto()
    {
        return $this->horasDescansoCosto;
    }

    /**
     * @param mixed $horasDescansoCosto
     */
    public function setHorasDescansoCosto($horasDescansoCosto): void
    {
        $this->horasDescansoCosto = $horasDescansoCosto;
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
    public function getParticipacion()
    {
        return $this->participacion;
    }

    /**
     * @param mixed $participacion
     */
    public function setParticipacion($participacion): void
    {
        $this->participacion = $participacion;
    }

    /**
     * @return mixed
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * @param mixed $vrCosto
     */
    public function setVrCosto($vrCosto): void
    {
        $this->vrCosto = $vrCosto;
    }

    /**
     * @return mixed
     */
    public function getVrNomina()
    {
        return $this->vrNomina;
    }

    /**
     * @param mixed $vrNomina
     */
    public function setVrNomina($vrNomina): void
    {
        $this->vrNomina = $vrNomina;
    }

    /**
     * @return mixed
     */
    public function getVrAporte()
    {
        return $this->vrAporte;
    }

    /**
     * @param mixed $vrAporte
     */
    public function setVrAporte($vrAporte): void
    {
        $this->vrAporte = $vrAporte;
    }

    /**
     * @return mixed
     */
    public function getVrProvision()
    {
        return $this->vrProvision;
    }

    /**
     * @param mixed $vrProvision
     */
    public function setVrProvision($vrProvision): void
    {
        $this->vrProvision = $vrProvision;
    }

    /**
     * @return mixed
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * @param mixed $codigoCentroCostoFk
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk): void
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;
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
     * @return mixed
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * @param mixed $centroCostoRel
     */
    public function setCentroCostoRel($centroCostoRel): void
    {
        $this->centroCostoRel = $centroCostoRel;
    }

    /**
     * @return mixed
     */
    public function getCierreRel()
    {
        return $this->cierreRel;
    }

    /**
     * @param mixed $cierreRel
     */
    public function setCierreRel($cierreRel): void
    {
        $this->cierreRel = $cierreRel;
    }



}


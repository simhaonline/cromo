<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurPedidoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurPedidoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoPedidoDetallePk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoDetallePk;

    /**
     * @ORM\Column(name="codigo_pedido_fk", type="integer")
     */
    private $codigoPedidoFk;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="integer", nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="codigo_modalidad_fk", type="string", length=10, nullable=true)
     */
    private $codigoModalidadFk;

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */
    private $codigoPuestoFk;

    /**
     * @ORM\Column(name="periodo", type="string", length=1, nullable=true)
     */
    private $periodo;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="dia_desde", type="integer")
     */
    private $diaDesde = 1;

    /**
     * @ORM\Column(name="dia_hasta", type="integer")
     */
    private $diaHasta = 1;

    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;

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
     * @ORM\Column(name="horas_programadas", type="float")
     */
    private $horasProgramadas = 0;

    /**
     * @ORM\Column(name="horas_diurnas_programadas", type="float")
     */
    private $horasDiurnasProgramadas = 0;

    /**
     * @ORM\Column(name="horas_nocturnas_programadas", type="float")
     */
    private $horasNocturnasProgramadas = 0;

    /**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="vr_precio_ajustado", type="float", nullable=true)
     */
    private $vrPrecioAjustado = 0;

    /**
     * @ORM\Column(name="vr_precio_minimo", type="float")
     */
    private $vrPrecioMinimo = 0;

    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="float", nullable=true)
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="porcentaje_base_iva", type="float", nullable=true)
     */
    private $porcentajeBaseIva = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_base_aiu", type="float")
     */
    private $vrBaseAiu = 0;

    /**
     * @ORM\Column(name="vr_total_detalle", type="float")
     */
    private $vrTotalDetalle = 0;

    /**
     * @ORM\Column(name="vr_total_detalle_afectado", type="float")
     */
    private $vrTotalDetalleAfectado = 0;

    /**
     * @ORM\Column(name="vr_total_detalle_pendiente", type="float")
     */
    private $vrTotalDetallePendiente = 0;

    /**
     * @ORM\Column(name="vr_total_detalle_devolucion", type="float")
     */
    private $vrTotalDetalleDevolucion = 0;

    /**
     * @ORM\Column(name="vr_total_detalle_adicion", type="float")
     */
    private $vrTotalDetalleAdicion = 0;

    /**
     * @ORM\Column(name="lunes", type="boolean")
     */
    private $lunes = false;

    /**
     * @ORM\Column(name="martes", type="boolean")
     */
    private $martes = false;

    /**
     * @ORM\Column(name="miercoles", type="boolean")
     */
    private $miercoles = false;

    /**
     * @ORM\Column(name="jueves", type="boolean")
     */
    private $jueves = false;

    /**
     * @ORM\Column(name="viernes", type="boolean")
     */
    private $viernes = false;

    /**
     * @ORM\Column(name="sabado", type="boolean")
     */
    private $sabado = false;

    /**
     * @ORM\Column(name="domingo", type="boolean")
     */
    private $domingo = false;

    /**
     * @ORM\Column(name="festivo", type="boolean")
     */
    private $festivo = false;

    /**
     * @ORM\Column(name="detalle", type="string", length=300, nullable=true)
     * * @Assert\Length(
     *     max = 300,
     *     maxMessage="El campo no puede contener mas de 300 caracteres"
     * )
     */
    private $detalle;

    /**
     *
     * @ORM\Column(name="detalle_factura", type="string", nullable=true, length=150)
     */
    private $detalleFactura;

    /**
     * @ORM\Column(name="vr_salario_base", type="float")
     */
    private $vrSalarioBase = 0;

    /**
     * @ORM\Column(name="hora_inicio", type="time", nullable=true)
     */
    private $horaInicio;

    /**
     * @ORM\Column(name="hora_fin", type="time", nullable=true)
     */
    private $horaFin;

    /**
     * @ORM\Column(name="compuesto", type="boolean")
     */
    private $compuesto = false;

    /**
     * @ORM\Column(name="estado_terminado", type="boolean", options={"default":false})
     */
    private $estadoTerminado = false;

    /**
     * @ORM\ManyToOne(targetEntity="TurPedido", inversedBy="pedidosDetallesPedidoRel")
     * @ORM\JoinColumn(name="codigo_pedido_fk", referencedColumnName="codigo_pedido_pk")
     */
    protected $pedidoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurConcepto", inversedBy="pedidosDetallesConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurModalidad", inversedBy="pedidosDetallesModalidadRel")
     * @ORM\JoinColumn(name="codigo_modalidad_fk", referencedColumnName="codigo_modalidad_pk")
     */
    protected $modalidadRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="pedidosDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurProgramacion", mappedBy="pedidoDetalleRel")
     */
    protected $programacionesPedidoDetalleRel;

    /**
     * @return mixed
     */
    public function getCodigoPedidoDetallePk()
    {
        return $this->codigoPedidoDetallePk;
    }

    /**
     * @param mixed $codigoPedidoDetallePk
     */
    public function setCodigoPedidoDetallePk($codigoPedidoDetallePk): void
    {
        $this->codigoPedidoDetallePk = $codigoPedidoDetallePk;
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
    public function getCodigoConceptoFk()
    {
        return $this->codigoConceptoFk;
    }

    /**
     * @param mixed $codigoConceptoFk
     */
    public function setCodigoConceptoFk($codigoConceptoFk): void
    {
        $this->codigoConceptoFk = $codigoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoModalidadFk()
    {
        return $this->codigoModalidadFk;
    }

    /**
     * @param mixed $codigoModalidadFk
     */
    public function setCodigoModalidadFk($codigoModalidadFk): void
    {
        $this->codigoModalidadFk = $codigoModalidadFk;
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
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * @param mixed $periodo
     */
    public function setPeriodo($periodo): void
    {
        $this->periodo = $periodo;
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
    public function getDiaDesde()
    {
        return $this->diaDesde;
    }

    /**
     * @param mixed $diaDesde
     */
    public function setDiaDesde($diaDesde): void
    {
        $this->diaDesde = $diaDesde;
    }

    /**
     * @return mixed
     */
    public function getDiaHasta()
    {
        return $this->diaHasta;
    }

    /**
     * @param mixed $diaHasta
     */
    public function setDiaHasta($diaHasta): void
    {
        $this->diaHasta = $diaHasta;
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
    public function getHorasProgramadas()
    {
        return $this->horasProgramadas;
    }

    /**
     * @param mixed $horasProgramadas
     */
    public function setHorasProgramadas($horasProgramadas): void
    {
        $this->horasProgramadas = $horasProgramadas;
    }

    /**
     * @return mixed
     */
    public function getHorasDiurnasProgramadas()
    {
        return $this->horasDiurnasProgramadas;
    }

    /**
     * @param mixed $horasDiurnasProgramadas
     */
    public function setHorasDiurnasProgramadas($horasDiurnasProgramadas): void
    {
        $this->horasDiurnasProgramadas = $horasDiurnasProgramadas;
    }

    /**
     * @return mixed
     */
    public function getHorasNocturnasProgramadas()
    {
        return $this->horasNocturnasProgramadas;
    }

    /**
     * @param mixed $horasNocturnasProgramadas
     */
    public function setHorasNocturnasProgramadas($horasNocturnasProgramadas): void
    {
        $this->horasNocturnasProgramadas = $horasNocturnasProgramadas;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return mixed
     */
    public function getVrPrecioAjustado()
    {
        return $this->vrPrecioAjustado;
    }

    /**
     * @param mixed $vrPrecioAjustado
     */
    public function setVrPrecioAjustado($vrPrecioAjustado): void
    {
        $this->vrPrecioAjustado = $vrPrecioAjustado;
    }

    /**
     * @return mixed
     */
    public function getVrPrecioMinimo()
    {
        return $this->vrPrecioMinimo;
    }

    /**
     * @param mixed $vrPrecioMinimo
     */
    public function setVrPrecioMinimo($vrPrecioMinimo): void
    {
        $this->vrPrecioMinimo = $vrPrecioMinimo;
    }

    /**
     * @return mixed
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * @param mixed $vrPrecio
     */
    public function setVrPrecio($vrPrecio): void
    {
        $this->vrPrecio = $vrPrecio;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeIva()
    {
        return $this->porcentajeIva;
    }

    /**
     * @param mixed $porcentajeIva
     */
    public function setPorcentajeIva($porcentajeIva): void
    {
        $this->porcentajeIva = $porcentajeIva;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeBaseIva()
    {
        return $this->porcentajeBaseIva;
    }

    /**
     * @param mixed $porcentajeBaseIva
     */
    public function setPorcentajeBaseIva($porcentajeBaseIva): void
    {
        $this->porcentajeBaseIva = $porcentajeBaseIva;
    }

    /**
     * @return mixed
     */
    public function getVrSubtotal()
    {
        return $this->vrSubtotal;
    }

    /**
     * @param mixed $vrSubtotal
     */
    public function setVrSubtotal($vrSubtotal): void
    {
        $this->vrSubtotal = $vrSubtotal;
    }

    /**
     * @return mixed
     */
    public function getVrIva()
    {
        return $this->vrIva;
    }

    /**
     * @param mixed $vrIva
     */
    public function setVrIva($vrIva): void
    {
        $this->vrIva = $vrIva;
    }

    /**
     * @return mixed
     */
    public function getVrBaseAiu()
    {
        return $this->vrBaseAiu;
    }

    /**
     * @param mixed $vrBaseAiu
     */
    public function setVrBaseAiu($vrBaseAiu): void
    {
        $this->vrBaseAiu = $vrBaseAiu;
    }

    /**
     * @return mixed
     */
    public function getVrTotalDetalle()
    {
        return $this->vrTotalDetalle;
    }

    /**
     * @param mixed $vrTotalDetalle
     */
    public function setVrTotalDetalle($vrTotalDetalle): void
    {
        $this->vrTotalDetalle = $vrTotalDetalle;
    }

    /**
     * @return mixed
     */
    public function getVrTotalDetalleAfectado()
    {
        return $this->vrTotalDetalleAfectado;
    }

    /**
     * @param mixed $vrTotalDetalleAfectado
     */
    public function setVrTotalDetalleAfectado($vrTotalDetalleAfectado): void
    {
        $this->vrTotalDetalleAfectado = $vrTotalDetalleAfectado;
    }

    /**
     * @return mixed
     */
    public function getVrTotalDetallePendiente()
    {
        return $this->vrTotalDetallePendiente;
    }

    /**
     * @param mixed $vrTotalDetallePendiente
     */
    public function setVrTotalDetallePendiente($vrTotalDetallePendiente): void
    {
        $this->vrTotalDetallePendiente = $vrTotalDetallePendiente;
    }

    /**
     * @return mixed
     */
    public function getVrTotalDetalleDevolucion()
    {
        return $this->vrTotalDetalleDevolucion;
    }

    /**
     * @param mixed $vrTotalDetalleDevolucion
     */
    public function setVrTotalDetalleDevolucion($vrTotalDetalleDevolucion): void
    {
        $this->vrTotalDetalleDevolucion = $vrTotalDetalleDevolucion;
    }

    /**
     * @return mixed
     */
    public function getVrTotalDetalleAdicion()
    {
        return $this->vrTotalDetalleAdicion;
    }

    /**
     * @param mixed $vrTotalDetalleAdicion
     */
    public function setVrTotalDetalleAdicion($vrTotalDetalleAdicion): void
    {
        $this->vrTotalDetalleAdicion = $vrTotalDetalleAdicion;
    }

    /**
     * @return mixed
     */
    public function getLunes()
    {
        return $this->lunes;
    }

    /**
     * @param mixed $lunes
     */
    public function setLunes($lunes): void
    {
        $this->lunes = $lunes;
    }

    /**
     * @return mixed
     */
    public function getMartes()
    {
        return $this->martes;
    }

    /**
     * @param mixed $martes
     */
    public function setMartes($martes): void
    {
        $this->martes = $martes;
    }

    /**
     * @return mixed
     */
    public function getMiercoles()
    {
        return $this->miercoles;
    }

    /**
     * @param mixed $miercoles
     */
    public function setMiercoles($miercoles): void
    {
        $this->miercoles = $miercoles;
    }

    /**
     * @return mixed
     */
    public function getJueves()
    {
        return $this->jueves;
    }

    /**
     * @param mixed $jueves
     */
    public function setJueves($jueves): void
    {
        $this->jueves = $jueves;
    }

    /**
     * @return mixed
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * @param mixed $viernes
     */
    public function setViernes($viernes): void
    {
        $this->viernes = $viernes;
    }

    /**
     * @return mixed
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * @param mixed $sabado
     */
    public function setSabado($sabado): void
    {
        $this->sabado = $sabado;
    }

    /**
     * @return mixed
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * @param mixed $domingo
     */
    public function setDomingo($domingo): void
    {
        $this->domingo = $domingo;
    }

    /**
     * @return mixed
     */
    public function getFestivo()
    {
        return $this->festivo;
    }

    /**
     * @param mixed $festivo
     */
    public function setFestivo($festivo): void
    {
        $this->festivo = $festivo;
    }

    /**
     * @return mixed
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param mixed $detalle
     */
    public function setDetalle($detalle): void
    {
        $this->detalle = $detalle;
    }

    /**
     * @return mixed
     */
    public function getDetalleFactura()
    {
        return $this->detalleFactura;
    }

    /**
     * @param mixed $detalleFactura
     */
    public function setDetalleFactura($detalleFactura): void
    {
        $this->detalleFactura = $detalleFactura;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioBase()
    {
        return $this->vrSalarioBase;
    }

    /**
     * @param mixed $vrSalarioBase
     */
    public function setVrSalarioBase($vrSalarioBase): void
    {
        $this->vrSalarioBase = $vrSalarioBase;
    }

    /**
     * @return mixed
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * @param mixed $horaInicio
     */
    public function setHoraInicio($horaInicio): void
    {
        $this->horaInicio = $horaInicio;
    }

    /**
     * @return mixed
     */
    public function getHoraFin()
    {
        return $this->horaFin;
    }

    /**
     * @param mixed $horaFin
     */
    public function setHoraFin($horaFin): void
    {
        $this->horaFin = $horaFin;
    }

    /**
     * @return mixed
     */
    public function getCompuesto()
    {
        return $this->compuesto;
    }

    /**
     * @param mixed $compuesto
     */
    public function setCompuesto($compuesto): void
    {
        $this->compuesto = $compuesto;
    }

    /**
     * @return mixed
     */
    public function getEstadoTerminado()
    {
        return $this->estadoTerminado;
    }

    /**
     * @param mixed $estadoTerminado
     */
    public function setEstadoTerminado($estadoTerminado): void
    {
        $this->estadoTerminado = $estadoTerminado;
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
    public function getConceptoRel()
    {
        return $this->conceptoRel;
    }

    /**
     * @param mixed $conceptoRel
     */
    public function setConceptoRel($conceptoRel): void
    {
        $this->conceptoRel = $conceptoRel;
    }

    /**
     * @return mixed
     */
    public function getModalidadRel()
    {
        return $this->modalidadRel;
    }

    /**
     * @param mixed $modalidadRel
     */
    public function setModalidadRel($modalidadRel): void
    {
        $this->modalidadRel = $modalidadRel;
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
    public function getProgramacionesPedidoDetalleRel()
    {
        return $this->programacionesPedidoDetalleRel;
    }

    /**
     * @param mixed $programacionesPedidoDetalleRel
     */
    public function setProgramacionesPedidoDetalleRel($programacionesPedidoDetalleRel): void
    {
        $this->programacionesPedidoDetalleRel = $programacionesPedidoDetalleRel;
    }



}


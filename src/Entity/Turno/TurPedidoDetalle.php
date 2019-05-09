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
     * @ORM\Column(name="codigo_contrato_concepto_fk", type="integer", nullable=true)
     */
    private $codigoContratoConceptoFk;

    /**
     * @ORM\Column(name="codigo_contrato_concepto_facturacion_fk", type="integer", nullable=true)
     */
    private $codigoContratoConceptoFacturacionFk;

    /**
     * @ORM\Column(name="codigo_contrato_modalidad_fk", type="string", length=10, nullable=true)
     */
    private $codigoContratoModalidadFk;

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */
    private $codigoPuestoFk;

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
     * @ORM\Column(name="factura_distribuida", type="boolean", nullable=true)
     */
    private $facturaDistribuida = false;

    /**
     * @ORM\ManyToOne(targetEntity="TurContratoConcepto", inversedBy="pedidosDetallesContratoConceptoRel")
     * @ORM\JoinColumn(name="codigo_contrato_concepto_fk", referencedColumnName="codigo_contrato_concepto_pk")
     */
    protected $contratoConceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurContratoConcepto", inversedBy="pedidosDetallesContratoConceptoFacturacionRel")
     * @ORM\JoinColumn(name="codigo_contrato_concepto_facturacion_fk", referencedColumnName="codigo_contrato_concepto_pk")
     */
    protected $contratoConceptoFacturacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurContratoModalidad", inversedBy="pedidosDetallesContratoModalidadRel")
     * @ORM\JoinColumn(name="codigo_contrato_modalidad_fk", referencedColumnName="codigo_contrato_modalidad_pk")
     */
    protected $contratoModalidadRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="pedidosDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;

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
    public function getCodigoContratoConceptoFk()
    {
        return $this->codigoContratoConceptoFk;
    }

    /**
     * @param mixed $codigoContratoConceptoFk
     */
    public function setCodigoContratoConceptoFk($codigoContratoConceptoFk): void
    {
        $this->codigoContratoConceptoFk = $codigoContratoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoContratoConceptoFacturacionFk()
    {
        return $this->codigoContratoConceptoFacturacionFk;
    }

    /**
     * @param mixed $codigoContratoConceptoFacturacionFk
     */
    public function setCodigoContratoConceptoFacturacionFk($codigoContratoConceptoFacturacionFk): void
    {
        $this->codigoContratoConceptoFacturacionFk = $codigoContratoConceptoFacturacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoContratoModalidadFk()
    {
        return $this->codigoContratoModalidadFk;
    }

    /**
     * @param mixed $codigoContratoModalidadFk
     */
    public function setCodigoContratoModalidadFk($codigoContratoModalidadFk): void
    {
        $this->codigoContratoModalidadFk = $codigoContratoModalidadFk;
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
    public function getFacturaDistribuida()
    {
        return $this->facturaDistribuida;
    }

    /**
     * @param mixed $facturaDistribuida
     */
    public function setFacturaDistribuida($facturaDistribuida): void
    {
        $this->facturaDistribuida = $facturaDistribuida;
    }

    /**
     * @return mixed
     */
    public function getContratoConceptoRel()
    {
        return $this->contratoConceptoRel;
    }

    /**
     * @param mixed $contratoConceptoRel
     */
    public function setContratoConceptoRel($contratoConceptoRel): void
    {
        $this->contratoConceptoRel = $contratoConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getContratoConceptoFacturacionRel()
    {
        return $this->contratoConceptoFacturacionRel;
    }

    /**
     * @param mixed $contratoConceptoFacturacionRel
     */
    public function setContratoConceptoFacturacionRel($contratoConceptoFacturacionRel): void
    {
        $this->contratoConceptoFacturacionRel = $contratoConceptoFacturacionRel;
    }

    /**
     * @return mixed
     */
    public function getContratoModalidadRel()
    {
        return $this->contratoModalidadRel;
    }

    /**
     * @param mixed $contratoModalidadRel
     */
    public function setContratoModalidadRel($contratoModalidadRel): void
    {
        $this->contratoModalidadRel = $contratoModalidadRel;
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

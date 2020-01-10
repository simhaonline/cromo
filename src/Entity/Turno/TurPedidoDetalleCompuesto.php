<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurPedidoDetalleCompuestoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurPedidoDetalleCompuesto
{
    public $infoLog = [
        "primaryKey" => "codigoPedidoDetalleCompuestoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_detalle_compuesto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoDetalleCompuestoPk;


    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer")
     */
    private $codigoPedidoDetalleFk;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="integer", nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="codigo_modalidad_fk", type="string", length=10)
     */
    private $codigoModalidadFk;

    /**
     * @ORM\Column(name="periodo", type="string", nullable=true, length=1)
     */
    private $periodo;

    /**
     * @ORM\Column(name="dia_desde", type="integer")
     */
    private $diaDesde = 1;

    /**
     * @ORM\Column(name="dia_hasta", type="integer")
     */
    private $diaHasta = 1;

    /**
     * @ORM\Column(name="dias_reales", type="boolean")
     */
    private $diasReales = false;

    /**
     * @ORM\Column(name="hora_desde", type="time", nullable=true)
     */
    private $horaDesde;

    /**
     * @ORM\Column(name="hora_hasta", type="time", nullable=true)
     */
    private $horaHasta;

    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="horas_unidad", type="float", nullable=true)
     */
    private $horasUnidad = 0;

    /**
     * @ORM\Column(name="horas_diurnas_unidad", type="float", nullable=true)
     */
    private $horasDiurnasUnidad = 0;

    /**
     * @ORM\Column(name="horas_nocturnas_unidad", type="float", nullable=true)
     */
    private $horasNocturnasUnidad = 0;

    /**
     * @ORM\Column(name="horas", type="integer")
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="horas_diurnas", type="integer")
     */
    private $horasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_nocturnas", type="integer")
     */
    private $horasNocturnas = 0;

    /**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="vr_precio_ajustado", type="float")
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
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_base_iva", type="float")
     */
    private $vrBaseIva = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="float")
     */
    private $porcentajeIva = 0;

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
     * @ORM\Column(name="dia_31", type="boolean")
     */
    private $dia31 = false;

    /**
     * @ORM\Column(name="detalle", type="string", length=300, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="pedidosDetallesCompuestosPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurConcepto", inversedBy="pedidosDetallesCompuestosConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurModalidad", inversedBy="pedidosDetallesCompuestosModalidadRel")
     * @ORM\JoinColumn(name="codigo_modalidad_fk", referencedColumnName="codigo_modalidad_pk")
     */
    protected $modalidadRel;

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
    public function getCodigoPedidoDetalleCompuestoPk()
    {
        return $this->codigoPedidoDetalleCompuestoPk;
    }

    /**
     * @param mixed $codigoPedidoDetalleCompuestoPk
     */
    public function setCodigoPedidoDetalleCompuestoPk($codigoPedidoDetalleCompuestoPk): void
    {
        $this->codigoPedidoDetalleCompuestoPk = $codigoPedidoDetalleCompuestoPk;
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
    public function getDiasReales()
    {
        return $this->diasReales;
    }

    /**
     * @param mixed $diasReales
     */
    public function setDiasReales($diasReales): void
    {
        $this->diasReales = $diasReales;
    }

    /**
     * @return mixed
     */
    public function getHoraDesde()
    {
        return $this->horaDesde;
    }

    /**
     * @param mixed $horaDesde
     */
    public function setHoraDesde($horaDesde): void
    {
        $this->horaDesde = $horaDesde;
    }

    /**
     * @return mixed
     */
    public function getHoraHasta()
    {
        return $this->horaHasta;
    }

    /**
     * @param mixed $horaHasta
     */
    public function setHoraHasta($horaHasta): void
    {
        $this->horaHasta = $horaHasta;
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
    public function getHorasUnidad()
    {
        return $this->horasUnidad;
    }

    /**
     * @param mixed $horasUnidad
     */
    public function setHorasUnidad($horasUnidad): void
    {
        $this->horasUnidad = $horasUnidad;
    }

    /**
     * @return mixed
     */
    public function getHorasDiurnasUnidad()
    {
        return $this->horasDiurnasUnidad;
    }

    /**
     * @param mixed $horasDiurnasUnidad
     */
    public function setHorasDiurnasUnidad($horasDiurnasUnidad): void
    {
        $this->horasDiurnasUnidad = $horasDiurnasUnidad;
    }

    /**
     * @return mixed
     */
    public function getHorasNocturnasUnidad()
    {
        return $this->horasNocturnasUnidad;
    }

    /**
     * @param mixed $horasNocturnasUnidad
     */
    public function setHorasNocturnasUnidad($horasNocturnasUnidad): void
    {
        $this->horasNocturnasUnidad = $horasNocturnasUnidad;
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
    public function getVrBaseIva()
    {
        return $this->vrBaseIva;
    }

    /**
     * @param mixed $vrBaseIva
     */
    public function setVrBaseIva($vrBaseIva): void
    {
        $this->vrBaseIva = $vrBaseIva;
    }

    /**
     * @return mixed
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
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



}


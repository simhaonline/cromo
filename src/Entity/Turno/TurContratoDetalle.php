<?php

namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurContratoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurContratoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoContratoDetallePk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoDetallePk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_contrato_concepto_fk", type="integer", nullable=true)
     */
    private $codigoContratoConceptoFk;

    /**
     * @ORM\Column(name="codigo_contrato_modalidad_fk", type="string", length=10, nullable=true)
     */
    private $codigoContratoModalidadFk;

    /**
     * @ORM\Column(name="codigo_contrato_concepto_facturacion_fk", type="integer", nullable=true)
     */
    private $codigoContratoConceptoFacturacionFk;

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */
    private $codigoPuestoFk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="liquidar_dias_reales", type="boolean")
     */
    private $liquidarDiasReales = false;

    /**
     * @ORM\Column(name="compuesto", type="boolean")
     */
    private $compuesto = false;

    /**
     * @ORM\Column(name="no_facturar", type="boolean", nullable=true)
     */
    private $noFacturar = false;

    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;

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
     * @ORM\Column(name="cantidad_recurso", type="integer")
     */
    private $cantidadRecurso = 0;

    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;

    /**
     * @ORM\Column(name="vr_precio_ajustado", type="float")
     */
    private $vrPrecioAjustado = 0;

    /**
     * @ORM\Column(name="vr_precio_ajustado_anterior", type="float", nullable=true)
     */
    private $vrPrecioAjustadoAnterior = 0;

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
     * @ORM\Column(name="vr_base_aiu", type="float")
     */
    private $vrBaseAiu = 0;

    /**
     * @ORM\Column(name="vr_total_detalle", type="float")
     */
    private $vrTotalDetalle = 0;

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
     * @ORM\Column(name="dias_secuencia", type="integer")
     */
    private $diasSecuencia = 0;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = false;

    /**
     * @ORM\Column(name="vr_salario_base", type="float")
     */
    private $vrSalarioBase = 0;

    /**
     * @ORM\Column(name="porcentaje_iva", type="float", nullable=true)
     */
    private $porcentajeIva = 0;

    /**
     * @ORM\Column(name="porcentaje_base_iva", type="float", nullable=true)
     */
    private $porcentajeBaseIva = 0;

    /**
     * @ORM\Column(name="hora_inicio", type="time", nullable=true)
     */
    private $horaInicio;

    /**
     * @ORM\Column(name="hora_fin", type="time", nullable=true)
     */
    private $horaFin;

    /**
     * @ORM\Column(name="detalle_facturas", type="string", length=150, nullable=true)
     */
    private $detalleFactura;

    /**
     * @ORM\Column(name="sumar_base_iva", type="boolean", nullable=true)
     */
    private $sumarBaseIva = false;

    /**
     * @ORM\Column(name="factura_distribuida", type="boolean", nullable=true)
     */
    private $facturaDistribuida = false;

    /**
     * @ORM\ManyToOne(targetEntity="TurContrato", inversedBy="contratosDetallesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurContratoConcepto", inversedBy="contratosDetallesContratoConceptoRel")
     * @ORM\JoinColumn(name="codigo_contrato_concepto_fk", referencedColumnName="codigo_contrato_concepto_pk")
     */
    protected $contratoConceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurContratoModalidad", inversedBy="contratosDetallesContratoModalidadRel")
     * @ORM\JoinColumn(name="codigo_contrato_modalidad_fk", referencedColumnName="codigo_contrato_modalidad_pk")
     */
    protected $contratoModalidadRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurContratoConcepto", inversedBy="contratosDetallesContratoConceptoFacturacionRel")
     * @ORM\JoinColumn(name="codigo_contrato_concepto_facturacion_fk", referencedColumnName="codigo_contrato_concepto_pk")
     */
    protected $contratoConceptoFacturacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="contratosDetallesPuestoRel")
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
    public function getCodigoContratoDetallePk()
    {
        return $this->codigoContratoDetallePk;
    }

    /**
     * @param mixed $codigoContratoDetallePk
     */
    public function setCodigoContratoDetallePk($codigoContratoDetallePk): void
    {
        $this->codigoContratoDetallePk = $codigoContratoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * @param mixed $codigoContratoFk
     */
    public function setCodigoContratoFk($codigoContratoFk): void
    {
        $this->codigoContratoFk = $codigoContratoFk;
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
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * @param mixed $fechaDesde
     */
    public function setFechaDesde($fechaDesde): void
    {
        $this->fechaDesde = $fechaDesde;
    }

    /**
     * @return mixed
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * @param mixed $fechaHasta
     */
    public function setFechaHasta($fechaHasta): void
    {
        $this->fechaHasta = $fechaHasta;
    }

    /**
     * @return mixed
     */
    public function getLiquidarDiasReales()
    {
        return $this->liquidarDiasReales;
    }

    /**
     * @param mixed $liquidarDiasReales
     */
    public function setLiquidarDiasReales($liquidarDiasReales): void
    {
        $this->liquidarDiasReales = $liquidarDiasReales;
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
    public function getNoFacturar()
    {
        return $this->noFacturar;
    }

    /**
     * @param mixed $noFacturar
     */
    public function setNoFacturar($noFacturar): void
    {
        $this->noFacturar = $noFacturar;
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
    public function getCantidadRecurso()
    {
        return $this->cantidadRecurso;
    }

    /**
     * @param mixed $cantidadRecurso
     */
    public function setCantidadRecurso($cantidadRecurso): void
    {
        $this->cantidadRecurso = $cantidadRecurso;
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
    public function getVrPrecioAjustadoAnterior()
    {
        return $this->vrPrecioAjustadoAnterior;
    }

    /**
     * @param mixed $vrPrecioAjustadoAnterior
     */
    public function setVrPrecioAjustadoAnterior($vrPrecioAjustadoAnterior): void
    {
        $this->vrPrecioAjustadoAnterior = $vrPrecioAjustadoAnterior;
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
    public function getDiasSecuencia()
    {
        return $this->diasSecuencia;
    }

    /**
     * @param mixed $diasSecuencia
     */
    public function setDiasSecuencia($diasSecuencia): void
    {
        $this->diasSecuencia = $diasSecuencia;
    }

    /**
     * @return mixed
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * @param mixed $estadoCerrado
     */
    public function setEstadoCerrado($estadoCerrado): void
    {
        $this->estadoCerrado = $estadoCerrado;
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
    public function getSumarBaseIva()
    {
        return $this->sumarBaseIva;
    }

    /**
     * @param mixed $sumarBaseIva
     */
    public function setSumarBaseIva($sumarBaseIva): void
    {
        $this->sumarBaseIva = $sumarBaseIva;
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
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * @param mixed $contratoRel
     */
    public function setContratoRel($contratoRel): void
    {
        $this->contratoRel = $contratoRel;
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

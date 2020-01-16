<?php

namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurContratoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @ORM\Table(name="tur_contrato_detalle")
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
     * @ORM\Column(name="codigo_concepto_fk", type="integer", nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */
    private $codigoItemFk;

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
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="hora_desde", type="time", nullable=true)
     */
    private $horaDesde;

    /**
     * @ORM\Column(name="hora_hasta", type="time", nullable=true)
     */
    private $horaHasta;

    /**
     * @ORM\Column(name="compuesto", type="boolean", options={"default":false})
     */
    private $compuesto = false;

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
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio = 0;

    /**
     * @ORM\Column(name="vr_precio_minimo", type="float")
     */
    private $vrPrecioMinimo = 0;

    /**
     * @ORM\Column(name="vr_precio_ajustado", type="float", options={"default":0})
     */
    private $vrPrecioAjustado = 0;

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
     * @ORM\Column(name="lunes", type="boolean", options={"default":false})
     */
    private $lunes = false;

    /**
     * @ORM\Column(name="martes", type="boolean", options={"default":false})
     */
    private $martes = false;

    /**
     * @ORM\Column(name="miercoles", type="boolean", options={"default":false})
     */
    private $miercoles = false;

    /**
     * @ORM\Column(name="jueves", type="boolean", options={"default":false})
     */
    private $jueves = false;

    /**
     * @ORM\Column(name="viernes", type="boolean", options={"default":false})
     */
    private $viernes = false;

    /**
     * @ORM\Column(name="sabado", type="boolean", options={"default":false})
     */
    private $sabado = false;

    /**
     * @ORM\Column(name="domingo", type="boolean", options={"default":false})
     */
    private $domingo = false;

    /**
     * @ORM\Column(name="festivo", type="boolean", options={"default":false})
     */
    private $festivo = false;

    /**
     * @ORM\Column(name="estado_terminado", type="boolean", options={"default":false})
     */
    private $estadoTerminado = false;

    /**
     * @ORM\Column(name="liquidar_dias_reales", type="boolean", options={"default":false})
     */
    private $liquidarDiasReales = false;

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
     * @ORM\Column(name="codigo_grupo_fk", type="integer", nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="programar", type="boolean", options={"default":false})
     */
    private $programar = false;

    /**
     * @ORM\Column(name="cortesia", type="boolean", options={"default":false})
     */
    private $cortesia = false;

    /**
     * @ORM\ManyToOne(targetEntity="TurContrato", inversedBy="contratosDetallesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurConcepto", inversedBy="contratosDetallesConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurItem", inversedBy="contratosDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurModalidad", inversedBy="contratosDetallesModalidadRel")
     * @ORM\JoinColumn(name="codigo_modalidad_fk", referencedColumnName="codigo_modalidad_pk")
     */
    protected $modalidadRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="contratosDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurGrupo", inversedBy="contratosDetallesGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk", referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPrototipo", mappedBy="contratoDetalleRel")
     */
    protected $prototiposContratoDetalleRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="contratoDetalleRel")
     */
    protected $pedidosDetallesContratoDetalleRel;

    /**
     * @ORM\OneToMany(targetEntity="TurContratoDetalleCompuesto", mappedBy="contratoDetalleRel")
     */
    protected $contratoDetallesCompuestosContratoDetalleRel;

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
    public function getCodigoItemFk()
    {
        return $this->codigoItemFk;
    }

    /**
     * @param mixed $codigoItemFk
     */
    public function setCodigoItemFk($codigoItemFk): void
    {
        $this->codigoItemFk = $codigoItemFk;
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
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk($codigoGrupoFk): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
    }

    /**
     * @return mixed
     */
    public function getProgramar()
    {
        return $this->programar;
    }

    /**
     * @param mixed $programar
     */
    public function setProgramar($programar): void
    {
        $this->programar = $programar;
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
    public function getItemRel()
    {
        return $this->itemRel;
    }

    /**
     * @param mixed $itemRel
     */
    public function setItemRel($itemRel): void
    {
        $this->itemRel = $itemRel;
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
    public function getGrupoRel()
    {
        return $this->grupoRel;
    }

    /**
     * @param mixed $grupoRel
     */
    public function setGrupoRel($grupoRel): void
    {
        $this->grupoRel = $grupoRel;
    }

    /**
     * @return mixed
     */
    public function getPrototiposContratoDetalleRel()
    {
        return $this->prototiposContratoDetalleRel;
    }

    /**
     * @param mixed $prototiposContratoDetalleRel
     */
    public function setPrototiposContratoDetalleRel($prototiposContratoDetalleRel): void
    {
        $this->prototiposContratoDetalleRel = $prototiposContratoDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosDetallesContratoDetalleRel()
    {
        return $this->pedidosDetallesContratoDetalleRel;
    }

    /**
     * @param mixed $pedidosDetallesContratoDetalleRel
     */
    public function setPedidosDetallesContratoDetalleRel($pedidosDetallesContratoDetalleRel): void
    {
        $this->pedidosDetallesContratoDetalleRel = $pedidosDetallesContratoDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getContratoDetallesCompuestosContratoDetalleRel()
    {
        return $this->contratoDetallesCompuestosContratoDetalleRel;
    }

    /**
     * @param mixed $contratoDetallesCompuestosContratoDetalleRel
     */
    public function setContratoDetallesCompuestosContratoDetalleRel($contratoDetallesCompuestosContratoDetalleRel): void
    {
        $this->contratoDetallesCompuestosContratoDetalleRel = $contratoDetallesCompuestosContratoDetalleRel;
    }

    /**
     * @return mixed
     */
    public function getCortesia()
    {
        return $this->cortesia;
    }

    /**
     * @param mixed $cortesia
     */
    public function setCortesia($cortesia): void
    {
        $this->cortesia = $cortesia;
    }




}

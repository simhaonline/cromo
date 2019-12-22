<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurCostoServicioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurCostoServicio
{
    public $infoLog = [
        "primaryKey" => "codigoCostoServicioPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_servicio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCostoServicioPk;

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
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */
    private $codigoPedidoDetalleFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */
    private $codigoPuestoFk;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="integer")
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="codigo_modalidad_fk", type="string", length=10, nullable=true)
     */
    private $codigoModalidadFk;

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
     * @ORM\Column(name="horas_programadas", type="integer")
     */
    private $horasProgramadas = 0;

    /**
     * @ORM\Column(name="horas_diurnas_programadas", type="integer")
     */
    private $horasDiurnasProgramadas = 0;

    /**
     * @ORM\Column(name="horas_nocturnas_programadas", type="integer")
     */
    private $horasNocturnasProgramadas = 0;

    /**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="margen", type="float",nullable=true)
     *
     */
    private  $margen = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurCierre", inversedBy="costosServiciosCierreRel")
     * @ORM\JoinColumn(name="codigo_cierre_fk", referencedColumnName="codigo_cierre_pk")
     */
    protected $cierreRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="costosServiciosPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="costosServiciosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="costosServiciosPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurConcepto", inversedBy="costosServiciosConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_pk")
     */
    protected $conceptoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurModalidad", inversedBy="costosServiciosModalidadServicioRel")
     * @ORM\JoinColumn(name="codigo_modalidad_fk", referencedColumnName="codigo_modalidad_pk")
     */
    protected $modalidadRel;

    /**
     * @return mixed
     */
    public function getCodigoCostoServicioPk()
    {
        return $this->codigoCostoServicioPk;
    }

    /**
     * @param mixed $codigoCostoServicioPk
     */
    public function setCodigoCostoServicioPk($codigoCostoServicioPk): void
    {
        $this->codigoCostoServicioPk = $codigoCostoServicioPk;
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
     * @return int
     */
    public function getDiaDesde(): int
    {
        return $this->diaDesde;
    }

    /**
     * @param int $diaDesde
     */
    public function setDiaDesde(int $diaDesde): void
    {
        $this->diaDesde = $diaDesde;
    }

    /**
     * @return int
     */
    public function getDiaHasta(): int
    {
        return $this->diaHasta;
    }

    /**
     * @param int $diaHasta
     */
    public function setDiaHasta(int $diaHasta): void
    {
        $this->diaHasta = $diaHasta;
    }

    /**
     * @return int
     */
    public function getDias(): int
    {
        return $this->dias;
    }

    /**
     * @param int $dias
     */
    public function setDias(int $dias): void
    {
        $this->dias = $dias;
    }

    /**
     * @return int
     */
    public function getHoras(): int
    {
        return $this->horas;
    }

    /**
     * @param int $horas
     */
    public function setHoras(int $horas): void
    {
        $this->horas = $horas;
    }

    /**
     * @return int
     */
    public function getHorasDiurnas(): int
    {
        return $this->horasDiurnas;
    }

    /**
     * @param int $horasDiurnas
     */
    public function setHorasDiurnas(int $horasDiurnas): void
    {
        $this->horasDiurnas = $horasDiurnas;
    }

    /**
     * @return int
     */
    public function getHorasNocturnas(): int
    {
        return $this->horasNocturnas;
    }

    /**
     * @param int $horasNocturnas
     */
    public function setHorasNocturnas(int $horasNocturnas): void
    {
        $this->horasNocturnas = $horasNocturnas;
    }

    /**
     * @return int
     */
    public function getHorasProgramadas(): int
    {
        return $this->horasProgramadas;
    }

    /**
     * @param int $horasProgramadas
     */
    public function setHorasProgramadas(int $horasProgramadas): void
    {
        $this->horasProgramadas = $horasProgramadas;
    }

    /**
     * @return int
     */
    public function getHorasDiurnasProgramadas(): int
    {
        return $this->horasDiurnasProgramadas;
    }

    /**
     * @param int $horasDiurnasProgramadas
     */
    public function setHorasDiurnasProgramadas(int $horasDiurnasProgramadas): void
    {
        $this->horasDiurnasProgramadas = $horasDiurnasProgramadas;
    }

    /**
     * @return int
     */
    public function getHorasNocturnasProgramadas(): int
    {
        return $this->horasNocturnasProgramadas;
    }

    /**
     * @param int $horasNocturnasProgramadas
     */
    public function setHorasNocturnasProgramadas(int $horasNocturnasProgramadas): void
    {
        $this->horasNocturnasProgramadas = $horasNocturnasProgramadas;
    }

    /**
     * @return int
     */
    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     */
    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return int
     */
    public function getVrCosto(): int
    {
        return $this->vrCosto;
    }

    /**
     * @param int $vrCosto
     */
    public function setVrCosto(int $vrCosto): void
    {
        $this->vrCosto = $vrCosto;
    }

    /**
     * @return int
     */
    public function getVrTotal(): int
    {
        return $this->vrTotal;
    }

    /**
     * @param int $vrTotal
     */
    public function setVrTotal(int $vrTotal): void
    {
        $this->vrTotal = $vrTotal;
    }

    /**
     * @return int
     */
    public function getMargen(): int
    {
        return $this->margen;
    }

    /**
     * @param int $margen
     */
    public function setMargen(int $margen): void
    {
        $this->margen = $margen;
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


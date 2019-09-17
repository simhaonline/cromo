<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteIntermediacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteIntermediacion
{
    public $infoLog = [
        "primaryKey" => "codigoIntermediacionPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoIntermediacionPk;

    /**
     * @ORM\Column(name="numero", type="float", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */
    private $anio;

    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */
    private $mes;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="vr_flete_pago", type="float", options={"default" : 0})
     */
    private $vrFletePago = 0;

    /**
     * @ORM\Column(name="vr_flete_pago_recogida", type="float", options={"default" : 0})
     */
    private $vrFletePagoRecogida = 0;

    /**
     * @ORM\Column(name="vr_flete_pago_total", type="float", options={"default" : 0})
     */
    private $vrFletePagoTotal = 0;

    /**
     * @ORM\Column(name="vr_flete_cobro", type="float", options={"default" : 0})
     */
    private $vrFleteCobro = 0;

    /**
     * @ORM\Column(name="vr_flete_cobro_total", type="float", options={"default" : 0})
     */
    private $vrFleteCobroTotal = 0;

    /**
     * @ORM\Column(name="vr_ingreso", type="float", options={"default" : 0})
     */
    private $vrIngreso = 0;

    /**
     * @ORM\Column(name="vr_ingreso_total", type="float", options={"default" : 0})
     */
    private $vrIngresoTotal = 0;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteIntermediacionVenta", mappedBy="intermediacionRel")
     */
    protected $intermediacionesVentasIntermediacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteIntermediacionCompra", mappedBy="intermediacionRel")
     */
    protected $intermediacionesComprasIntermediacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteIntermediacionRecogida", mappedBy="intermediacionRel")
     */
    protected $intermediacionesRecogidasIntermediacionRel;

    /**
     * @return mixed
     */
    public function getCodigoIntermediacionPk()
    {
        return $this->codigoIntermediacionPk;
    }

    /**
     * @param mixed $codigoIntermediacionPk
     */
    public function setCodigoIntermediacionPk($codigoIntermediacionPk): void
    {
        $this->codigoIntermediacionPk = $codigoIntermediacionPk;
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
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getVrFletePago()
    {
        return $this->vrFletePago;
    }

    /**
     * @param mixed $vrFletePago
     */
    public function setVrFletePago($vrFletePago): void
    {
        $this->vrFletePago = $vrFletePago;
    }

    /**
     * @return mixed
     */
    public function getVrFleteCobro()
    {
        return $this->vrFleteCobro;
    }

    /**
     * @param mixed $vrFleteCobro
     */
    public function setVrFleteCobro($vrFleteCobro): void
    {
        $this->vrFleteCobro = $vrFleteCobro;
    }

    /**
     * @return mixed
     */
    public function getVrIngreso()
    {
        return $this->vrIngreso;
    }

    /**
     * @param mixed $vrIngreso
     */
    public function setVrIngreso($vrIngreso): void
    {
        $this->vrIngreso = $vrIngreso;
    }

    /**
     * @return mixed
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @param mixed $estadoAutorizado
     */
    public function setEstadoAutorizado($estadoAutorizado): void
    {
        $this->estadoAutorizado = $estadoAutorizado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getIntermediacionesVentasIntermediacionRel()
    {
        return $this->intermediacionesVentasIntermediacionRel;
    }

    /**
     * @param mixed $intermediacionesVentasIntermediacionRel
     */
    public function setIntermediacionesVentasIntermediacionRel($intermediacionesVentasIntermediacionRel): void
    {
        $this->intermediacionesVentasIntermediacionRel = $intermediacionesVentasIntermediacionRel;
    }

    /**
     * @return mixed
     */
    public function getIntermediacionesComprasIntermediacionRel()
    {
        return $this->intermediacionesComprasIntermediacionRel;
    }

    /**
     * @param mixed $intermediacionesComprasIntermediacionRel
     */
    public function setIntermediacionesComprasIntermediacionRel($intermediacionesComprasIntermediacionRel): void
    {
        $this->intermediacionesComprasIntermediacionRel = $intermediacionesComprasIntermediacionRel;
    }

    /**
     * @return mixed
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param mixed $estadoAnulado
     */
    public function setEstadoAnulado($estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
    }

    /**
     * @return mixed
     */
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * @param mixed $estadoContabilizado
     */
    public function setEstadoContabilizado($estadoContabilizado): void
    {
        $this->estadoContabilizado = $estadoContabilizado;
    }

    /**
     * @return mixed
     */
    public function getVrFletePagoRecogida()
    {
        return $this->vrFletePagoRecogida;
    }

    /**
     * @param mixed $vrFletePagoRecogida
     */
    public function setVrFletePagoRecogida($vrFletePagoRecogida): void
    {
        $this->vrFletePagoRecogida = $vrFletePagoRecogida;
    }

    /**
     * @return mixed
     */
    public function getVrFletePagoTotal()
    {
        return $this->vrFletePagoTotal;
    }

    /**
     * @param mixed $vrFletePagoTotal
     */
    public function setVrFletePagoTotal($vrFletePagoTotal): void
    {
        $this->vrFletePagoTotal = $vrFletePagoTotal;
    }

    /**
     * @return mixed
     */
    public function getIntermediacionesRecogidasIntermediacionRel()
    {
        return $this->intermediacionesRecogidasIntermediacionRel;
    }

    /**
     * @param mixed $intermediacionesRecogidasIntermediacionRel
     */
    public function setIntermediacionesRecogidasIntermediacionRel($intermediacionesRecogidasIntermediacionRel): void
    {
        $this->intermediacionesRecogidasIntermediacionRel = $intermediacionesRecogidasIntermediacionRel;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
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
    public function getVrFleteCobroTotal()
    {
        return $this->vrFleteCobroTotal;
    }

    /**
     * @param mixed $vrFleteCobroTotal
     */
    public function setVrFleteCobroTotal($vrFleteCobroTotal): void
    {
        $this->vrFleteCobroTotal = $vrFleteCobroTotal;
    }

    /**
     * @return mixed
     */
    public function getVrIngresoTotal()
    {
        return $this->vrIngresoTotal;
    }

    /**
     * @param mixed $vrIngresoTotal
     */
    public function setVrIngresoTotal($vrIngresoTotal): void
    {
        $this->vrIngresoTotal = $vrIngresoTotal;
    }




}


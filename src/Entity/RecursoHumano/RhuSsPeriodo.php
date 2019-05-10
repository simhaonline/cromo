<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSsPeriodoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSsPeriodo
{
    public $infoLog = [
        "primaryKey" => "codigoSsPeriodoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_ss_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSsPeriodoPk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="anio_pago", type="integer")
     */
    private $anioPago = 0;

    /**
     * @ORM\Column(name="mes_pago", type="integer")
     */
    private $mesPago = 0;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */
    private $fechaPago;

    /**
     * @ORM\Column(name="estado_generado", type="boolean")
     */
    private $estadoGenerado = 0;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = 0;

    /**
     * @return mixed
     */
    public function getCodigoSsPeriodoPk()
    {
        return $this->codigoSsPeriodoPk;
    }

    /**
     * @param mixed $codigoSsPeriodoPk
     */
    public function setCodigoSsPeriodoPk($codigoSsPeriodoPk): void
    {
        $this->codigoSsPeriodoPk = $codigoSsPeriodoPk;
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
    public function getAnioPago()
    {
        return $this->anioPago;
    }

    /**
     * @param mixed $anioPago
     */
    public function setAnioPago($anioPago): void
    {
        $this->anioPago = $anioPago;
    }

    /**
     * @return mixed
     */
    public function getMesPago()
    {
        return $this->mesPago;
    }

    /**
     * @param mixed $mesPago
     */
    public function setMesPago($mesPago): void
    {
        $this->mesPago = $mesPago;
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
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * @param mixed $estadoGenerado
     */
    public function setEstadoGenerado($estadoGenerado): void
    {
        $this->estadoGenerado = $estadoGenerado;
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
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * @param mixed $fechaPago
     */
    public function setFechaPago($fechaPago): void
    {
        $this->fechaPago = $fechaPago;
    }





//    /**
//     * @ORM\OneToMany(targetEntity="RhuSsoPeriodoDetalle", mappedBy="ssoPeriodoRel")
//     */
//    protected $ssoPeriodosDetallesSsoPeriodoRel;
//
//    /**
//     * @ORM\OneToMany(targetEntity="RhuSsoPeriodoEmpleado", mappedBy="ssoPeriodoRel")
//     */
//    protected $ssoPeriodosEmpleadosSsoPeriodoRel;
//
//    /**
//     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="ssoPeriodoRel")
//     */
//    protected $ssoAportesSsoPeriodoRel;




}

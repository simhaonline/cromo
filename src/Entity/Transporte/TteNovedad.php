<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteNovedadRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteNovedad
{
    public $infoLog = [
        "primaryKey" => "codigoNovedadPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoNovedadPk;

    /**
     * @ORM\Column(name="codigo_guia_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoNovedadTipoFk;

    /**
     * @ORM\Column(name="descripcion", type="string", length=2000, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="solucion", type="string", length=2000, nullable=true)
     */
    private $solucion;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=true)
     */
    private $fechaRegistro;

    /**
     * @ORM\Column(name="fecha_reporte", type="datetime", nullable=true)
     */
    private $fechaReporte;

    /**
     * @ORM\Column(name="fecha_atencion", type="datetime", nullable=true)
     */
    private $fechaAtencion;

    /**
     * @ORM\Column(name="fecha_solucion", type="datetime", nullable=true)
     */
    private $fechaSolucion;

    /**
     * @ORM\Column(name="estado_reporte", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoReporte = false;

    /**
     * @ORM\Column(name="estado_atendido", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoAtendido = false;

    /**
     * @ORM\Column(name="estado_solucion", type="boolean", nullable=true, options={"default" : false})
     */
    private $estadoSolucion = false;

    /**
     * @ORM\Column(name="aplica_guia", type="boolean", nullable=true, options={"default" : false})
     */
    private $aplicaGuia = false;

    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */
    private $codigoGuiaFk;

    /**
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */
    private $codigoDespachoFk;

    /**
     * @ORM\Column(name="codigo_despacho_referencia_fk", type="integer", nullable=true)
     */
    private $codigoDespachoReferenciaFk;

    /**
     * @ORM\ManyToOne(targetEntity="TteNovedadTipo", inversedBy="novedadesNovedadTipoRel")
     * @ORM\JoinColumn(name="codigo_novedad_tipo_fk", referencedColumnName="codigo_novedad_tipo_pk")
     */
    private $novedadTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteGuia", inversedBy="novedadesGuiaRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    private $guiaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteDespacho", inversedBy="novedadesDespachoRel")
     * @ORM\JoinColumn(name="codigo_despacho_fk", referencedColumnName="codigo_despacho_pk")
     */
    private $despachoRel;

    /**
     * @return mixed
     */
    public function getCodigoNovedadPk()
    {
        return $this->codigoNovedadPk;
    }

    /**
     * @param mixed $codigoNovedadPk
     */
    public function setCodigoNovedadPk($codigoNovedadPk): void
    {
        $this->codigoNovedadPk = $codigoNovedadPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoNovedadTipoFk()
    {
        return $this->codigoNovedadTipoFk;
    }

    /**
     * @param mixed $codigoNovedadTipoFk
     */
    public function setCodigoNovedadTipoFk($codigoNovedadTipoFk): void
    {
        $this->codigoNovedadTipoFk = $codigoNovedadTipoFk;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getSolucion()
    {
        return $this->solucion;
    }

    /**
     * @param mixed $solucion
     */
    public function setSolucion($solucion): void
    {
        $this->solucion = $solucion;
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
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * @param mixed $fechaRegistro
     */
    public function setFechaRegistro($fechaRegistro): void
    {
        $this->fechaRegistro = $fechaRegistro;
    }

    /**
     * @return mixed
     */
    public function getFechaReporte()
    {
        return $this->fechaReporte;
    }

    /**
     * @param mixed $fechaReporte
     */
    public function setFechaReporte($fechaReporte): void
    {
        $this->fechaReporte = $fechaReporte;
    }

    /**
     * @return mixed
     */
    public function getFechaAtencion()
    {
        return $this->fechaAtencion;
    }

    /**
     * @param mixed $fechaAtencion
     */
    public function setFechaAtencion($fechaAtencion): void
    {
        $this->fechaAtencion = $fechaAtencion;
    }

    /**
     * @return mixed
     */
    public function getFechaSolucion()
    {
        return $this->fechaSolucion;
    }

    /**
     * @param mixed $fechaSolucion
     */
    public function setFechaSolucion($fechaSolucion): void
    {
        $this->fechaSolucion = $fechaSolucion;
    }

    /**
     * @return mixed
     */
    public function getEstadoReporte()
    {
        return $this->estadoReporte;
    }

    /**
     * @param mixed $estadoReporte
     */
    public function setEstadoReporte($estadoReporte): void
    {
        $this->estadoReporte = $estadoReporte;
    }

    /**
     * @return mixed
     */
    public function getEstadoAtendido()
    {
        return $this->estadoAtendido;
    }

    /**
     * @param mixed $estadoAtendido
     */
    public function setEstadoAtendido($estadoAtendido): void
    {
        $this->estadoAtendido = $estadoAtendido;
    }

    /**
     * @return mixed
     */
    public function getEstadoSolucion()
    {
        return $this->estadoSolucion;
    }

    /**
     * @param mixed $estadoSolucion
     */
    public function setEstadoSolucion($estadoSolucion): void
    {
        $this->estadoSolucion = $estadoSolucion;
    }

    /**
     * @return mixed
     */
    public function getAplicaGuia()
    {
        return $this->aplicaGuia;
    }

    /**
     * @param mixed $aplicaGuia
     */
    public function setAplicaGuia($aplicaGuia): void
    {
        $this->aplicaGuia = $aplicaGuia;
    }

    /**
     * @return mixed
     */
    public function getCodigoGuiaFk()
    {
        return $this->codigoGuiaFk;
    }

    /**
     * @param mixed $codigoGuiaFk
     */
    public function setCodigoGuiaFk($codigoGuiaFk): void
    {
        $this->codigoGuiaFk = $codigoGuiaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoFk()
    {
        return $this->codigoDespachoFk;
    }

    /**
     * @param mixed $codigoDespachoFk
     */
    public function setCodigoDespachoFk($codigoDespachoFk): void
    {
        $this->codigoDespachoFk = $codigoDespachoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoReferenciaFk()
    {
        return $this->codigoDespachoReferenciaFk;
    }

    /**
     * @param mixed $codigoDespachoReferenciaFk
     */
    public function setCodigoDespachoReferenciaFk($codigoDespachoReferenciaFk): void
    {
        $this->codigoDespachoReferenciaFk = $codigoDespachoReferenciaFk;
    }

    /**
     * @return mixed
     */
    public function getNovedadTipoRel()
    {
        return $this->novedadTipoRel;
    }

    /**
     * @param mixed $novedadTipoRel
     */
    public function setNovedadTipoRel($novedadTipoRel): void
    {
        $this->novedadTipoRel = $novedadTipoRel;
    }

    /**
     * @return mixed
     */
    public function getGuiaRel()
    {
        return $this->guiaRel;
    }

    /**
     * @param mixed $guiaRel
     */
    public function setGuiaRel($guiaRel): void
    {
        $this->guiaRel = $guiaRel;
    }

    /**
     * @return mixed
     */
    public function getDespachoRel()
    {
        return $this->despachoRel;
    }

    /**
     * @param mixed $despachoRel
     */
    public function setDespachoRel($despachoRel): void
    {
        $this->despachoRel = $despachoRel;
    }




}


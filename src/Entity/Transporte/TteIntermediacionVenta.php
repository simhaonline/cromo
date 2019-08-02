<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteIntermediacionVentaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteIntermediacionVenta
{
    public $infoLog = [
        "primaryKey" => "codigoIntermediacionVentaPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoIntermediacionVentaPk;

    /**
     * @ORM\Column(name="codigo_intermediacion_fk", type="integer", nullable=true)
     */
    private $codigoIntermediacionFk;

    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */
    private $anio;

    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */
    private $mes;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_factura_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoFacturaTipoFk;

    /**
     * @ORM\Column(name="vr_flete", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_flete_participacion", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrFleteParticipacion = 0;

    /**
     * @ORM\Column(name="vr_flete_ingreso", type="float", options={"default" : 0}, nullable=true)
     */
    private $vrFleteIngreso = 0;

    /**
     * @ORM\Column(name="porcentaje_participacion", type="float", options={"default" : 0}, nullable=true)
     */
    private $porcentajeParticipacion = 0;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteIntermediacion", inversedBy="intermediacionesVentasIntermediacionRel")
     * @ORM\JoinColumn(name="codigo_intermediacion_fk", referencedColumnName="codigo_intermediacion_pk")
     */
    private $intermediacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteFacturaTipo", inversedBy="intermediacionesVentasFacturaTipoRel")
     * @ORM\JoinColumn(name="codigo_factura_tipo_fk", referencedColumnName="codigo_factura_tipo_pk")
     */
    private $facturaTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="intermediacionesVentasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @return mixed
     */
    public function getCodigoIntermediacionVentaPk()
    {
        return $this->codigoIntermediacionVentaPk;
    }

    /**
     * @param mixed $codigoIntermediacionVentaPk
     */
    public function setCodigoIntermediacionVentaPk($codigoIntermediacionVentaPk): void
    {
        $this->codigoIntermediacionVentaPk = $codigoIntermediacionVentaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoIntermediacionFk()
    {
        return $this->codigoIntermediacionFk;
    }

    /**
     * @param mixed $codigoIntermediacionFk
     */
    public function setCodigoIntermediacionFk($codigoIntermediacionFk): void
    {
        $this->codigoIntermediacionFk = $codigoIntermediacionFk;
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
    public function getCodigoFacturaTipoFk()
    {
        return $this->codigoFacturaTipoFk;
    }

    /**
     * @param mixed $codigoFacturaTipoFk
     */
    public function setCodigoFacturaTipoFk($codigoFacturaTipoFk): void
    {
        $this->codigoFacturaTipoFk = $codigoFacturaTipoFk;
    }

    /**
     * @return mixed
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * @param mixed $vrFlete
     */
    public function setVrFlete($vrFlete): void
    {
        $this->vrFlete = $vrFlete;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeParticipacion()
    {
        return $this->porcentajeParticipacion;
    }

    /**
     * @param mixed $porcentajeParticipacion
     */
    public function setPorcentajeParticipacion($porcentajeParticipacion): void
    {
        $this->porcentajeParticipacion = $porcentajeParticipacion;
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
    public function getIntermediacionRel()
    {
        return $this->intermediacionRel;
    }

    /**
     * @param mixed $intermediacionRel
     */
    public function setIntermediacionRel($intermediacionRel): void
    {
        $this->intermediacionRel = $intermediacionRel;
    }

    /**
     * @return mixed
     */
    public function getFacturaTipoRel()
    {
        return $this->facturaTipoRel;
    }

    /**
     * @param mixed $facturaTipoRel
     */
    public function setFacturaTipoRel($facturaTipoRel): void
    {
        $this->facturaTipoRel = $facturaTipoRel;
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
    public function getVrFleteParticipacion()
    {
        return $this->vrFleteParticipacion;
    }

    /**
     * @param mixed $vrFleteParticipacion
     */
    public function setVrFleteParticipacion($vrFleteParticipacion): void
    {
        $this->vrFleteParticipacion = $vrFleteParticipacion;
    }

    /**
     * @return mixed
     */
    public function getVrFleteIngreso()
    {
        return $this->vrFleteIngreso;
    }

    /**
     * @param mixed $vrFleteIngreso
     */
    public function setVrFleteIngreso($vrFleteIngreso): void
    {
        $this->vrFleteIngreso = $vrFleteIngreso;
    }




}


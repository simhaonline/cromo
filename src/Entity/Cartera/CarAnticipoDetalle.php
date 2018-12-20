<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarAnticipoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarAnticipoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoAnticipoDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_anticipo_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoAnticipoDetallePk;

    /**
     * @ORM\Column(name="codigo_anticipo_fk", type="integer", nullable=true)
     */
    private $codigoAnticipoFk;

    /**
     * @ORM\Column(name="codigo_anticipo_concepto_fk", type="string", length=10,  nullable=true)
     */
    private $codigoAnticipoConceptoFk;

    /**
     * @ORM\Column(name="vr_pago", type="float", nullable=true)
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\ManyToOne(targetEntity="CarAnticipo", inversedBy="anticiposDetallesRel")
     * @ORM\JoinColumn(name="codigo_anticipo_fk", referencedColumnName="codigo_anticipo_pk")
     */
    protected $anticipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarAnticipoConcepto", inversedBy="anticiposDetallesConceptosRel")
     * @ORM\JoinColumn(name="codigo_anticipo_concepto_fk", referencedColumnName="codigo_anticipo_concepto_pk")
     */
    protected $anticipoConceptoRel;

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
    public function getCodigoAnticipoDetallePk()
    {
        return $this->codigoAnticipoDetallePk;
    }

    /**
     * @param mixed $codigoAnticipoDetallePk
     */
    public function setCodigoAnticipoDetallePk($codigoAnticipoDetallePk): void
    {
        $this->codigoAnticipoDetallePk = $codigoAnticipoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAnticipoFk()
    {
        return $this->codigoAnticipoFk;
    }

    /**
     * @param mixed $codigoAnticipoFk
     */
    public function setCodigoAnticipoFk($codigoAnticipoFk): void
    {
        $this->codigoAnticipoFk = $codigoAnticipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAnticipoConceptoFk()
    {
        return $this->codigoAnticipoConceptoFk;
    }

    /**
     * @param mixed $codigoAnticipoConceptoFk
     */
    public function setCodigoAnticipoConceptoFk($codigoAnticipoConceptoFk): void
    {
        $this->codigoAnticipoConceptoFk = $codigoAnticipoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * @param mixed $vrPago
     */
    public function setVrPago($vrPago): void
    {
        $this->vrPago = $vrPago;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * @param mixed $operacion
     */
    public function setOperacion($operacion): void
    {
        $this->operacion = $operacion;
    }

    /**
     * @return mixed
     */
    public function getAnticipoRel()
    {
        return $this->anticipoRel;
    }

    /**
     * @param mixed $anticipoRel
     */
    public function setAnticipoRel($anticipoRel): void
    {
        $this->anticipoRel = $anticipoRel;
    }

    /**
     * @return mixed
     */
    public function getAnticipoConceptoRel()
    {
        return $this->anticipoConceptoRel;
    }

    /**
     * @param mixed $anticipoConceptoRel
     */
    public function setAnticipoConceptoRel($anticipoConceptoRel): void
    {
        $this->anticipoConceptoRel = $anticipoConceptoRel;
    }



}

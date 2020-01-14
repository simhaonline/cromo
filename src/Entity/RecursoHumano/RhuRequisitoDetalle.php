<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuRequisitoDetalleRepository")
 */
class RhuRequisitoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_requisito_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRequisitoDetallePk;

    /**
     * @ORM\Column(name="codigo_requisito_fk", type="integer", nullable=true)
     */
    private $codigoRequisitoFk;

    /**
     * @ORM\Column(name="codigo_requisito_concepto_fk", type="integer", nullable=true)
     */
    private $codigoRequisitoConceptoFk;

    /**
     * @ORM\Column(name="estado_entregado", type="boolean")
     */
    private $estadoEntregado = 0;

    /**
     * @ORM\Column(name="estado_no_aplica", type="boolean")
     */
    private $estadoNoAplica = 0;

    /**
     * @ORM\Column(name="tipo", type="string", length=20, nullable=true)
     */
    private $tipo;

    /**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="cantidad_entregada", type="integer")
     */
    private $cantidadEntregada = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuRequisito", inversedBy="requisitosDetallesRequisitoRel")
     * @ORM\JoinColumn(name="codigo_requisito_fk", referencedColumnName="codigo_requisito_pk")
     */
    protected $requisitoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuRequisitoConcepto", inversedBy="requisitosDetallesRequisitoConceptoRel")
     * @ORM\JoinColumn(name="codigo_requisito_concepto_fk", referencedColumnName="codigo_requisito_concepto_pk")
     */
    protected $requisitoConceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoRequisitoDetallePk()
    {
        return $this->codigoRequisitoDetallePk;
    }

    /**
     * @param mixed $codigoRequisitoDetallePk
     */
    public function setCodigoRequisitoDetallePk($codigoRequisitoDetallePk): void
    {
        $this->codigoRequisitoDetallePk = $codigoRequisitoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoRequisitoFk()
    {
        return $this->codigoRequisitoFk;
    }

    /**
     * @param mixed $codigoRequisitoFk
     */
    public function setCodigoRequisitoFk($codigoRequisitoFk): void
    {
        $this->codigoRequisitoFk = $codigoRequisitoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoRequisitoConceptoFk()
    {
        return $this->codigoRequisitoConceptoFk;
    }

    /**
     * @param mixed $codigoRequisitoConceptoFk
     */
    public function setCodigoRequisitoConceptoFk($codigoRequisitoConceptoFk): void
    {
        $this->codigoRequisitoConceptoFk = $codigoRequisitoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getEstadoEntregado()
    {
        return $this->estadoEntregado;
    }

    /**
     * @param mixed $estadoEntregado
     */
    public function setEstadoEntregado($estadoEntregado): void
    {
        $this->estadoEntregado = $estadoEntregado;
    }

    /**
     * @return mixed
     */
    public function getEstadoNoAplica()
    {
        return $this->estadoNoAplica;
    }

    /**
     * @param mixed $estadoNoAplica
     */
    public function setEstadoNoAplica($estadoNoAplica): void
    {
        $this->estadoNoAplica = $estadoNoAplica;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
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
    public function getCantidadEntregada()
    {
        return $this->cantidadEntregada;
    }

    /**
     * @param mixed $cantidadEntregada
     */
    public function setCantidadEntregada($cantidadEntregada): void
    {
        $this->cantidadEntregada = $cantidadEntregada;
    }

    /**
     * @return mixed
     */
    public function getRequisitoRel()
    {
        return $this->requisitoRel;
    }

    /**
     * @param mixed $requisitoRel
     */
    public function setRequisitoRel($requisitoRel): void
    {
        $this->requisitoRel = $requisitoRel;
    }

    /**
     * @return mixed
     */
    public function getRequisitoConceptoRel()
    {
        return $this->requisitoConceptoRel;
    }

    /**
     * @param mixed $requisitoConceptoRel
     */
    public function setRequisitoConceptoRel($requisitoConceptoRel): void
    {
        $this->requisitoConceptoRel = $requisitoConceptoRel;
    }


}

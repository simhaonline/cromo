<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoAdicionalRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteDespachoAdicional
{
    public $infoLog = [
        "primaryKey" => "codigoDespachoAdicionalPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoDespachoAdicionalPk;

    /**
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */
    private $codigoDespachoFk;

    /**
     * @ORM\Column(name="codigo_despacho_adicional_concepto_fk", type="integer", nullable=true)
     */
    private $codigoDespachoAdicionalConceptoFk;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="vr_valor", type="float")
     */
    private $vrValor = 0;

    /**
     * @ORM\Column(name="vr_valor_operado", type="float")
     */
    private $vrValorOperado = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteDespacho", inversedBy="despachosAdicionalesDespachoRel")
     * @ORM\JoinColumn(name="codigo_despacho_fk", referencedColumnName="codigo_despacho_pk")
     */
    private $despachoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteDespachoAdicionalConcepto", inversedBy="despachosAdicionalesDespachoAdicionalConceptoRel")
     * @ORM\JoinColumn(name="codigo_despacho_adicional_concepto_fk", referencedColumnName="codigo_despacho_adicional_concepto_pk")
     */
    private $despachoAdicionalConceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoDespachoAdicionalPk()
    {
        return $this->codigoDespachoAdicionalPk;
    }

    /**
     * @param mixed $codigoDespachoAdicionalPk
     */
    public function setCodigoDespachoAdicionalPk($codigoDespachoAdicionalPk): void
    {
        $this->codigoDespachoAdicionalPk = $codigoDespachoAdicionalPk;
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
    public function getCodigoDespachoAdicionalConceptoFk()
    {
        return $this->codigoDespachoAdicionalConceptoFk;
    }

    /**
     * @param mixed $codigoDespachoAdicionalConceptoFk
     */
    public function setCodigoDespachoAdicionalConceptoFk($codigoDespachoAdicionalConceptoFk): void
    {
        $this->codigoDespachoAdicionalConceptoFk = $codigoDespachoAdicionalConceptoFk;
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
    public function getVrValor()
    {
        return $this->vrValor;
    }

    /**
     * @param mixed $vrValor
     */
    public function setVrValor($vrValor): void
    {
        $this->vrValor = $vrValor;
    }

    /**
     * @return mixed
     */
    public function getVrValorOperado()
    {
        return $this->vrValorOperado;
    }

    /**
     * @param mixed $vrValorOperado
     */
    public function setVrValorOperado($vrValorOperado): void
    {
        $this->vrValorOperado = $vrValorOperado;
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

    /**
     * @return mixed
     */
    public function getDespachoAdicionalConceptoRel()
    {
        return $this->despachoAdicionalConceptoRel;
    }

    /**
     * @param mixed $despachoAdicionalConceptoRel
     */
    public function setDespachoAdicionalConceptoRel($despachoAdicionalConceptoRel): void
    {
        $this->despachoAdicionalConceptoRel = $despachoAdicionalConceptoRel;
    }



}


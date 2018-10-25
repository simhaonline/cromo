<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCreditoPagoRepository")
 */
class RhuCreditoPago {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credito_pago_pk", type="string", length=10)
     */
    private $codigoCreditoPagoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="creditoPagoRel")
     */
    protected $creditosCreditoPagoRel;

    /**
     * @return mixed
     */
    public function getCodigoCreditoPagoPk()
    {
        return $this->codigoCreditoPagoPk;
    }

    /**
     * @param mixed $codigoCreditoPagoPk
     */
    public function setCodigoCreditoPagoPk($codigoCreditoPagoPk): void
    {
        $this->codigoCreditoPagoPk = $codigoCreditoPagoPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getCreditosCreditoPagoRel()
    {
        return $this->creditosCreditoPagoRel;
    }

    /**
     * @param mixed $creditosCreditoPagoRel
     */
    public function setCreditosCreditoPagoRel($creditosCreditoPagoRel): void
    {
        $this->creditosCreditoPagoRel = $creditosCreditoPagoRel;
    }
}

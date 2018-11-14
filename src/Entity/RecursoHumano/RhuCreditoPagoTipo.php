<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCreditoPagoTipoRepository")
 */
class RhuCreditoPagoTipo {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credito_pago_tipo_pk", type="string", length=10)
     */
    private $codigoCreditoPagoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="creditoPagoTipoRel")
     */
    protected $creditosCreditoPagoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoCreditoPagoTipoPk()
    {
        return $this->codigoCreditoPagoTipoPk;
    }

    /**
     * @param mixed $codigoCreditoPagoTipoPk
     */
    public function setCodigoCreditoPagoTipoPk( $codigoCreditoPagoTipoPk ): void
    {
        $this->codigoCreditoPagoTipoPk = $codigoCreditoPagoTipoPk;
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
    public function setNombre( $nombre ): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getCreditosCreditoPagoTipoRel()
    {
        return $this->creditosCreditoPagoTipoRel;
    }

    /**
     * @param mixed $creditosCreditoPagoTipoRel
     */
    public function setCreditosCreditoPagoTipoRel( $creditosCreditoPagoTipoRel ): void
    {
        $this->creditosCreditoPagoTipoRel = $creditosCreditoPagoTipoRel;
    }



}

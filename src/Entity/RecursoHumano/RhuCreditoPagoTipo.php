<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCreditoPagoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuCreditoPagoTipo {

    public $infoLog = [
        "primaryKey" => "codigoCreditoPagoTipoPk",
        "todos"     => true,
    ];
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
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden = 0;

    /**
     * @ORM\OneToMany(targetEntity="RhuCredito", mappedBy="creditoPagoTipoRel")
     */
    protected $creditosCreditoPagoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuCreditoPago", mappedBy="creditoPagoTipoRel")
     */
    protected $creditosPagosCreditoPagoTipoRel;

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

    /**
     * @return mixed
     */
    public function getCreditosPagosCreditoPagoTipoRel()
    {
        return $this->creditosPagosCreditoPagoTipoRel;
    }

    /**
     * @param mixed $creditosPagosCreditoPagoTipoRel
     */
    public function setCreditosPagosCreditoPagoTipoRel( $creditosPagosCreditoPagoTipoRel ): void
    {
        $this->creditosPagosCreditoPagoTipoRel = $creditosPagosCreditoPagoTipoRel;
    }

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
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     */
    public function setOrden($orden): void
    {
        $this->orden = $orden;
    }



}

<?php


namespace App\Entity\Turno;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurClienteIcaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurClienteIca
{
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_cliente_ica_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoClienteIcaPk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="integer", nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="codigo_servicio_erp", type="string", length=20, nullable=true)
     */
    private $codigoServicioErp;

    /**
     * @ORM\Column(name="codigo_dane", type="string", length=5)
    private $codigoDane;

    /**
     * @ORM\Column(name="tar_ica", type="float", nullable=true)
     */
    private $tarIca = 0;

    /**
     * @ORM\Column(name="por_ica", type="float", nullable=true)
     */
    private $porIca = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="clientesIcaClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="turClientesIcaCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurConcepto", inversedBy="clientesIcaConceptoRel")
     * @ORM\JoinColumn(name="codigo_concepto_fk", referencedColumnName="codigo_concepto_servicio_pk")
     */
    protected $conceptoRel;

    /**
     * @return int
     */
    public function getCodigoClienteIcaPk(): int
    {
        return $this->codigoClienteIcaPk;
    }

    /**
     * @param int $codigoClienteIcaPk
     */
    public function setCodigoClienteIcaPk(int $codigoClienteIcaPk): void
    {
        $this->codigoClienteIcaPk = $codigoClienteIcaPk;
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
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * @param mixed $codigoCiudadFk
     */
    public function setCodigoCiudadFk($codigoCiudadFk): void
    {
        $this->codigoCiudadFk = $codigoCiudadFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoConceptoFk()
    {
        return $this->codigoConceptoFk;
    }

    /**
     * @param mixed $codigoConceptoFk
     */
    public function setCodigoConceptoFk($codigoConceptoFk): void
    {
        $this->codigoConceptoFk = $codigoConceptoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoServicioErp()
    {
        return $this->codigoServicioErp;
    }

    /**
     * @param mixed $codigoServicioErp
     */
    public function setCodigoServicioErp($codigoServicioErp): void
    {
        $this->codigoServicioErp = $codigoServicioErp;
    }

    /**
     * @return mixed
     */
    public function getCodigoDane()
    {
        return $this->codigoDane;
    }

    /**
     * @param mixed $codigoDane
     */
    public function setCodigoDane($codigoDane): void
    {
        $this->codigoDane = $codigoDane;
    }

    /**
     * @return int
     */
    public function getTarIca(): int
    {
        return $this->tarIca;
    }

    /**
     * @param int $tarIca
     */
    public function setTarIca(float $tarIca): void
    {
        $this->tarIca = $tarIca;
    }


    public function getPorIca()
    {
        return $this->porIca;
    }

    /**
     * @param int $porIca
     */
    public function setPorIca(float $porIca): void
    {
        $this->porIca = $porIca;
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
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * @param mixed $ciudadRel
     */
    public function setCiudadRel($ciudadRel): void
    {
        $this->ciudadRel = $ciudadRel;
    }

    /**
     * @return mixed
     */
    public function getConceptoRel()
    {
        return $this->conceptoRel;
    }

    /**
     * @param mixed $conceptoRel
     */
    public function setConceptoRel($conceptoRel): void
    {
        $this->conceptoRel = $conceptoRel;
    }


}
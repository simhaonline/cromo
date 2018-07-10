<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteClienteCondicionRepository")
 */
class TteClienteCondicion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoClienteCondicionPk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_condicion_fk", type="integer", nullable=true)
     */
    private $codigoCondicionFk;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="clientesCondicionesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCondicion", inversedBy="clientesCondicionesCondicionRel")
     * @ORM\JoinColumn(name="codigo_condicion_fk", referencedColumnName="codigo_condicion_pk")
     */
    private $condicionRel;

    /**
     * @return mixed
     */
    public function getCodigoClienteCondicionPk()
    {
        return $this->codigoClienteCondicionPk;
    }

    /**
     * @param mixed $codigoClienteCondicionPk
     */
    public function setCodigoClienteCondicionPk($codigoClienteCondicionPk): void
    {
        $this->codigoClienteCondicionPk = $codigoClienteCondicionPk;
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
    public function getCodigoCondicionFk()
    {
        return $this->codigoCondicionFk;
    }

    /**
     * @param mixed $codigoCondicionFk
     */
    public function setCodigoCondicionFk($codigoCondicionFk): void
    {
        $this->codigoCondicionFk = $codigoCondicionFk;
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
    public function getCondicionRel()
    {
        return $this->condicionRel;
    }

    /**
     * @param mixed $condicionRel
     */
    public function setCondicionRel($condicionRel): void
    {
        $this->condicionRel = $condicionRel;
    }



}


<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenFormaPagoRepository")
 */
class GenFormaPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_forma_pago_pk", type="string", length=10)
     */
    private $codigoFormaPagoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarCliente", mappedBy="formaPagoRel")
     */
    protected $carClientesFormaPagoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCliente", mappedBy="formaPagoRel")
     */
    protected $tteClientesFormaPagoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCliente", mappedBy="formaPagoRel")
     */
    protected $turClientesFormaPagoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvTercero", mappedBy="formaPagoRel")
     */
    protected $invTercerosFormaPagoRel;

    /**
     * @return mixed
     */
    public function getCodigoFormaPagoPk()
    {
        return $this->codigoFormaPagoPk;
    }

    /**
     * @param mixed $codigoFormaPagoPk
     */
    public function setCodigoFormaPagoPk($codigoFormaPagoPk): void
    {
        $this->codigoFormaPagoPk = $codigoFormaPagoPk;
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
    public function getCarClientesFormaPagoRel()
    {
        return $this->carClientesFormaPagoRel;
    }

    /**
     * @param mixed $carClientesFormaPagoRel
     */
    public function setCarClientesFormaPagoRel($carClientesFormaPagoRel): void
    {
        $this->carClientesFormaPagoRel = $carClientesFormaPagoRel;
    }

    /**
     * @return mixed
     */
    public function getTteClientesFormaPagoRel()
    {
        return $this->tteClientesFormaPagoRel;
    }

    /**
     * @param mixed $tteClientesFormaPagoRel
     */
    public function setTteClientesFormaPagoRel($tteClientesFormaPagoRel): void
    {
        $this->tteClientesFormaPagoRel = $tteClientesFormaPagoRel;
    }

    /**
     * @return mixed
     */
    public function getInvTercerosFormaPagoRel()
    {
        return $this->invTercerosFormaPagoRel;
    }

    /**
     * @param mixed $invTercerosFormaPagoRel
     */
    public function setInvTercerosFormaPagoRel($invTercerosFormaPagoRel): void
    {
        $this->invTercerosFormaPagoRel = $invTercerosFormaPagoRel;
    }



}


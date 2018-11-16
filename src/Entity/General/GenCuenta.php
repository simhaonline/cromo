<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenCuentaRepository")
 */
class GenCuenta
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_pk", type="string", length=10, nullable=false)
     */
    private $codigoCuentaPk;

    /**
     * @ORM\Column(name="codigo_banco_fk", type="string", length=10, nullable=true)
     */
    private $codigoBancoFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="cuenta", type="string", length=20)
     */
    private $cuenta;

    /**
     * @ORM\Column(name="tipo", type="string", length=60)
     */
    private $tipo;

    /**
     * @ORM\Column(name="codigo_cuenta_contable_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaContableFk;

    /**
     * @ORM\ManyToOne(targetEntity="GenBanco", inversedBy="cuentasBancoRel")
     * @ORM\JoinColumn(name="codigo_banco_fk", referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarRecibo", mappedBy="cuentaRel")
     */
    protected $recibosCuentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compra\ComEgreso" , mappedBy="cuentaRel")
     */
    protected $egresosCuentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEgreso" , mappedBy="cuentaRel")
     */
    protected $rhuEgresosCuentaRel;

    /**
     * @return mixed
     */
    public function getCodigoCuentaPk()
    {
        return $this->codigoCuentaPk;
    }

    /**
     * @param mixed $codigoCuentaPk
     */
    public function setCodigoCuentaPk($codigoCuentaPk): void
    {
        $this->codigoCuentaPk = $codigoCuentaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
    }

    /**
     * @param mixed $codigoBancoFk
     */
    public function setCodigoBancoFk($codigoBancoFk): void
    {
        $this->codigoBancoFk = $codigoBancoFk;
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
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * @param mixed $cuenta
     */
    public function setCuenta($cuenta): void
    {
        $this->cuenta = $cuenta;
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
    public function getCodigoCuentaContableFk()
    {
        return $this->codigoCuentaContableFk;
    }

    /**
     * @param mixed $codigoCuentaContableFk
     */
    public function setCodigoCuentaContableFk($codigoCuentaContableFk): void
    {
        $this->codigoCuentaContableFk = $codigoCuentaContableFk;
    }

    /**
     * @return mixed
     */
    public function getBancoRel()
    {
        return $this->bancoRel;
    }

    /**
     * @param mixed $bancoRel
     */
    public function setBancoRel($bancoRel): void
    {
        $this->bancoRel = $bancoRel;
    }

    /**
     * @return mixed
     */
    public function getRecibosCuentaRel()
    {
        return $this->recibosCuentaRel;
    }

    /**
     * @param mixed $recibosCuentaRel
     */
    public function setRecibosCuentaRel($recibosCuentaRel): void
    {
        $this->recibosCuentaRel = $recibosCuentaRel;
    }

    /**
     * @return mixed
     */
    public function getEgresosCuentaRel()
    {
        return $this->egresosCuentaRel;
    }

    /**
     * @param mixed $egresosCuentaRel
     */
    public function setEgresosCuentaRel($egresosCuentaRel): void
    {
        $this->egresosCuentaRel = $egresosCuentaRel;
    }

    /**
     * @return mixed
     */
    public function getRhuEgresosCuentaRel()
    {
        return $this->rhuEgresosCuentaRel;
    }

    /**
     * @param mixed $rhuEgresosCuentaRel
     */
    public function setRhuEgresosCuentaRel($rhuEgresosCuentaRel): void
    {
        $this->rhuEgresosCuentaRel = $rhuEgresosCuentaRel;
    }
}


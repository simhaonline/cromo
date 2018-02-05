<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClienteRepository")
 */
class Cliente
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoClientePk;

    /**
     * @ORM\Column(name="nit", type="string", length=20, nullable=true)
     */
    private $nit;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=150, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="nombre1", type="string", length=50, nullable=true)
     */
    private $nombre1;

    /**
     * @ORM\Column(name="nombre2", type="string", length=50, nullable=true)
     */
    private $nombre2;

    /**
     * @ORM\Column(name="apellido1", type="string", length=50, nullable=true)
     */
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=50, nullable=true)
     */
    private $apellido2;

    /**
     * @ORM\Column(name="direccion", type="string", length=200, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\OneToMany(targetEntity="Guia", mappedBy="clienteRel")
     */
    protected $guiasClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="Recogida", mappedBy="clienteRel")
     */
    protected $recogidasClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="Cumplido", mappedBy="clienteRel")
     */
    protected $cumplidosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="Factura", mappedBy="clienteRel")
     */
    protected $facturasClienteRel;

    /**
     * @return mixed
     */
    public function getCodigoClientePk()
    {
        return $this->codigoClientePk;
    }

    /**
     * @param mixed $codigoClientePk
     */
    public function setCodigoClientePk($codigoClientePk): void
    {
        $this->codigoClientePk = $codigoClientePk;
    }

    /**
     * @return mixed
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * @param mixed $nombreCorto
     */
    public function setNombreCorto($nombreCorto): void
    {
        $this->nombreCorto = $nombreCorto;
    }

    /**
     * @return mixed
     */
    public function getGuiasClienteRel()
    {
        return $this->guiasClienteRel;
    }

    /**
     * @param mixed $guiasClienteRel
     */
    public function setGuiasClienteRel($guiasClienteRel): void
    {
        $this->guiasClienteRel = $guiasClienteRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasClienteRel()
    {
        return $this->recogidasClienteRel;
    }

    /**
     * @param mixed $recogidasClienteRel
     */
    public function setRecogidasClienteRel($recogidasClienteRel): void
    {
        $this->recogidasClienteRel = $recogidasClienteRel;
    }

    /**
     * @return mixed
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * @param mixed $nit
     */
    public function setNit($nit): void
    {
        $this->nit = $nit;
    }

    /**
     * @return mixed
     */
    public function getNombre1()
    {
        return $this->nombre1;
    }

    /**
     * @param mixed $nombre1
     */
    public function setNombre1($nombre1): void
    {
        $this->nombre1 = $nombre1;
    }

    /**
     * @return mixed
     */
    public function getNombre2()
    {
        return $this->nombre2;
    }

    /**
     * @param mixed $nombre2
     */
    public function setNombre2($nombre2): void
    {
        $this->nombre2 = $nombre2;
    }

    /**
     * @return mixed
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * @param mixed $apellido1
     */
    public function setApellido1($apellido1): void
    {
        $this->apellido1 = $apellido1;
    }

    /**
     * @return mixed
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * @param mixed $apellido2
     */
    public function setApellido2($apellido2): void
    {
        $this->apellido2 = $apellido2;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getCumplidosClienteRel()
    {
        return $this->cumplidosClienteRel;
    }

    /**
     * @param mixed $cumplidosClienteRel
     */
    public function setCumplidosClienteRel($cumplidosClienteRel): void
    {
        $this->cumplidosClienteRel = $cumplidosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasClienteRel()
    {
        return $this->facturasClienteRel;
    }

    /**
     * @param mixed $facturasClienteRel
     */
    public function setFacturasClienteRel($facturasClienteRel): void
    {
        $this->facturasClienteRel = $facturasClienteRel;
    }



}


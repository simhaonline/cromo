<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteAseguradoraRepository")
 */
class TteAseguradora
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoAseguradoraPk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=2, nullable=true)
     */
    private $digitoVerificacion;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteVehiculo", mappedBy="aseguradoraRel")
     */
    protected $vehiculosAseguradoraRel;

    /**
     * @return mixed
     */
    public function getCodigoAseguradoraPk()
    {
        return $this->codigoAseguradoraPk;
    }

    /**
     * @param mixed $codigoAseguradoraPk
     */
    public function setCodigoAseguradoraPk($codigoAseguradoraPk): void
    {
        $this->codigoAseguradoraPk = $codigoAseguradoraPk;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param mixed $numeroIdentificacion
     */
    public function setNumeroIdentificacion($numeroIdentificacion): void
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
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
    public function getVehiculosAseguradoraRel()
    {
        return $this->vehiculosAseguradoraRel;
    }

    /**
     * @param mixed $vehiculosAseguradoraRel
     */
    public function setVehiculosAseguradoraRel($vehiculosAseguradoraRel): void
    {
        $this->vehiculosAseguradoraRel = $vehiculosAseguradoraRel;
    }

    /**
     * @return mixed
     */
    public function getDigitoVerificacion()
    {
        return $this->digitoVerificacion;
    }

    /**
     * @param mixed $digitoVerificacion
     */
    public function setDigitoVerificacion($digitoVerificacion): void
    {
        $this->digitoVerificacion = $digitoVerificacion;
    }



}


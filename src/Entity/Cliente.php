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

}


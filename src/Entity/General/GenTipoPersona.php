<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenTipoPersonaRepository")
 */
class GenTipoPersona
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=3, nullable=false, unique=true)
     */
    private $codigoTipoPersonaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=3, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCliente", mappedBy="tipoPersonaRel")
     */
    protected $tteClientesTipoPersonaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvTercero", mappedBy="tipoPersonaRel")
     */
    protected $invTercerosTipoPersonaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCliente", mappedBy="tipoPersonaRel")
     */
    protected $turClientesTipoPersonaRel;

    /**
     * @return mixed
     */
    public function getCodigoTipoPersonaPk()
    {
        return $this->codigoTipoPersonaPk;
    }

    /**
     * @param mixed $codigoTipoPersonaPk
     */
    public function setCodigoTipoPersonaPk($codigoTipoPersonaPk): void
    {
        $this->codigoTipoPersonaPk = $codigoTipoPersonaPk;
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
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * @param mixed $codigoInterface
     */
    public function setCodigoInterface($codigoInterface): void
    {
        $this->codigoInterface = $codigoInterface;
    }

    /**
     * @return mixed
     */
    public function getTteClientesTipoPersonaRel()
    {
        return $this->tteClientesTipoPersonaRel;
    }

    /**
     * @param mixed $tteClientesTipoPersonaRel
     */
    public function setTteClientesTipoPersonaRel($tteClientesTipoPersonaRel): void
    {
        $this->tteClientesTipoPersonaRel = $tteClientesTipoPersonaRel;
    }



}


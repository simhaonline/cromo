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
     * @ORM\OneToMany(targetEntity="App\Entity\General\GenConfiguracion", mappedBy="tipoPersonaRel")
     */
    protected $configuracionTipoPersonaRel;

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

    /**
     * @return mixed
     */
    public function getInvTercerosTipoPersonaRel()
    {
        return $this->invTercerosTipoPersonaRel;
    }

    /**
     * @param mixed $invTercerosTipoPersonaRel
     */
    public function setInvTercerosTipoPersonaRel($invTercerosTipoPersonaRel): void
    {
        $this->invTercerosTipoPersonaRel = $invTercerosTipoPersonaRel;
    }

    /**
     * @return mixed
     */
    public function getConfiguracionTipoPersonaRel()
    {
        return $this->configuracionTipoPersonaRel;
    }

    /**
     * @param mixed $configuracionTipoPersonaRel
     */
    public function setConfiguracionTipoPersonaRel($configuracionTipoPersonaRel): void
    {
        $this->configuracionTipoPersonaRel = $configuracionTipoPersonaRel;
    }

    /**
     * @return mixed
     */
    public function getTurClientesTipoPersonaRel()
    {
        return $this->turClientesTipoPersonaRel;
    }

    /**
     * @param mixed $turClientesTipoPersonaRel
     */
    public function setTurClientesTipoPersonaRel($turClientesTipoPersonaRel): void
    {
        $this->turClientesTipoPersonaRel = $turClientesTipoPersonaRel;
    }



}


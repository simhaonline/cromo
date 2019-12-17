<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenRegimenRepository")
 */
class GenRegimen
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=3, nullable=false, unique=true)
     */
    private $codigoRegimenPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=3, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCliente", mappedBy="regimenRel")
     */
    protected $tteClientesRegimenRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvTercero", mappedBy="regimenRel")
     */
    protected $invTercerosRegimenRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCliente", mappedBy="regimenRel")
     */
    protected $turClientesRegimenRel;

    /**
     * @return mixed
     */
    public function getCodigoRegimenPk()
    {
        return $this->codigoRegimenPk;
    }

    /**
     * @param mixed $codigoRegimenPk
     */
    public function setCodigoRegimenPk($codigoRegimenPk): void
    {
        $this->codigoRegimenPk = $codigoRegimenPk;
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
    public function getTteClientesRegimenRel()
    {
        return $this->tteClientesRegimenRel;
    }

    /**
     * @param mixed $tteClientesRegimenRel
     */
    public function setTteClientesRegimenRel($tteClientesRegimenRel): void
    {
        $this->tteClientesRegimenRel = $tteClientesRegimenRel;
    }



}


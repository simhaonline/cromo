<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenResponsabilidadFiscalRepository")
 */
class GenResponsabilidadFiscal
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, nullable=false, unique=true)
     */
    private $codigoResponsabilidadFiscalPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=10, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCliente", mappedBy="responsabilidadFiscalRel")
     */
    protected $tteClientesResponsabilidadFiscalRel;

    /**
     * @return mixed
     */
    public function getCodigoResponsabilidadFiscalPk()
    {
        return $this->codigoResponsabilidadFiscalPk;
    }

    /**
     * @param mixed $codigoResponsabilidadFiscalPk
     */
    public function setCodigoResponsabilidadFiscalPk($codigoResponsabilidadFiscalPk): void
    {
        $this->codigoResponsabilidadFiscalPk = $codigoResponsabilidadFiscalPk;
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
    public function getTteClientesResponsabilidadFiscalRel()
    {
        return $this->tteClientesResponsabilidadFiscalRel;
    }

    /**
     * @param mixed $tteClientesResponsabilidadFiscalRel
     */
    public function setTteClientesResponsabilidadFiscalRel($tteClientesResponsabilidadFiscalRel): void
    {
        $this->tteClientesResponsabilidadFiscalRel = $tteClientesResponsabilidadFiscalRel;
    }



}


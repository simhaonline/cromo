<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCargoRepository")
 */
class RhuCargo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCargoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="cargoRel")
     */
    protected $solicitudesCargoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="cargoRel")
     */
    protected $seleccionCargoRel;

    /**
     * @return mixed
     */
    public function getCodigoCargoPk()
    {
        return $this->codigoCargoPk;
    }

    /**
     * @param mixed $codigoCargoPk
     */
    public function setCodigoCargoPk($codigoCargoPk): void
    {
        $this->codigoCargoPk = $codigoCargoPk;
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
    public function getSolicitudesCargoRel()
    {
        return $this->solicitudesCargoRel;
    }

    /**
     * @param mixed $solicitudesCargoRel
     */
    public function setSolicitudesCargoRel($solicitudesCargoRel): void
    {
        $this->solicitudesCargoRel = $solicitudesCargoRel;
    }

    /**
     * @return mixed
     */
    public function getSeleccionCargoRel()
    {
        return $this->seleccionCargoRel;
    }

    /**
     * @param mixed $seleccionCargoRel
     */
    public function setSeleccionCargoRel($seleccionCargoRel): void
    {
        $this->seleccionCargoRel = $seleccionCargoRel;
    }


}

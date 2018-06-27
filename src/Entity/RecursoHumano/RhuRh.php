<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuRhRepository")
 */
class RhuRh
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_rh_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoRhPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="rhRel")
     */
    protected $rhuAspirantesRhRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="rhRel")
     */
    protected $rhuSeleccionRhRel;

    /**
     * @return mixed
     */
    public function getCodigoRhPk()
    {
        return $this->codigoRhPk;
    }

    /**
     * @param mixed $codigoRhPk
     */
    public function setCodigoRhPk($codigoRhPk): void
    {
        $this->codigoRhPk = $codigoRhPk;
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
    public function getRhuAspirantesRhRel()
    {
        return $this->rhuAspirantesRhRel;
    }

    /**
     * @param mixed $rhuAspirantesRhRel
     */
    public function setRhuAspirantesRhRel($rhuAspirantesRhRel): void
    {
        $this->rhuAspirantesRhRel = $rhuAspirantesRhRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionRhRel()
    {
        return $this->rhuSeleccionRhRel;
    }

    /**
     * @param mixed $rhuSeleccionRhRel
     */
    public function setRhuSeleccionRhRel($rhuSeleccionRhRel): void
    {
        $this->rhuSeleccionRhRel = $rhuSeleccionRhRel;
    }


}

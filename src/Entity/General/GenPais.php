<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenPaisRepository")
 */
class GenPais
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pais_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPaesId;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="GenDepartamento", mappedBy="paisRel")
     */
    protected $departamentosRel;

    /**
     * @return mixed
     */
    public function getCodigoPaesId()
    {
        return $this->codigoPaesId;
    }

    /**
     * @param mixed $codigoPaesId
     */
    public function setCodigoPaesId($codigoPaesId): void
    {
        $this->codigoPaesId = $codigoPaesId;
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
    public function getDepartamentosRel()
    {
        return $this->departamentosRel;
    }

    /**
     * @param mixed $departamentosRel
     */
    public function setDepartamentosRel($departamentosRel): void
    {
        $this->departamentosRel = $departamentosRel;
    }

}


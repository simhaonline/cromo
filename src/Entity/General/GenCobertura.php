<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenCoberturaRepository")
 */
class GenCobertura
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cobertura_pk", type="integer")
     */
    private $codigoCoberturaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCliente", mappedBy="coberturaRel")
     */
    protected $turClientesCoberturaRel;

    /**
     * @return mixed
     */
    public function getCodigoCoberturaPk()
    {
        return $this->codigoCoberturaPk;
    }

    /**
     * @param mixed $codigoCoberturaPk
     */
    public function setCodigoCoberturaPk($codigoCoberturaPk): void
    {
        $this->codigoCoberturaPk = $codigoCoberturaPk;
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
    public function getTurClientesCoberturaRel()
    {
        return $this->turClientesCoberturaRel;
    }

    /**
     * @param mixed $turClientesCoberturaRel
     */
    public function setTurClientesCoberturaRel($turClientesCoberturaRel): void
    {
        $this->turClientesCoberturaRel = $turClientesCoberturaRel;
    }


}
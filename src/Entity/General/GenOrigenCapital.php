<?php


namespace App\Entity\General;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenOrigenCapitalRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenOrigenCapital
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_origen_capital_pk", type="integer")
     */
    private $codigoOrigenCapitalPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="origenCapitalRel")
     */
    protected $turClientesOrigenCapitalRel;

    /**
     * @return mixed
     */
    public function getCodigoOrigenCapitalPk()
    {
        return $this->codigoOrigenCapitalPk;
    }

    /**
     * @param mixed $codigoOrigenCapitalPk
     */
    public function setCodigoOrigenCapitalPk($codigoOrigenCapitalPk): void
    {
        $this->codigoOrigenCapitalPk = $codigoOrigenCapitalPk;
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
    public function getTurClientesOrigenCapitalRel()
    {
        return $this->turClientesOrigenCapitalRel;
    }

    /**
     * @param mixed $turClientesOrigenCapitalRel
     */
    public function setTurClientesOrigenCapitalRel($turClientesOrigenCapitalRel): void
    {
        $this->turClientesOrigenCapitalRel = $turClientesOrigenCapitalRel;
    }


}
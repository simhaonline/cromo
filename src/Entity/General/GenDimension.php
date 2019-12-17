<?php


namespace App\Entity\General;


class GenDimension
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dimension_pk", type="integer")
     */
    private $codigoDimensionPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="dimensionRel")
     */
    protected $turClientesDimensionRel;

    /**
     * @return mixed
     */
    public function getCodigoDimensionPk()
    {
        return $this->codigoDimensionPk;
    }

    /**
     * @param mixed $codigoDimensionPk
     */
    public function setCodigoDimensionPk($codigoDimensionPk): void
    {
        $this->codigoDimensionPk = $codigoDimensionPk;
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
    public function getTurClientesDimensionRel()
    {
        return $this->turClientesDimensionRel;
    }

    /**
     * @param mixed $turClientesDimensionRel
     */
    public function setTurClientesDimensionRel($turClientesDimensionRel): void
    {
        $this->turClientesDimensionRel = $turClientesDimensionRel;
    }


}
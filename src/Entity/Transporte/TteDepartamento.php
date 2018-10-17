<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDepartamentoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteDepartamento
{
    public $infoLog = [
        "primaryKey" => "codigoDepartamentoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=2, nullable=false, unique=true)
     */
    private $codigoDepartamentoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TteCiudad", mappedBy="departamentoRel")
     */
    protected $ciudadesRel;

    /**
     * @return mixed
     */
    public function getCodigoDepartamentoPk()
    {
        return $this->codigoDepartamentoPk;
    }

    /**
     * @param mixed $codigoDepartamentoPk
     */
    public function setCodigoDepartamentoPk($codigoDepartamentoPk): void
    {
        $this->codigoDepartamentoPk = $codigoDepartamentoPk;
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
    public function getCiudadesRel()
    {
        return $this->ciudadesRel;
    }

    /**
     * @param mixed $ciudadesRel
     */
    public function setCiudadesRel($ciudadesRel): void
    {
        $this->ciudadesRel = $ciudadesRel;
    }



}


<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEmbargoJuzgadoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoEmbargoJuzgadoPk"},message="Ya existe el codigo de juzgado")
 */
class RhuEmbargoJuzgado
{
    public $infoLog = [
        "primaryKey" => "codigoEmbargoJuzgadoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Column(name="codigo_embargo_juzgado_pk", type="string", length=30)
     * @ORM\Id
     */
    private $codigoEmbargoJuzgadoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;    
    
    /**
     * @ORM\Column(name="oficina", type="string", length=30, nullable=true)
     */
    private $oficina;

    /**
     * @ORM\Column(name="cuenta", type="string", length=30, nullable=true)
     */
    private $cuenta;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmbargo", mappedBy="embargoJuzgadoRel")
     */
    protected $embargosEmbargoJuzgadoRel;

    /**
     * @return mixed
     */
    public function getCodigoEmbargoJuzgadoPk()
    {
        return $this->codigoEmbargoJuzgadoPk;
    }

    /**
     * @param mixed $codigoEmbargoJuzgadoPk
     */
    public function setCodigoEmbargoJuzgadoPk($codigoEmbargoJuzgadoPk): void
    {
        $this->codigoEmbargoJuzgadoPk = $codigoEmbargoJuzgadoPk;
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
    public function getOficina()
    {
        return $this->oficina;
    }

    /**
     * @param mixed $oficina
     */
    public function setOficina($oficina): void
    {
        $this->oficina = $oficina;
    }

    /**
     * @return mixed
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * @param mixed $cuenta
     */
    public function setCuenta($cuenta): void
    {
        $this->cuenta = $cuenta;
    }

    /**
     * @return mixed
     */
    public function getEmbargosEmbargoJuzgadoRel()
    {
        return $this->embargosEmbargoJuzgadoRel;
    }

    /**
     * @param mixed $embargosEmbargoJuzgadoRel
     */
    public function setEmbargosEmbargoJuzgadoRel($embargosEmbargoJuzgadoRel): void
    {
        $this->embargosEmbargoJuzgadoRel = $embargosEmbargoJuzgadoRel;
    }
}

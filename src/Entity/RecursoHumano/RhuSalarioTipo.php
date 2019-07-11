<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSalarioTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoCargoPk"},message="Ya existe el código del grupo")
 */
class RhuSalarioTipo
{
    public $infoLog = [
        "primaryKey" => "codigoCargoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_salario_tipo_pk", type="string", length=10)
     */        
    private $codigoSalarioTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="salarioTipoRel")
     */
    protected $contratosSalarioTipoRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoSalarioTipoPk()
    {
        return $this->codigoSalarioTipoPk;
    }

    /**
     * @param mixed $codigoSalarioTipoPk
     */
    public function setCodigoSalarioTipoPk($codigoSalarioTipoPk): void
    {
        $this->codigoSalarioTipoPk = $codigoSalarioTipoPk;
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
    public function getContratosSalarioTipoRel()
    {
        return $this->contratosSalarioTipoRel;
    }

    /**
     * @param mixed $contratosSalarioTipoRel
     */
    public function setContratosSalarioTipoRel($contratosSalarioTipoRel): void
    {
        $this->contratosSalarioTipoRel = $contratosSalarioTipoRel;
    }



}

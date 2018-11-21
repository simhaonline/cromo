<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuEgresoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoEgresoTipoPk"},message="Ya existe el código")
 */
class RhuEgresoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoEgresoTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_egreso_tipo_pk", type="string", length=10)
     */
    private $codigoEgresoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\OneToMany(targetEntity="RhuEgreso", mappedBy="egresoTipoRel")
     */
    protected $egresosEgresoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoEgresoTipoPk()
    {
        return $this->codigoEgresoTipoPk;
    }

    /**
     * @param mixed $codigoEgresoTipoPk
     */
    public function setCodigoEgresoTipoPk($codigoEgresoTipoPk): void
    {
        $this->codigoEgresoTipoPk = $codigoEgresoTipoPk;
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
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * @param mixed $codigoCuentaFk
     */
    public function setCodigoCuentaFk($codigoCuentaFk): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
    }

    /**
     * @return mixed
     */
    public function getEgresosEgresoTipoRel()
    {
        return $this->egresosEgresoTipoRel;
    }

    /**
     * @param mixed $egresosEgresoTipoRel
     */
    public function setEgresosEgresoTipoRel($egresosEgresoTipoRel): void
    {
        $this->egresosEgresoTipoRel = $egresosEgresoTipoRel;
    }
}

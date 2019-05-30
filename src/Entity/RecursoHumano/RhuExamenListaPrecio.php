<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuExamenListaPrecioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuExamenListaPrecio
{
    public $infoLog = [
        "primaryKey" => "codigoExamenListaPrecioPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_lista_precio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenListaPrecioPk;

    /**
     * @ORM\Column(name="codigo_entidad_examen_fk", type="integer", nullable=true)
     */
    private $codigoEntidadExamenFk;

    /**
     * @ORM\Column(name="codigo_examen_tipo_fk", type="integer", nullable=true)
     */
    private $codigoExamenTipoFk;

    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $Usuario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadExamen", inversedBy="examenListaPreciosEntidadExamenRel")
     * @ORM\JoinColumn(name="codigo_entidad_examen_fk", referencedColumnName="codigo_entidad_examen_pk")
     */
    protected $entidadExamenRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenTipo", inversedBy="examenListaPreciosExamenTipoRel")
     * @ORM\JoinColumn(name="codigo_examen_tipo_fk", referencedColumnName="codigo_examen_tipo_pk")
     */
    protected $examenTipoRel;

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
    public function getCodigoExamenListaPrecioPk()
    {
        return $this->codigoExamenListaPrecioPk;
    }

    /**
     * @param mixed $codigoExamenListaPrecioPk
     */
    public function setCodigoExamenListaPrecioPk($codigoExamenListaPrecioPk): void
    {
        $this->codigoExamenListaPrecioPk = $codigoExamenListaPrecioPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadExamenFk()
    {
        return $this->codigoEntidadExamenFk;
    }

    /**
     * @param mixed $codigoEntidadExamenFk
     */
    public function setCodigoEntidadExamenFk($codigoEntidadExamenFk): void
    {
        $this->codigoEntidadExamenFk = $codigoEntidadExamenFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoExamenTipoFk()
    {
        return $this->codigoExamenTipoFk;
    }

    /**
     * @param mixed $codigoExamenTipoFk
     */
    public function setCodigoExamenTipoFk($codigoExamenTipoFk): void
    {
        $this->codigoExamenTipoFk = $codigoExamenTipoFk;
    }

    /**
     * @return mixed
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * @param mixed $vrPrecio
     */
    public function setVrPrecio($vrPrecio): void
    {
        $this->vrPrecio = $vrPrecio;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->Usuario;
    }

    /**
     * @param mixed $Usuario
     */
    public function setUsuario($Usuario): void
    {
        $this->Usuario = $Usuario;
    }

    /**
     * @return mixed
     */
    public function getEntidadExamenRel()
    {
        return $this->entidadExamenRel;
    }

    /**
     * @param mixed $entidadExamenRel
     */
    public function setEntidadExamenRel($entidadExamenRel): void
    {
        $this->entidadExamenRel = $entidadExamenRel;
    }

    /**
     * @return mixed
     */
    public function getExamenTipoRel()
    {
        return $this->examenTipoRel;
    }

    /**
     * @param mixed $examenTipoRel
     */
    public function setExamenTipoRel($examenTipoRel): void
    {
        $this->examenTipoRel = $examenTipoRel;
    }
}

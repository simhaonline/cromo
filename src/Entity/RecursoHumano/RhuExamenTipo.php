<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuExamenTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuExamenTipo
{
    public $infoLog = [
        "primaryKey" => "codigoExamenTipoPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
    
    /**     
     * @ORM\Column(name="ingreso", type="boolean")
     */    
    private $ingreso = false;

    /**
     * @ORM\Column(name="arma", type="boolean", nullable=true)
     */
    private $arma;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenDetalle", mappedBy="examenTipoRel")
     */
    protected $examenDetallesExamenTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamenListaPrecio", mappedBy="examenTipoRel")
     */
    protected $examenListaPreciosExamenTipoRel;

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
    public function getCodigoExamenTipoPk()
    {
        return $this->codigoExamenTipoPk;
    }

    /**
     * @param mixed $codigoExamenTipoPk
     */
    public function setCodigoExamenTipoPk($codigoExamenTipoPk): void
    {
        $this->codigoExamenTipoPk = $codigoExamenTipoPk;
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
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * @param mixed $ingreso
     */
    public function setIngreso($ingreso): void
    {
        $this->ingreso = $ingreso;
    }

    /**
     * @return mixed
     */
    public function getArma()
    {
        return $this->arma;
    }

    /**
     * @param mixed $arma
     */
    public function setArma($arma): void
    {
        $this->arma = $arma;
    }

    /**
     * @return mixed
     */
    public function getExamenDetallesExamenTipoRel()
    {
        return $this->examenDetallesExamenTipoRel;
    }

    /**
     * @param mixed $examenDetallesExamenTipoRel
     */
    public function setExamenDetallesExamenTipoRel($examenDetallesExamenTipoRel): void
    {
        $this->examenDetallesExamenTipoRel = $examenDetallesExamenTipoRel;
    }

    /**
     * @return mixed
     */
    public function getExamenListaPreciosExamenTipoRel()
    {
        return $this->examenListaPreciosExamenTipoRel;
    }

    /**
     * @param mixed $examenListaPreciosExamenTipoRel
     */
    public function setExamenListaPreciosExamenTipoRel($examenListaPreciosExamenTipoRel): void
    {
        $this->examenListaPreciosExamenTipoRel = $examenListaPreciosExamenTipoRel;
    }
}

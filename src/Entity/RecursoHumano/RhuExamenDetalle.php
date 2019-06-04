<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuExamenDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuExamenDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoExamenDetallePk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenDetallePk;
    
    /**
     * @ORM\Column(name="codigo_examen_fk", type="integer", nullable=true)
     */    
    private $codigoExamenFk;

    /**
     * @ORM\Column(name="codigo_examen_tipo_fk", type="integer", nullable=true)
     */
    private $codigoExamenTipoFk;

    /**     
     * @ORM\Column(name="estado_apto", type="boolean", options={"default":false},nullable=true)
     */    
    private $estadoApto = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */
    private $estadoAprobado = 0;

    /**     
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;
    
    /**     
     * @ORM\Column(name="vr_precio", type="float")
     */    
    private $vrPrecio;
    
    /**
     * @ORM\Column(name="fecha_vence", type="date")
     */    
    private $fechaVence;    
    
    /**     
     * @ORM\Column(name="validar_vencimiento", type="boolean")
     */    
    private $validarVencimiento = 0;
    
    /**
     * @ORM\Column(name="fecha_examen", type="date", nullable=true)
     */    
    private $fechaExamen;
    
    /**
     * @ORM\Column(name="comentario", type="text", nullable=true)
     */    
    private $comentario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamen", inversedBy="examenesExamenDetalleRel")
     * @ORM\JoinColumn(name="codigo_examen_fk", referencedColumnName="codigo_examen_pk")
     */
    protected $examenRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenTipo", inversedBy="examenDetallesExamenTipoRel")
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
    public function getCodigoExamenDetallePk()
    {
        return $this->codigoExamenDetallePk;
    }

    /**
     * @param mixed $codigoExamenDetallePk
     */
    public function setCodigoExamenDetallePk($codigoExamenDetallePk): void
    {
        $this->codigoExamenDetallePk = $codigoExamenDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoExamenFk()
    {
        return $this->codigoExamenFk;
    }

    /**
     * @param mixed $codigoExamenFk
     */
    public function setCodigoExamenFk($codigoExamenFk): void
    {
        $this->codigoExamenFk = $codigoExamenFk;
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
    public function getEstadoApto()
    {
        return $this->estadoApto;
    }

    /**
     * @param mixed $estadoApto
     */
    public function setEstadoApto($estadoApto): void
    {
        $this->estadoApto = $estadoApto;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param mixed $estadoAnulado
     */
    public function setEstadoAnulado($estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
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
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * @param mixed $fechaVence
     */
    public function setFechaVence($fechaVence): void
    {
        $this->fechaVence = $fechaVence;
    }

    /**
     * @return mixed
     */
    public function getValidarVencimiento()
    {
        return $this->validarVencimiento;
    }

    /**
     * @param mixed $validarVencimiento
     */
    public function setValidarVencimiento($validarVencimiento): void
    {
        $this->validarVencimiento = $validarVencimiento;
    }

    /**
     * @return mixed
     */
    public function getFechaExamen()
    {
        return $this->fechaExamen;
    }

    /**
     * @param mixed $fechaExamen
     */
    public function setFechaExamen($fechaExamen): void
    {
        $this->fechaExamen = $fechaExamen;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return mixed
     */
    public function getExamenRel()
    {
        return $this->examenRel;
    }

    /**
     * @param mixed $examenRel
     */
    public function setExamenRel($examenRel): void
    {
        $this->examenRel = $examenRel;
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

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
     * @ORM\Column(name="codigo_examen_item_fk", type="string", length=20, nullable=true)
     */
    private $codigoExamenItemFk;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */
    private $estadoAprobado = 0;

    /**     
     * @ORM\Column(name="vr_precio", type="float")
     */    
    private $vrPrecio;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuExamenItem", inversedBy="examenItemsExamenDetalleRel")
     * @ORM\JoinColumn(name="codigo_examen_item_fk", referencedColumnName="codigo_examen_item_pk")
     */
    protected $examenItemRel;

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
    public function getCodigoExamenItemFk()
    {
        return $this->codigoExamenItemFk;
    }

    /**
     * @param mixed $codigoExamenItemFk
     */
    public function setCodigoExamenItemFk($codigoExamenItemFk): void
    {
        $this->codigoExamenItemFk = $codigoExamenItemFk;
    }

    /**
     * @return int
     */
    public function getEstadoAprobado(): int
    {
        return $this->estadoAprobado;
    }

    /**
     * @param int $estadoAprobado
     */
    public function setEstadoAprobado(int $estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
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

    /**
     * @return mixed
     */
    public function getExamenItemRel()
    {
        return $this->examenItemRel;
    }

    /**
     * @param mixed $examenItemRel
     */
    public function setExamenItemRel($examenItemRel): void
    {
        $this->examenItemRel = $examenItemRel;
    }


}

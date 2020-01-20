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
     * @ORM\Column(name="codigo_examen_entidad_fk", type="integer", nullable=true)
     */
    private $codigoExamenEntidadFk;


    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $Usuario;

    /**
     * @ORM\Column(name="codigo_examen_item_fk", type="string", length=20, nullable=true)
     */
    private $codigoExamenItemFk;

    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenEntidad", inversedBy="examenListaPreciosExamenEntidadRel")
     * @ORM\JoinColumn(name="codigo_examen_entidad_fk", referencedColumnName="codigo_examen_entidad_pk")
     */
    protected $examenEntidadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuExamenItem", inversedBy="examenItemsExamenListaPrecioRel")
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
    public function getCodigoExamenEntidadFk()
    {
        return $this->codigoExamenEntidadFk;
    }

    /**
     * @param mixed $codigoExamenEntidadFk
     */
    public function setCodigoExamenEntidadFk($codigoExamenEntidadFk): void
    {
        $this->codigoExamenEntidadFk = $codigoExamenEntidadFk;
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
     * @return mixed
     */
    public function getExamenEntidadRel()
    {
        return $this->examenEntidadRel;
    }

    /**
     * @param mixed $examenEntidadRel
     */
    public function setExamenEntidadRel($examenEntidadRel): void
    {
        $this->examenEntidadRel = $examenEntidadRel;
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

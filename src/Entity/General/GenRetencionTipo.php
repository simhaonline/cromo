<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenRetencionTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenRetencionTipo
{
    public $infoLog = [
        "primaryKey" => "codigoRetencionTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_retencion_tipo_pk",type="integer")
     */
    private $codigoRetencionTipoPk;

    /**
     * @ORM\Column(name="nombre" , type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(name="base_retencion" , type="float")
     */
    private $baseRetencion;

    /**
     * @ORM\Column(name="porcentaje_retencion" , type="float")
     */
    private $porcentajeRetencion;

    /**
     * @return mixed
     */
    public function getCodigoRetencionTipoPk()
    {
        return $this->codigoRetencionTipoPk;
    }

    /**
     * @param mixed $codigoRetencionTipoPk
     */
    public function setCodigoRetencionTipoPk($codigoRetencionTipoPk): void
    {
        $this->codigoRetencionTipoPk = $codigoRetencionTipoPk;
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
    public function getBaseRetencion()
    {
        return $this->baseRetencion;
    }

    /**
     * @param mixed $baseRetencion
     */
    public function setBaseRetencion($baseRetencion): void
    {
        $this->baseRetencion = $baseRetencion;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeRetencion()
    {
        return $this->porcentajeRetencion;
    }

    /**
     * @param mixed $porcentajeRetencion
     */
    public function setPorcentajeRetencion($porcentajeRetencion): void
    {
        $this->porcentajeRetencion = $porcentajeRetencion;
    }


}

<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAporteTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoAporteTipoPk"},message="Ya existe el código")
 *
 */
class RhuAporteTipo
{
    public $infoLog = [
        "primaryKey" => "codigoAporteTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aporte_tipo_pk", type="string", length=10)
     */
    private $codigoAporteTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true, options={"default":1})
     */
    private $consecutivo;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\OneToMany(targetEntity="RhuAporte", mappedBy="aporteTipoRel")
     */
    protected $aportesAporteTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoAporteTipoPk()
    {
        return $this->codigoAporteTipoPk;
    }

    /**
     * @param mixed $codigoAporteTipoPk
     */
    public function setCodigoAporteTipoPk($codigoAporteTipoPk): void
    {
        $this->codigoAporteTipoPk = $codigoAporteTipoPk;
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
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * @return mixed
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * @param mixed $codigoComprobanteFk
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk): void
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;
    }

    /**
     * @return mixed
     */
    public function getAportesAporteTipoRel()
    {
        return $this->aportesAporteTipoRel;
    }

    /**
     * @param mixed $aportesAporteTipoRel
     */
    public function setAportesAporteTipoRel($aportesAporteTipoRel): void
    {
        $this->aportesAporteTipoRel = $aportesAporteTipoRel;
    }



}
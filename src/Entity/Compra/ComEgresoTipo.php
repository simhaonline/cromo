<?php

namespace App\Entity\Compra;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Compra\ComEgresoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoEgresoTipoPk"},message="Ya existe un registro con el mismo codigo")
 */
class ComEgresoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoEgresoTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_egreso_tipo_pk", type="string", length=10)
     */
    private $codigoEgresoTipoPk;

    /**
     * @ORM\Column(name="nombre" ,type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo" ,type="integer")
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="codigo_comprobante_fk" , type="string" , nullable=true)
     */
    private $codigoComprobanteFk = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compra\ComEgreso" ,mappedBy="egresoTipoRel")
     */
    private $egresosEgresoTipoRel;

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

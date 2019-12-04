<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarIngresoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoIngresoTipoPk"},message="Ya existe un registro con el mismo codigo")
 */
class CarIngresoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoIngresoTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_ingreso_tipo_pk", type="string", length=10)
     */
    private $codigoIngresoTipoPk;

    /**
     * @ORM\Column(name="nombre" ,type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo" ,type="integer")
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="orden" ,type="integer")
     */
    private $orden = 0;

    /**
     * @ORM\Column(name="codigo_comprobante_fk" , type="string" , nullable=true)
     */
    private $codigoComprobanteFk = null;

    /**
     * @return mixed
     */
    public function getCodigoIngresoTipoPk()
    {
        return $this->codigoIngresoTipoPk;
    }

    /**
     * @param mixed $codigoIngresoTipoPk
     */
    public function setCodigoIngresoTipoPk($codigoIngresoTipoPk): void
    {
        $this->codigoIngresoTipoPk = $codigoIngresoTipoPk;
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
     * @return int
     */
    public function getConsecutivo(): int
    {
        return $this->consecutivo;
    }

    /**
     * @param int $consecutivo
     */
    public function setConsecutivo(int $consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * @return int
     */
    public function getOrden(): int
    {
        return $this->orden;
    }

    /**
     * @param int $orden
     */
    public function setOrden(int $orden): void
    {
        $this->orden = $orden;
    }

    /**
     * @return null
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * @param null $codigoComprobanteFk
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk): void
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;
    }


}

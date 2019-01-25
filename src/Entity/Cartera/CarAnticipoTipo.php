<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarAnticipoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"orden"},message="El orden ingresado ya existe")
 */
class CarAnticipoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoAnticipoTipoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, nullable=false, unique=true)
     */
    private $codigoAnticipoTipoPk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCuentaCobrarTipoFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="orden", type="integer", nullable=true, unique=true)
     */
    private $orden = 0;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true, options={"default" : null})
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="prefijo", type="string", length=20, nullable=true, options={"default" : null})
     */
    private $prefijo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarAnticipo", mappedBy="anticipoTipoRel")
     */
    protected $anticiposAnticipoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrarTipo", inversedBy="cuentaCobrarTipoRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_tipo_fk", referencedColumnName="codigo_cuenta_cobrar_tipo_pk")
     */
    protected $cuentaCobrarTipoRel;

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
    public function getCodigoAnticipoTipoPk()
    {
        return $this->codigoAnticipoTipoPk;
    }

    /**
     * @param mixed $codigoAnticipoTipoPk
     */
    public function setCodigoAnticipoTipoPk($codigoAnticipoTipoPk): void
    {
        $this->codigoAnticipoTipoPk = $codigoAnticipoTipoPk;
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
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param mixed $orden
     */
    public function setOrden($orden): void
    {
        $this->orden = $orden;
    }

    /**
     * @return mixed
     */
    public function getAnticiposAnticipoTipoRel()
    {
        return $this->anticiposAnticipoTipoRel;
    }

    /**
     * @param mixed $anticiposAnticipoTipoRel
     */
    public function setAnticiposAnticipoTipoRel($anticiposAnticipoTipoRel): void
    {
        $this->anticiposAnticipoTipoRel = $anticiposAnticipoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarTipoFk()
    {
        return $this->codigoCuentaCobrarTipoFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarTipoFk
     */
    public function setCodigoCuentaCobrarTipoFk($codigoCuentaCobrarTipoFk): void
    {
        $this->codigoCuentaCobrarTipoFk = $codigoCuentaCobrarTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarTipoRel()
    {
        return $this->cuentaCobrarTipoRel;
    }

    /**
     * @param mixed $cuentaCobrarTipoRel
     */
    public function setCuentaCobrarTipoRel($cuentaCobrarTipoRel): void
    {
        $this->cuentaCobrarTipoRel = $cuentaCobrarTipoRel;
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
    public function getPrefijo()
    {
        return $this->prefijo;
    }

    /**
     * @param mixed $prefijo
     */
    public function setPrefijo($prefijo): void
    {
        $this->prefijo = $prefijo;
    }



}

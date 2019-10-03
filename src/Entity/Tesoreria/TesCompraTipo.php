<?php

namespace App\Entity\Tesoreria;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Tesoreria\TesCompraTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoCompraTipoPk"},message="Ya existe un registro con el mismo codigo")
 */
class TesCompraTipo
{
    public $infoLog = [
        "primaryKey" => "codigoCompraTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_compra_tipo_pk", type="string", length=10)
     */
    private $codigoCompraTipoPk;

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
     * @ORM\Column(name="codigo_cuenta_pagar_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCuentaPagarTipoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tesoreria\TesCuentaPagarTipo", inversedBy="comprasTiposCuentaPagarTipoRel")
     * @ORM\JoinColumn(name="codigo_cuenta_pagar_tipo_fk", referencedColumnName="codigo_cuenta_pagar_tipo_pk")
     */
    private $cuentaPagarTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesCompra" ,mappedBy="compraTipoRel")
     */
    private $comprasCompraTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoCompraTipoPk()
    {
        return $this->codigoCompraTipoPk;
    }

    /**
     * @param mixed $codigoCompraTipoPk
     */
    public function setCodigoCompraTipoPk($codigoCompraTipoPk): void
    {
        $this->codigoCompraTipoPk = $codigoCompraTipoPk;
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
    public function getComprasCompraTipoRel()
    {
        return $this->comprasCompraTipoRel;
    }

    /**
     * @param mixed $comprasCompraTipoRel
     */
    public function setComprasCompraTipoRel($comprasCompraTipoRel): void
    {
        $this->comprasCompraTipoRel = $comprasCompraTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaPagarTipoFk()
    {
        return $this->codigoCuentaPagarTipoFk;
    }

    /**
     * @param mixed $codigoCuentaPagarTipoFk
     */
    public function setCodigoCuentaPagarTipoFk($codigoCuentaPagarTipoFk): void
    {
        $this->codigoCuentaPagarTipoFk = $codigoCuentaPagarTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCuentaPagarTipoRel()
    {
        return $this->cuentaPagarTipoRel;
    }

    /**
     * @param mixed $cuentaPagarTipoRel
     */
    public function setCuentaPagarTipoRel($cuentaPagarTipoRel): void
    {
        $this->cuentaPagarTipoRel = $cuentaPagarTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * @param mixed $codigoCuentaFk
     */
    public function setCodigoCuentaFk($codigoCuentaFk): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
    }



}

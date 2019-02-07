<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvCostoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoCostoTipoPk"},message="Ya existe el código del tipo")
 */
class InvCostoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoCostoTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_tipo_pk",type="string",length=10)
     */
    private $codigoCostoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer",options={"default" : 0},nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="prefijo", type="string", length=5, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\OneToMany(targetEntity="InvCosto", mappedBy="costoTipoRel")
     */
    protected $costosCostoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoCostoTipoPk()
    {
        return $this->codigoCostoTipoPk;
    }

    /**
     * @param mixed $codigoCostoTipoPk
     */
    public function setCodigoCostoTipoPk($codigoCostoTipoPk): void
    {
        $this->codigoCostoTipoPk = $codigoCostoTipoPk;
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

    /**
     * @return mixed
     */
    public function getCostosCostoTipoRel()
    {
        return $this->costosCostoTipoRel;
    }

    /**
     * @param mixed $costosCostoTipoRel
     */
    public function setCostosCostoTipoRel($costosCostoTipoRel): void
    {
        $this->costosCostoTipoRel = $costosCostoTipoRel;
    }



}


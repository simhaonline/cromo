<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvImportacionTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoImportacionTipoPk"},message="Ya existe el código del tipo")
 */
class InvImportacionTipo
{
    public $infoLog = [
        "primaryKey" => "codigoImportacionTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_importacion_tipo_pk",type="string",length=10)
     */
    private $codigoImportacionTipoPk;

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
     * @ORM\OneToMany(targetEntity="InvImportacion", mappedBy="importacionTipoRel")
     */
    protected $importacionesImportacionTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoImportacionTipoPk()
    {
        return $this->codigoImportacionTipoPk;
    }

    /**
     * @param mixed $codigoImportacionTipoPk
     */
    public function setCodigoImportacionTipoPk($codigoImportacionTipoPk): void
    {
        $this->codigoImportacionTipoPk = $codigoImportacionTipoPk;
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
    public function getImportacionesImportacionTipoRel()
    {
        return $this->importacionesImportacionTipoRel;
    }

    /**
     * @param mixed $importacionesImportacionTipoRel
     */
    public function setImportacionesImportacionTipoRel($importacionesImportacionTipoRel): void
    {
        $this->importacionesImportacionTipoRel = $importacionesImportacionTipoRel;
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


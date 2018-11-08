<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvImportacionTipoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoImportacionTipoPk"},message="Ya existe el código del tipo")
 */
class InvImportacionTipo
{
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
    public function setCodigoImportacionTipoPk( $codigoImportacionTipoPk ): void
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
    public function setNombre( $nombre ): void
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
    public function setConsecutivo( $consecutivo ): void
    {
        $this->consecutivo = $consecutivo;
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
    public function setImportacionesImportacionTipoRel( $importacionesImportacionTipoRel ): void
    {
        $this->importacionesImportacionTipoRel = $importacionesImportacionTipoRel;
    }



}

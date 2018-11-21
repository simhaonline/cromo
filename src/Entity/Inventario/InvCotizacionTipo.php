<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvCotizacionTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoCotizacionTipoPk"},message= "Ya existe el código del tipo")
 */
class InvCotizacionTipo
{
    public $infoLog = [
        "primaryKey" => "codigoCotizacionTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cotizacion_tipo_pk", type="string", length=10)
     */
    private $codigoCotizacionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(name="consecutivo", type="integer",options={"default" : 0},nullable=true)
     */
    private $consecutivo = 0;

    /**
     * @ORM\OneToMany(targetEntity="InvCotizacion", mappedBy="cotizacionTipoRel")
     */
    protected $cotizacionesCotizacionTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoCotizacionTipoPk()
    {
        return $this->codigoCotizacionTipoPk;
    }

    /**
     * @param mixed $codigoCotizacionTipoPk
     */
    public function setCodigoCotizacionTipoPk($codigoCotizacionTipoPk): void
    {
        $this->codigoCotizacionTipoPk = $codigoCotizacionTipoPk;
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
    public function getCotizacionesCotizacionTipoRel()
    {
        return $this->cotizacionesCotizacionTipoRel;
    }

    /**
     * @param mixed $cotizacionesCotizacionTipoRel
     */
    public function setCotizacionesCotizacionTipoRel($cotizacionesCotizacionTipoRel): void
    {
        $this->cotizacionesCotizacionTipoRel = $cotizacionesCotizacionTipoRel;
    }
}


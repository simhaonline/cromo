<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurCotizacionTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurCotizacionTipo
{
    public $infoLog = [
        "primaryKey" => "codigoCotizacionTipoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cotizacion_tipo_pk", type="string", length=20)
     */
    private $codigoCotizacionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TurCotizacion", mappedBy="cotizacionTipoRel")
     */
    protected $cotizacionesCotizacionTipoRel;

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


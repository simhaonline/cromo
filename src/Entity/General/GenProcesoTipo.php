<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenProcesoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenProcesoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoProcesoTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=1, name="codigo_proceso_tipo_pk")
     */
    private $codigoProcesoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\GenProceso", mappedBy="procesoTipoRel")
     */
    protected $procesosProcesoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoProcesoTipoPk()
    {
        return $this->codigoProcesoTipoPk;
    }

    /**
     * @param mixed $codigoProcesoTipoPk
     */
    public function setCodigoProcesoTipoPk( $codigoProcesoTipoPk ): void
    {
        $this->codigoProcesoTipoPk = $codigoProcesoTipoPk;
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
    public function getProcesosProcesoTipoRel()
    {
        return $this->procesosProcesoTipoRel;
    }

    /**
     * @param mixed $procesosProcesoTipoRel
     */
    public function setProcesosProcesoTipoRel( $procesosProcesoTipoRel ): void
    {
        $this->procesosProcesoTipoRel = $procesosProcesoTipoRel;
    }



}


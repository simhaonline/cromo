<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenProcesoRepository")
 */
class GenProceso
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, name="codigo_proceso_pk")
     */
    private $codigoProcesoPk;

    /**
     * @ORM\Column(name="codigo_proceso_tipo_fk", length=1, type="string")
     */
    private $codigoProcesoTipoFk;

    /**
     * @ORM\Column(name="codigo_modulo_fk", length=80, type="string")
     */
    private $codigoModuloFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=400, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenProcesoTipo", inversedBy="procesosProcesoTipoRel")
     * @ORM\JoinColumn(name="codigo_proceso_tipo_fk", referencedColumnName="codigo_proceso_tipo_pk")
     */
    protected $procesoTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seguridad\SegUsuarioProceso", mappedBy="procesoRel")
     */
    protected $seguridadUsuariosProcesosProcesoRel;

    /**
     * @return mixed
     */
    public function getCodigoProcesoPk()
    {
        return $this->codigoProcesoPk;
    }

    /**
     * @param mixed $codigoProcesoPk
     */
    public function setCodigoProcesoPk( $codigoProcesoPk )
    {
        $this->codigoProcesoPk = $codigoProcesoPk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoProcesoTipoFk()
    {
        return $this->codigoProcesoTipoFk;
    }

    /**
     * @param mixed $codigoProcesoTipoFk
     */
    public function setCodigoProcesoTipoFk( $codigoProcesoTipoFk )
    {
        $this->codigoProcesoTipoFk = $codigoProcesoTipoFk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoModuloFk()
    {
        return $this->codigoModuloFk;
    }

    /**
     * @param mixed $codigoModuloFk
     */
    public function setCodigoModuloFk( $codigoModuloFk )
    {
        $this->codigoModuloFk = $codigoModuloFk;
        return $this;
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
    public function setNombre( $nombre )
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcesoTipoRel()
    {
        return $this->procesoTipoRel;
    }

    /**
     * @param mixed $procesoTipoRel
     */
    public function setProcesoTipoRel( $procesoTipoRel )
    {
        $this->procesoTipoRel = $procesoTipoRel;
        return $this;
    }


}


<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPagoTipoRepository")
 */
class RhuPagoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_tipo_pk", type="string", length=10)
     */
    private $codigoPagoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */         
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacion", mappedBy="pagoTipoRel")
     */
    protected $programacionesPagoTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoPagoTipoPk()
    {
        return $this->codigoPagoTipoPk;
    }

    /**
     * @param mixed $codigoPagoTipoPk
     */
    public function setCodigoPagoTipoPk($codigoPagoTipoPk): void
    {
        $this->codigoPagoTipoPk = $codigoPagoTipoPk;
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
    public function getProgramacionesPagoTipoRel()
    {
        return $this->programacionesPagoTipoRel;
    }

    /**
     * @param mixed $programacionesPagoTipoRel
     */
    public function setProgramacionesPagoTipoRel($programacionesPagoTipoRel): void
    {
        $this->programacionesPagoTipoRel = $programacionesPagoTipoRel;
    }
}
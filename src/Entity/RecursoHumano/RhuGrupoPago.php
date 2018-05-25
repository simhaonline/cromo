<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuGrupoPagoRepository")
 */
class RhuGrupoPago
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_grupo_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoGrupoPagoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="grupoPagoRel")
     */
    protected $solicitudesGrupoPagoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="grupoPagoRel")
     */
    protected $seleccionGrupoPagoRel;

    /**
     * @return mixed
     */
    public function getCodigoGrupoPagoPk()
    {
        return $this->codigoGrupoPagoPk;
    }

    /**
     * @param mixed $codigoGrupoPagoPk
     */
    public function setCodigoGrupoPagoPk($codigoGrupoPagoPk): void
    {
        $this->codigoGrupoPagoPk = $codigoGrupoPagoPk;
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
    public function getSolicitudesGrupoPagoRel()
    {
        return $this->solicitudesGrupoPagoRel;
    }

    /**
     * @param mixed $solicitudesGrupoPagoRel
     */
    public function setSolicitudesGrupoPagoRel($solicitudesGrupoPagoRel): void
    {
        $this->solicitudesGrupoPagoRel = $solicitudesGrupoPagoRel;
    }

    /**
     * @return mixed
     */
    public function getSeleccionGrupoPagoRel()
    {
        return $this->seleccionGrupoPagoRel;
    }

    /**
     * @param mixed $seleccionGrupoPagoRel
     */
    public function setSeleccionGrupoPagoRel($seleccionGrupoPagoRel): void
    {
        $this->seleccionGrupoPagoRel = $seleccionGrupoPagoRel;
    }


}

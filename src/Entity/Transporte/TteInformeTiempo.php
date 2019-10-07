<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteInformeTiempoRepository")
 */
class TteInformeTiempo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoInformeTiempoPk;

    /**
     * @ORM\Column(name="fecha_ingreso", type="date", nullable=true)
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(name="fecha_ruta", type="date", nullable=true)
     */
    private $fechaRuta;

    /**
     * @ORM\Column(name="fecha_entrega", type="date", nullable=true)
     */
    private $fechaEntrega;

    /**
     * @ORM\Column(name="dias", type="integer", nullable=true, options={"default":0})
     */
    private $dias;

    /**
     * @ORM\Column(name="estado_entregado", type="boolean", options={"default":false})
     */
    private $estadoEntregado = false;

    /**
     * @return mixed
     */
    public function getCodigoInformeTiempoPk()
    {
        return $this->codigoInformeTiempoPk;
    }

    /**
     * @param mixed $codigoInformeTiempoPk
     */
    public function setCodigoInformeTiempoPk($codigoInformeTiempoPk): void
    {
        $this->codigoInformeTiempoPk = $codigoInformeTiempoPk;
    }

    /**
     * @return mixed
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * @param mixed $fechaIngreso
     */
    public function setFechaIngreso($fechaIngreso): void
    {
        $this->fechaIngreso = $fechaIngreso;
    }

    /**
     * @return mixed
     */
    public function getFechaRuta()
    {
        return $this->fechaRuta;
    }

    /**
     * @param mixed $fechaRuta
     */
    public function setFechaRuta($fechaRuta): void
    {
        $this->fechaRuta = $fechaRuta;
    }

    /**
     * @return mixed
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * @param mixed $fechaEntrega
     */
    public function setFechaEntrega($fechaEntrega): void
    {
        $this->fechaEntrega = $fechaEntrega;
    }

    /**
     * @return mixed
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
    }

    /**
     * @return mixed
     */
    public function getEstadoEntregado()
    {
        return $this->estadoEntregado;
    }

    /**
     * @param mixed $estadoEntregado
     */
    public function setEstadoEntregado($estadoEntregado): void
    {
        $this->estadoEntregado = $estadoEntregado;
    }



}

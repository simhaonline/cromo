<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteReciboRepository")
 */
class TteRecibo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoReciboPk;

    /**
     * @ORM\Column(name="numero", type="float", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="codigo_recibo_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoReciboTipoFk;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_manejo", type="float")
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;

    /**
     * @return mixed
     */
    public function getCodigoReciboPk()
    {
        return $this->codigoReciboPk;
    }

    /**
     * @param mixed $codigoReciboPk
     */
    public function setCodigoReciboPk($codigoReciboPk): void
    {
        $this->codigoReciboPk = $codigoReciboPk;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getCodigoReciboTipoFk()
    {
        return $this->codigoReciboTipoFk;
    }

    /**
     * @param mixed $codigoReciboTipoFk
     */
    public function setCodigoReciboTipoFk($codigoReciboTipoFk): void
    {
        $this->codigoReciboTipoFk = $codigoReciboTipoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOperacionFk()
    {
        return $this->codigoOperacionFk;
    }

    /**
     * @param mixed $codigoOperacionFk
     */
    public function setCodigoOperacionFk($codigoOperacionFk): void
    {
        $this->codigoOperacionFk = $codigoOperacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * @param mixed $codigoClienteFk
     */
    public function setCodigoClienteFk($codigoClienteFk): void
    {
        $this->codigoClienteFk = $codigoClienteFk;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * @param mixed $vrFlete
     */
    public function setVrFlete($vrFlete): void
    {
        $this->vrFlete = $vrFlete;
    }

    /**
     * @return mixed
     */
    public function getVrManejo()
    {
        return $this->vrManejo;
    }

    /**
     * @param mixed $vrManejo
     */
    public function setVrManejo($vrManejo): void
    {
        $this->vrManejo = $vrManejo;
    }

    /**
     * @return mixed
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
    }



}


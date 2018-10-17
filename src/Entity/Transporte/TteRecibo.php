<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteReciboRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteRecibo
{
    public $infoLog = [
        "primaryKey" => "codigoReciboPk",
        "todos"     => true,
    ];
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
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */
    private $codigoGuiaFk;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_relacion_caja_fk", type="integer", nullable=true)
     */
    private $codigoRelacionCajaFk;

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
     * @ORM\Column(name="estado_relacion", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoRelacion = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteRelacionCaja", inversedBy="recibosRelacionCajaRel")
     * @ORM\JoinColumn(name="codigo_relacion_caja_fk", referencedColumnName="codigo_relacion_caja_pk")
     */
    private $relacionCajaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="recibosOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="recibosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteGuia", inversedBy="recibosGuiaRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    private $guiaRel;

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
    public function getCodigoRelacionCajaFk()
    {
        return $this->codigoRelacionCajaFk;
    }

    /**
     * @param mixed $codigoRelacionCajaFk
     */
    public function setCodigoRelacionCajaFk($codigoRelacionCajaFk): void
    {
        $this->codigoRelacionCajaFk = $codigoRelacionCajaFk;
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

    /**
     * @return mixed
     */
    public function getRelacionCajaRel()
    {
        return $this->relacionCajaRel;
    }

    /**
     * @param mixed $relacionCajaRel
     */
    public function setRelacionCajaRel($relacionCajaRel): void
    {
        $this->relacionCajaRel = $relacionCajaRel;
    }

    /**
     * @return mixed
     */
    public function getOperacionRel()
    {
        return $this->operacionRel;
    }

    /**
     * @param mixed $operacionRel
     */
    public function setOperacionRel($operacionRel): void
    {
        $this->operacionRel = $operacionRel;
    }

    /**
     * @return mixed
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * @param mixed $clienteRel
     */
    public function setClienteRel($clienteRel): void
    {
        $this->clienteRel = $clienteRel;
    }

    /**
     * @return mixed
     */
    public function getGuiaRel()
    {
        return $this->guiaRel;
    }

    /**
     * @param mixed $guiaRel
     */
    public function setGuiaRel($guiaRel): void
    {
        $this->guiaRel = $guiaRel;
    }

    /**
     * @return mixed
     */
    public function getEstadoRelacion()
    {
        return $this->estadoRelacion;
    }

    /**
     * @param mixed $estadoRelacion
     */
    public function setEstadoRelacion($estadoRelacion): void
    {
        $this->estadoRelacion = $estadoRelacion;
    }

    /**
     * @return mixed
     */
    public function getCodigoGuiaFk()
    {
        return $this->codigoGuiaFk;
    }

    /**
     * @param mixed $codigoGuiaFk
     */
    public function setCodigoGuiaFk($codigoGuiaFk): void
    {
        $this->codigoGuiaFk = $codigoGuiaFk;
    }



}


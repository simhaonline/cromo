<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteCondicionRepository")
 */
class TteCondicion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoCondicionPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_precio_fk", type="integer", nullable=true)
     */
    private $codigoPrecioFk;

    /**
     * @ORM\Column(name="porcentaje_manejo", type="float", options={"default" : 0})
     */
    private $porcentajeManejo = 0;

    /**
     * @ORM\Column(name="manejo_minimo_unidad", type="float", options={"default" : 0})
     */
    private $manejoMinimoUnidad = 0;

    /**
     * @ORM\Column(name="manejo_minimo_despacho", type="float", options={"default" : 0})
     */
    private $manejoMinimoDespacho = 0;

    /**
     * @ORM\Column(name="precio_peso", type="boolean", nullable=true)
     */
    private $precioPeso = false;

    /**
     * @ORM\Column(name="precio_unidad", type="boolean", nullable=true)
     */
    private $precioUnidad = false;

    /**
     * @ORM\Column(name="precio_adicional", type="boolean", nullable=true)
     */
    private $precioAdicional = false;

    /**
     * @ORM\Column(name="descuento_peso", type="float", options={"default" : 0})
     */
    private $descuentoPeso = 0;

    /**
     * @ORM\Column(name="descuento_unidad", type="float", options={"default" : 0})
     */
    private $descuentoUnidad = 0;

    /**
     * @ORM\Column(name="peso_minimo", type="integer", options={"default" : 0})
     */
    private $pesoMinimo = 0;

    /**
     * @ORM\Column(name="permite_recaudo", type="boolean", nullable=true)
     */
    private $permiteRecaudo = false;

    /**
     * @ORM\Column(name="precio_general", type="boolean", nullable=true)
     */
    private $precioGeneral = false;

    /**
     * @ORM\Column(name="redondear_flete", type="boolean", nullable=true)
     */
    private $redondearFlete = false;

    /**
     * @ORM\Column(name="limitar_descuento_reexpedicion", type="boolean", nullable=true)
     */
    private $limitarDescuentoReexpedicion = false;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="TtePrecio", inversedBy="condicionesPrecioRel")
     * @ORM\JoinColumn(name="codigo_precio_fk", referencedColumnName="codigo_precio_pk")
     */
    private $precioRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="condicionRel")
     */
    protected $guiasCondicionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCliente", mappedBy="condicionRel")
     */
    protected $clientesCondicionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteClienteCondicion", mappedBy="condicionRel")
     */
    protected $clintesCondicionesCondicionRel;

    /**
     * @return mixed
     */
    public function getCodigoCondicionPk()
    {
        return $this->codigoCondicionPk;
    }

    /**
     * @param mixed $codigoCondicionPk
     */
    public function setCodigoCondicionPk($codigoCondicionPk): void
    {
        $this->codigoCondicionPk = $codigoCondicionPk;
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
    public function getCodigoPrecioFk()
    {
        return $this->codigoPrecioFk;
    }

    /**
     * @param mixed $codigoPrecioFk
     */
    public function setCodigoPrecioFk($codigoPrecioFk): void
    {
        $this->codigoPrecioFk = $codigoPrecioFk;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeManejo()
    {
        return $this->porcentajeManejo;
    }

    /**
     * @param mixed $porcentajeManejo
     */
    public function setPorcentajeManejo($porcentajeManejo): void
    {
        $this->porcentajeManejo = $porcentajeManejo;
    }

    /**
     * @return mixed
     */
    public function getManejoMinimoUnidad()
    {
        return $this->manejoMinimoUnidad;
    }

    /**
     * @param mixed $manejoMinimoUnidad
     */
    public function setManejoMinimoUnidad($manejoMinimoUnidad): void
    {
        $this->manejoMinimoUnidad = $manejoMinimoUnidad;
    }

    /**
     * @return mixed
     */
    public function getManejoMinimoDespacho()
    {
        return $this->manejoMinimoDespacho;
    }

    /**
     * @param mixed $manejoMinimoDespacho
     */
    public function setManejoMinimoDespacho($manejoMinimoDespacho): void
    {
        $this->manejoMinimoDespacho = $manejoMinimoDespacho;
    }

    /**
     * @return mixed
     */
    public function getPrecioPeso()
    {
        return $this->precioPeso;
    }

    /**
     * @param mixed $precioPeso
     */
    public function setPrecioPeso($precioPeso): void
    {
        $this->precioPeso = $precioPeso;
    }

    /**
     * @return mixed
     */
    public function getPrecioUnidad()
    {
        return $this->precioUnidad;
    }

    /**
     * @param mixed $precioUnidad
     */
    public function setPrecioUnidad($precioUnidad): void
    {
        $this->precioUnidad = $precioUnidad;
    }

    /**
     * @return mixed
     */
    public function getPrecioAdicional()
    {
        return $this->precioAdicional;
    }

    /**
     * @param mixed $precioAdicional
     */
    public function setPrecioAdicional($precioAdicional): void
    {
        $this->precioAdicional = $precioAdicional;
    }

    /**
     * @return mixed
     */
    public function getDescuentoPeso()
    {
        return $this->descuentoPeso;
    }

    /**
     * @param mixed $descuentoPeso
     */
    public function setDescuentoPeso($descuentoPeso): void
    {
        $this->descuentoPeso = $descuentoPeso;
    }

    /**
     * @return mixed
     */
    public function getDescuentoUnidad()
    {
        return $this->descuentoUnidad;
    }

    /**
     * @param mixed $descuentoUnidad
     */
    public function setDescuentoUnidad($descuentoUnidad): void
    {
        $this->descuentoUnidad = $descuentoUnidad;
    }

    /**
     * @return mixed
     */
    public function getPesoMinimo()
    {
        return $this->pesoMinimo;
    }

    /**
     * @param mixed $pesoMinimo
     */
    public function setPesoMinimo($pesoMinimo): void
    {
        $this->pesoMinimo = $pesoMinimo;
    }

    /**
     * @return mixed
     */
    public function getPermiteRecaudo()
    {
        return $this->permiteRecaudo;
    }

    /**
     * @param mixed $permiteRecaudo
     */
    public function setPermiteRecaudo($permiteRecaudo): void
    {
        $this->permiteRecaudo = $permiteRecaudo;
    }

    /**
     * @return mixed
     */
    public function getPrecioGeneral()
    {
        return $this->precioGeneral;
    }

    /**
     * @param mixed $precioGeneral
     */
    public function setPrecioGeneral($precioGeneral): void
    {
        $this->precioGeneral = $precioGeneral;
    }

    /**
     * @return mixed
     */
    public function getRedondearFlete()
    {
        return $this->redondearFlete;
    }

    /**
     * @param mixed $redondearFlete
     */
    public function setRedondearFlete($redondearFlete): void
    {
        $this->redondearFlete = $redondearFlete;
    }

    /**
     * @return mixed
     */
    public function getLimitarDescuentoReexpedicion()
    {
        return $this->limitarDescuentoReexpedicion;
    }

    /**
     * @param mixed $limitarDescuentoReexpedicion
     */
    public function setLimitarDescuentoReexpedicion($limitarDescuentoReexpedicion): void
    {
        $this->limitarDescuentoReexpedicion = $limitarDescuentoReexpedicion;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return mixed
     */
    public function getPrecioRel()
    {
        return $this->precioRel;
    }

    /**
     * @param mixed $precioRel
     */
    public function setPrecioRel($precioRel): void
    {
        $this->precioRel = $precioRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasCondicionRel()
    {
        return $this->guiasCondicionRel;
    }

    /**
     * @param mixed $guiasCondicionRel
     */
    public function setGuiasCondicionRel($guiasCondicionRel): void
    {
        $this->guiasCondicionRel = $guiasCondicionRel;
    }

    /**
     * @return mixed
     */
    public function getClientesCondicionRel()
    {
        return $this->clientesCondicionRel;
    }

    /**
     * @param mixed $clientesCondicionRel
     */
    public function setClientesCondicionRel($clientesCondicionRel): void
    {
        $this->clientesCondicionRel = $clientesCondicionRel;
    }

    /**
     * @return mixed
     */
    public function getClintesCondicionesCondicionRel()
    {
        return $this->clintesCondicionesCondicionRel;
    }

    /**
     * @param mixed $clintesCondicionesCondicionRel
     */
    public function setClintesCondicionesCondicionRel($clintesCondicionesCondicionRel): void
    {
        $this->clintesCondicionesCondicionRel = $clintesCondicionesCondicionRel;
    }


}


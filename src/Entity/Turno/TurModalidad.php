<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurModalidadRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurModalidad
{
    public $infoLog = [
        "primaryKey" => "codigoModalidadPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_modalidad_pk", type="string", length=10)
     */
    private $codigoModalidadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="porcentaje", type="float", options={"default":0})
     */
    private $porcentaje = 0;

    /**
     * @ORM\OneToMany(targetEntity="TurContratoDetalle", mappedBy="modalidadRel")
     */
    protected $contratosDetallesModalidadRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="modalidadRel")
     */
    protected $pedidosDetallesModalidadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoServicio", mappedBy="modalidadRel")
     */
    protected $costosServiciosModalidadServicioRel;

    /**
     * @ORM\OneToMany(targetEntity="TurContratoDetalleCompuesto", mappedBy="modalidadRel")
     */
    protected $contratoDetallesCompuestosModalidadRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleCompuesto", mappedBy="modalidadRel")
     */
    protected $pedidosDetallesCompuestosModalidadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurFacturaDetalle", mappedBy="modalidadRel")
     */
    protected $facturasDetallesModalidadRel;

    /**
     * @return mixed
     */
    public function getCodigoModalidadPk()
    {
        return $this->codigoModalidadPk;
    }

    /**
     * @param mixed $codigoModalidadPk
     */
    public function setCodigoModalidadPk($codigoModalidadPk): void
    {
        $this->codigoModalidadPk = $codigoModalidadPk;
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
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje($porcentaje): void
    {
        $this->porcentaje = $porcentaje;
    }

    /**
     * @return mixed
     */
    public function getContratosDetallesModalidadRel()
    {
        return $this->contratosDetallesModalidadRel;
    }

    /**
     * @param mixed $contratosDetallesModalidadRel
     */
    public function setContratosDetallesModalidadRel($contratosDetallesModalidadRel): void
    {
        $this->contratosDetallesModalidadRel = $contratosDetallesModalidadRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosDetallesModalidadRel()
    {
        return $this->pedidosDetallesModalidadRel;
    }

    /**
     * @param mixed $pedidosDetallesModalidadRel
     */
    public function setPedidosDetallesModalidadRel($pedidosDetallesModalidadRel): void
    {
        $this->pedidosDetallesModalidadRel = $pedidosDetallesModalidadRel;
    }

    /**
     * @return mixed
     */
    public function getCostosServiciosModalidadServicioRel()
    {
        return $this->costosServiciosModalidadServicioRel;
    }

    /**
     * @param mixed $costosServiciosModalidadServicioRel
     */
    public function setCostosServiciosModalidadServicioRel($costosServiciosModalidadServicioRel): void
    {
        $this->costosServiciosModalidadServicioRel = $costosServiciosModalidadServicioRel;
    }

    /**
     * @return mixed
     */
    public function getContratoDetallesCompuestosModalidadRel()
    {
        return $this->contratoDetallesCompuestosModalidadRel;
    }

    /**
     * @param mixed $contratoDetallesCompuestosModalidadRel
     */
    public function setContratoDetallesCompuestosModalidadRel($contratoDetallesCompuestosModalidadRel): void
    {
        $this->contratoDetallesCompuestosModalidadRel = $contratoDetallesCompuestosModalidadRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosDetallesCompuestosModalidadRel()
    {
        return $this->pedidosDetallesCompuestosModalidadRel;
    }

    /**
     * @param mixed $pedidosDetallesCompuestosModalidadRel
     */
    public function setPedidosDetallesCompuestosModalidadRel($pedidosDetallesCompuestosModalidadRel): void
    {
        $this->pedidosDetallesCompuestosModalidadRel = $pedidosDetallesCompuestosModalidadRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasDetallesModalidadRel()
    {
        return $this->facturasDetallesModalidadRel;
    }

    /**
     * @param mixed $facturasDetallesModalidadRel
     */
    public function setFacturasDetallesModalidadRel($facturasDetallesModalidadRel): void
    {
        $this->facturasDetallesModalidadRel = $facturasDetallesModalidadRel;
    }




}


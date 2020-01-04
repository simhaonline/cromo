<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurConceptoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurConcepto
{
    public $infoLog = [
        "primaryKey" => "codigoConceptoPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=500, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="nombre_facturacion", type="string", length=500, nullable=true)
     */
    private $nombreFacturacion;

    /**
     * @ORM\Column(name="horas", options={"default":0}, type="float", nullable=true)
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="horas_diurnas", options={"default":0}, type="float", nullable=true)
     */
    private $horasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_nocturnas", options={"default":0}, type="float", nullable=true)
     */
    private $horasNocturnas = 0;

    /**
     * @ORM\OneToMany(targetEntity="TurContratoDetalle", mappedBy="conceptoRel")
     */
    protected $contratosDetallesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="conceptoRel")
     */
    protected $pedidosDetallesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="conceptoRel")
     */
    protected $facturasDetallesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoServicio", mappedBy="conceptoRel")
     */
    protected $costosServiciosConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurContratoDetalleCompuesto", mappedBy="conceptoRel")
     */
    protected $contratoDetallesCompuestosConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurPedidoDetalleCompuesto", mappedBy="conceptoRel")
     */
    protected $pedidosDetallesCompuestosConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurClienteIca", mappedBy="conceptoRel")
     */
    protected $clientesIcaConceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoConceptoPk()
    {
        return $this->codigoConceptoPk;
    }

    /**
     * @param mixed $codigoConceptoPk
     */
    public function setCodigoConceptoPk($codigoConceptoPk): void
    {
        $this->codigoConceptoPk = $codigoConceptoPk;
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
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * @param mixed $horas
     */
    public function setHoras($horas): void
    {
        $this->horas = $horas;
    }

    /**
     * @return mixed
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * @param mixed $horasDiurnas
     */
    public function setHorasDiurnas($horasDiurnas): void
    {
        $this->horasDiurnas = $horasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * @param mixed $horasNocturnas
     */
    public function setHorasNocturnas($horasNocturnas): void
    {
        $this->horasNocturnas = $horasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getContratosDetallesConceptoRel()
    {
        return $this->contratosDetallesConceptoRel;
    }

    /**
     * @param mixed $contratosDetallesConceptoRel
     */
    public function setContratosDetallesConceptoRel($contratosDetallesConceptoRel): void
    {
        $this->contratosDetallesConceptoRel = $contratosDetallesConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosDetallesConceptoRel()
    {
        return $this->pedidosDetallesConceptoRel;
    }

    /**
     * @param mixed $pedidosDetallesConceptoRel
     */
    public function setPedidosDetallesConceptoRel($pedidosDetallesConceptoRel): void
    {
        $this->pedidosDetallesConceptoRel = $pedidosDetallesConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getCostosServiciosConceptoRel()
    {
        return $this->costosServiciosConceptoRel;
    }

    /**
     * @param mixed $costosServiciosConceptoRel
     */
    public function setCostosServiciosConceptoRel($costosServiciosConceptoRel): void
    {
        $this->costosServiciosConceptoRel = $costosServiciosConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasDetallesConceptoRel()
    {
        return $this->facturasDetallesConceptoRel;
    }

    /**
     * @param mixed $facturasDetallesConceptoRel
     */
    public function setFacturasDetallesConceptoRel($facturasDetallesConceptoRel): void
    {
        $this->facturasDetallesConceptoRel = $facturasDetallesConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getContratoDetallesCompuestosConceptoRel()
    {
        return $this->contratoDetallesCompuestosConceptoRel;
    }

    /**
     * @param mixed $contratoDetallesCompuestosConceptoRel
     */
    public function setContratoDetallesCompuestosConceptoRel($contratoDetallesCompuestosConceptoRel): void
    {
        $this->contratoDetallesCompuestosConceptoRel = $contratoDetallesCompuestosConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosDetallesCompuestosConceptoRel()
    {
        return $this->pedidosDetallesCompuestosConceptoRel;
    }

    /**
     * @param mixed $pedidosDetallesCompuestosConceptoRel
     */
    public function setPedidosDetallesCompuestosConceptoRel($pedidosDetallesCompuestosConceptoRel): void
    {
        $this->pedidosDetallesCompuestosConceptoRel = $pedidosDetallesCompuestosConceptoRel;
    }

    /**
     * @return mixed
     */
    public function getNombreFacturacion()
    {
        return $this->nombreFacturacion;
    }

    /**
     * @param mixed $nombreFacturacion
     */
    public function setNombreFacturacion($nombreFacturacion): void
    {
        $this->nombreFacturacion = $nombreFacturacion;
    }

    /**
     * @return mixed
     */
    public function getClientesIcaConceptoRel()
    {
        return $this->clientesIcaConceptoRel;
    }

    /**
     * @param mixed $clientesIcaConceptoRel
     */
    public function setClientesIcaConceptoRel($clientesIcaConceptoRel): void
    {
        $this->clientesIcaConceptoRel = $clientesIcaConceptoRel;
    }



}

